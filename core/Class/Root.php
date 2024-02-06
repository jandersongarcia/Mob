<?php

namespace Mob;

class Root
{
    public function __construct()
    {
        // Define os dados da aplicação como constante APP
        $this->defineApp();

        // Define os dados da aplicação como constante APP
        $this->defineMailer();

        // Define a conexão como constante CONN
        $this->defineConnection();

        // Define o fuso horário da aplicação
        $this->defineTimezone();

        // Define o pacote de idioma
        $this->defineLanguage();

        // Define os dados do MobAdmin caso esteja configurado
        $this->defineMobiAdmin();
    }

    /*
    |--------------------------------------------------------------------------
    | Define a conexão como constante CONN.
    |--------------------------------------------------------------------------
    | Exemplo de uso: $conn = CONN['mysql'];
    */
    private function defineConnection()
    {
        // Caminho do arquivo de configuração do banco de dados
        $dbConfigPath = 'config/DataBase.php';

        // Verifica se o arquivo de configuração do banco de dados existe
        if (file_exists($dbConfigPath)) {
            // Carrega as configurações do banco de dados a partir do arquivo
            $dbConnect = require $dbConfigPath;

            // Obtém o tipo de dado do aplicativo e converte para minúsculas
            $data = strtolower(trim(str_replace(" ", "-", $dbConnect['app_data_type'])));

            // Verifica se o tipo de dado não está vazio
            if (!empty($data)) {
                // Verifica se o tipo de dado existe nas configurações
                if (isset($dbConnect[$data])) {
                    // Se existir, utiliza as configurações correspondentes
                    $conn = $dbConnect[$data];
                } else {
                    $e = explode('/', $_SERVER['REQUEST_URI']);
                    // Se não existir, define um array com a mensagem de erro
                    $conn = ['data' => 'ERROR'];
                    if ($e[1] != 'ctrl') {
                        $error = "O tipo de banco declarado em <strong class='text-danger'>app_data_type</strong> no arquivo <strong class='text-danger'>/config/database.php</strong> está incorreto. <br>Certifique-se de preencher esta variável com <strong class='text-danger'>'mysql'</strong> para MySQL ou <strong class='text-danger'>'pgsql'</strong> para utilizar o banco PostgreSQL.";
                        require_once('./core/Php/Error.php');
                        exit;
                    }
                }
            } else {
                // Se o tipo de dado estiver vazio, define um array indicando que está desligado
                $conn = ['data' => 'OFF'];
            }

            // Define a constante CONN com as configurações obtidas
            define('CONN', $conn);

        } else {
            // Se o arquivo de configuração do banco de dados não for encontrado, emite um erro
            trigger_error('Arquivo de configuração do banco de dados não encontrado.', E_USER_ERROR);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Obtém os parâmetros da query da URL e atribui ao $_GET.
    |--------------------------------------------------------------------------
    */
    public function get()
    {
        // Obter a parte da query da URL
        $queryString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

        // Inicializar um array para armazenar os valores
        $queryArray = [];

        // Transformar a string da query em um array associativo
        parse_str($queryString, $queryArray);

        // Atribuir os valores ao $_GET
        $_GET = $queryArray;
    }

    /*
    |--------------------------------------------------------------------------
    | Define os dados da aplicação como constante APP
    |--------------------------------------------------------------------------
    | Exemplo de uso: $appName = APP['app_name'];
    */
    private function defineApp()
    {
        $appConfigPath = 'config/App.php';

        // Verifica se o arquivo de configuração da aplicação existe
        if (file_exists($appConfigPath)) {
            $appData = require $appConfigPath;
            define('APP', $appData);
        } else {
            trigger_error('Arquivo de configuração da aplicação não encontrado.', E_USER_ERROR);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Define os dados para o envio de e-mail usando o PHPMailer
    |--------------------------------------------------------------------------
    | Exemplo de uso: $appMailer = MAIL['action'];
    */
    private function defineMailer()
    {
        $appConfigPath = 'config/Phpmailer.php';

        // Verifica se o arquivo de configuração da aplicação existe
        if (file_exists($appConfigPath)) {
            $appData = require $appConfigPath;
            define('MAIL', $appData);
        } else {
            trigger_error('Arquivo de configuração da aplicação não encontrado.', E_USER_ERROR);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Define o fuso horário a ser utilizado pelo sistema
    |--------------------------------------------------------------------------
    */
    private function defineTimezone()
    {
        $appData = APP;

        if (isset($appData['timezone'])) {
            date_default_timezone_set($appData['timezone']);
        } else {
            trigger_error('Fuso horário não definido na configuração da aplicação.', E_USER_WARNING);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Define o pacote de idioma
    |--------------------------------------------------------------------------
    */
    private function defineLanguage()
    {
        $appData = APP;

        if (isset($appData['language'])) {
            $languageFile = "languages/{$appData['language']}.php";
            if (file_exists($languageFile)) {
                require_once $languageFile;
            } else {
                trigger_error('Arquivo de idioma não encontrado.', E_USER_WARNING);
            }
        } else {
            trigger_error('Idioma não definido na configuração da aplicação.', E_USER_WARNING);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Define os dados de configurações especiais do MobiAdmin
    |--------------------------------------------------------------------------
    | Exemplo de uso: $appMailer = MADMIN['action'];
    */
    private function defineMobiAdmin(){
        $file = 'config/mobiadmin.php';
        if(file_exists($file)){
            $appData = require $file;
            define('MADMIN', $appData);
        } else {
            define('MADMIN', []);
        }
    }
}
