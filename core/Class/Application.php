<?php

namespace Mob;

class Application
{

    /*
    |--------------------------------------------------------------------------
    | Returns the page title according to the model defined in title_model
    | present in config/app.php
    |--------------------------------------------------------------------------
    | $app->title();
    */
    public function title()
    {
        $model = APP['title_style'];
        return APP['app_name'];
    }

    /*
    |--------------------------------------------------------------------------
    | Carrega os arquivos de visualização dos componentes conforme o modelo
    | definido em title_model presente em config/app.php
    |--------------------------------------------------------------------------
    | Exemplo de chamada: $app->components(['componente1', 'componente2']);
    */
    public function components($components)
    {
        // Mensagens de erro possíveis
        $message = [
            "erro1" => "<strong>Erro:</strong> Os componentes devem ser declarados dentro de arrays:<br>Exemplo: <em>app->components(['componente1','componente2'])</em>",
            "erro2" => "<strong>Erro:</strong> O componente não foi localizado",
            "erro3" => "Mensagem de erro específica para Erro 3",
        ];

        // Verifica se $components é um array
        if (is_array($components)) {
            // Itera sobre os componentes fornecidos
            foreach ($components as $component) {
                $cmp = ucfirst(strtolower($component));
                $path = "app/components/$cmp/$cmp.view.php";

                // Verifica se o arquivo do componente existe
                if (file_exists($path)) {
                    // Inclui o arquivo do componente
                    require_once($path);
                } else {
                    // Exibe mensagem de erro se o componente não for encontrado
                    echo "<div class='alert alert-danger mx-3' role='alert'><strong>Erro:</strong> O componente $component não foi encontrado em <em>./app/components/$component</em></div>";
                }
            }
        } else {
            // Exibe mensagem de erro se $components não for um array
            echo "<div class='alert alert-danger mx-3' role='alert'>{$message['erro1']}</div>";
        }
    }   

    /*
    |--------------------------------------------------------------------------
    | Returns the main url of the page
    |--------------------------------------------------------------------------
    | $app->url();
    */
    public function url($tipo = 'principal')
    {
        // Verifica se a requisição está sendo feita através de HTTPS
        $protocolo = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';

        // Obtém o nome do host (domínio)
        $host = $_SERVER['HTTP_HOST'];

        // Constrói a URL principal
        $url_principal = $protocolo . '://' . $host;

        // Se o tipo for 'all', retorna a URL completa
        if ($tipo === 'all') {
            $caminho = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            return $url_principal . $caminho;
        }

        // Caso contrário, retorna apenas a URL principal
        return $url_principal;
    }

    /*
    |--------------------------------------------------------------------------
    | Returns parts of an active url path
    |--------------------------------------------------------------------------
    | $app->path(0);
    */
    public function path($n = 1)
    {
        $caminho = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $slicePath = explode('/', trim($caminho, '/'));

        // Verifica se $slicePath[$n] existe antes de retornar o valor
        return isset($slicePath[$n]) ? $slicePath[$n] : '';
    }

    /*
    |--------------------------------------------------------------------------
    | Lista as rotas do arquivo core/json/routes.json
    |--------------------------------------------------------------------------
    | $app->listRoutes();
    */
    public function listRoutes()
    {
        $routesFilePath = 'core/json/routes.json';

        try {
            // Verifica a existência e leitura do arquivo de rotas
            if (!file_exists($routesFilePath) || ($routesJson = file_get_contents($routesFilePath)) === false) {
                throw new \Exception('Erro ao carregar o arquivo de rotas.', 1);
            }

            // Decodifica o JSON
            $decodedRoutes = json_decode($routesJson, true);

            // Verifica se houve erro na decodificação
            if ($decodedRoutes === null) {
                throw new \Exception('Erro ao decodificar o JSON.', 2);
            }

            // Verifica se há rotas cadastradas
            if (isset($decodedRoutes['routes']) && empty($decodedRoutes['routes'])) {
                throw new \Exception('Nenhuma rota cadastrada.', 3);
            }

            // Retorna os dados da rota com sucesso
            return [
                "message" => "Rota(s) carregada(s) com sucesso.",
                "type" => "success",
                "data" => $decodedRoutes
            ];

        } catch (\Exception $e) {
            // Configuração de mensagens de erro e alertas
            $mensagem = 'Erro desconhecido';
            $comment = '';
            $icon = '<i class="bi bi-x-circle-fill"></i>';
            $tipo = 'danger';

            switch ($e->getCode()) {
                case 1:
                    $mensagem = 'Erro ao carregar o arquivo de rotas.';
                    $comment = "Verifique se o arquivo <span class='text-dark font-monospace'>routes.json</span> existe no diretório <span class='text-dark font-monospace'>core/json/</span>.";
                    break;
                case 2:
                    $mensagem = 'Erro ao decodificar o JSON.';
                    $comment = "Analise se a estrutura do JSON <span class='text-dark font-monospace'>routes.json</span> está correta. O arquivo está em <span class='text-dark font-monospace'>core/json/</span>.";
                    break;
                case 3:
                    $mensagem = 'Nenhuma rota cadastrada.';
                    $icon = '<i class="bi bi-exclamation-triangle-fill"></i>';
                    $comment = "Crie uma página através do comando: <span class='text-dark font-monospace'>composer mobi-create-page <em>nome-da-pagina</em> <em>nome-da-rota</em></span>";
                    $tipo = 'warning';
                    break;
            }

            // Retorna as informações de erro ou alerta
            return [
                "icon" => $icon,
                "message" => "$mensagem",
                "comment" => "$comment",
                "type" => $tipo
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Verifica se o host recebido é o mesmo do destino
    | Uma pequena camada de segurança
    |--------------------------------------------------------------------------
    | $app->checkHeader();
    */

    public function checkHeader(){
        if (isset($_SERVER['HTTP_REFERER'])) {
            // Extrai o host do cabeçalho Referer
            $refererHost = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
        
            // Extrai o host do servidor
            $serverHost = $_SERVER['HTTP_HOST'];
        
            // Verifica se os hosts são iguais
            if ($refererHost === $serverHost) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}
