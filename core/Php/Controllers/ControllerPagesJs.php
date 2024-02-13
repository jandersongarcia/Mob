<?php

use MatthiasMullie\Minify;


// Obtém a lista de rotas do aplicativo
$routes = $app->listRoutes();

// Obtém o caminho da página atual a partir da URL
$page = "/" . strtolower($app->path(2));

$query = explode('pagesjs/', $_SERVER['QUERY_STRING']);

$page = (substr($query[1], -1) == "/") ? "/" . substr($query[1], 0, -1) : "/" . $query[1];

// Verifica se o cabeçalho está presente ou se o modo do aplicativo é 0
if ($app->checkHeader() || APP['mode'] == 0) {

    // Se não houver rotas definidas, retorna a lista de rotas em formato JSON
    if (empty($routes['data']['routes'])) {
        echo json_encode($routes, JSON_UNESCAPED_UNICODE);
    } else {

        $exist = false;

        // Itera sobre as rotas definidas
        foreach ($routes['data']['routes'] as $route) {

            $controllerName = $route['controller'];
            $controllerPath = $controllerName;

            $e = explode("/", $controllerName);

            if (isset($e[1])) {
                $controllerName = ucfirst($e[1]);
                $controllerPath = ucfirst("{$e[0]}/{$e[1]}");
            }

            $js = "app/Pages/$controllerPath/$controllerName.js";
            $modal = "app/Pages/$controllerPath/{$controllerName}Modal.php";
            $controller = "app/Pages/$controllerPath/{$controllerName}Controller.php";

            // Verifica se o arquivo da página existe e se a rota corresponde à página atual
            if (file_exists($js) && $route['path'] == strtolower($page)) {
                // REALIZA O TRATAMENTO DOS COMPONENTES
                $exist = $controllerName;
                break;
            }
        }

        //Procura pelo pacote preloader
        $packages = "core/Json/Packages.json";
        if(file_exists($packages)){
            $packages = json_decode(file_get_contents($packages),true);
            $preloader = $packages['packages']['preloader'];
            $preName = ucfirst($preloader['name']);
            $load = isset($preloader['enabled']) ? $preloader['enabled'] : false;
            if(isset($preloader['files']) && $load === true){
                $local = "packages/$preName/{$preloader['files']['js']}";
                if(file_exists($local)){
                    $prePackages =  file_get_contents($local) . PHP_EOL;
                }
            }
        }

        if ($exist) {
            // Define o nome da página a ser carregada
            $pageName = $exist;

            // Carrega o css da página
            $scriptJs = (file_exists($js)) ? file_get_contents($js) . PHP_EOL : '';

            $scriptJs .= @$prePackages;

            // Verifica se existem componentes com scripts
            if(file_exists($modal) && file_exists($controller)){

                require_once($modal);
                require_once($controller);

                // Carrega os componentes declarados no modal
                $var = strtolower($controllerName);
                if(isset($$var->components)){
                    $scriptJs .=(printJS($$var->components));
                }

            }

            echo "function $controllerName(){";
            // Utiliza o Minify\CSS para compactar os estilos
            $minifier = new Minify\JS();
            $minifier->add($scriptJs);
            echo $minifier->minify();
            echo "} $controllerName();";
        } else {
            $errorMessage = "$page' page not found in routes file.";
            $mob->error($errorMessage);
        }

    }
} else {
    // Retorna uma mensagem de acesso negado caso o cabeçalho não esteja presente ou o modo seja diferente de 0
    $return = ['type' => '403', "message" => "Acesso negado!"];
    echo json_encode($return);
}

function printJS($array){
    $jsContent = '';

    if(is_array($array) && !empty($array)){
        header('Content-Type: text/javascript');

        foreach($array as $key){
            $key = ucfirst($key);
            $file = "app/Components/$key/$key.js";

            if(file_exists($file)){
                $jsContent .= file_get_contents($file);
            }
        }
    }

    return $jsContent;
}
