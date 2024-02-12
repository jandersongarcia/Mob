<?php

namespace Core\MClass;

use Core\MClass\Mob;

class Root
{

    private $mob;

    public function __construct()
    {

        $this->mob = new Mob;

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

        // Define o pacote de idiomas do MOB
        $this->defineMobLanguage();


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
                        $msg = "The database type declared in <strong class='text-danger'>app_data_type</strong> in the file <strong class='text-danger'>/config/database.php</strong> is incorrect. <br>Make sure to fill this variable with <strong class='text-danger'>'mysql'</strong> for MySQL or <strong class='text-danger'>'pgsql'</strong> to use the PostgreSQL database.";
                        $this->mob->ErrorMini("err3010",$msg);
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
            $msg = 'Database configuration file not found.';
            $this->mob->ErrorMini("err3002",$msg);
            exit;
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
            $this->mob->ErrorMini("err3003","Application configuration file not found.");
            exit;
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
            $this->mob->ErrorMini("err3004","Mail sending configuration file not found in 'config/PhpMailer.php'");
            exit;
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
            trigger_error('Item `timezone` was not found or defined in `config/App.php`', E_USER_WARNING);
            $this->mob->ErrorMini("err3005","Item `timezone` was not found or defined in `config/App.php`");
            exit;
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
                $msg = "Language file not found in `languages` directory.";
                $this->mob->ErrorMini("err3006",$msg);
                trigger_error($msg, E_USER_WARNING);
                exit;
            }
        } else {
            $msg = "Language not defined in application configuration.";
            $this->mob->ErrorMini("err3007",$msg);
            trigger_error($msg, E_USER_WARNING);
            exit;
        }
    }

    private function defineMobLanguage()
    {
        $appData = APP;

        if (isset($appData['language'])) {
            $languageFile = "core/languages/{$appData['language']}.php";
            if (file_exists($languageFile)) {
                require_once $languageFile;
            } else {
                $languageFile = "core/languages/pt-br.php";
                if (file_exists($languageFile)) {
                    require_once $languageFile;
                } else {
                    trigger_error('Arquivo de idioma da aplicação não foi encontrado.', E_USER_WARNING);
                }
            }
        }
    }
}
