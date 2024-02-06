<?php

// Inclui o autoloader do Composer para carregar as classes automaticamente
require_once 'vendor/autoload.php';

// Importa os aliases necessários do namespace 'Mobi\Root'
use Mob\Root as RootAlias;
use Mob\Application;
use Mob\Mob;
use languages\Language;

/*
|----------------------------------------------------------------
| Inicializa propriedades básicas para o funcionamento do Mobi
|----------------------------------------------------------------
*/

// Cria uma instância da classe 'RootAlias' para gerenciar propriedades básicas
$root = new RootAlias;

/*
|----------------------------------------------------------------
| Define a variável para a classe de aplicação
|----------------------------------------------------------------
*/

// Cria uma instância da classe 'Application' para gerenciar a aplicação
$app = new Application;

/*
|----------------------------------------------------------------
| Define a variável de linguagem
|----------------------------------------------------------------
*/

// Cria uma instância da classe 'Language' para gerenciar a linguagem da aplicação
$lang = new Language;

/*
|----------------------------------------------------------------
| Define as funções da classe 'Mobi'
|----------------------------------------------------------------
*/

// Cria uma instância da classe 'Mobi' para utilizar suas funções
$mob = new Mob;

$data = CONN;

// Cria a instancia do banco de dados
switch (@$data['driver']) {
    case 'mysql':
        require_once "./core/DataBase/{$data['driver']}.php";
        $sql = new Database\MySql\MySQL;
        break;

    case 'pgsql':
        require_once "./core/DataBase/{$data['driver']}.php";
        $sql = new Database\Postgre\PostgreSQL;
        break;
        // Adicione casos adicionais conforme necessário para outros drivers

    default:
        // Adicione um tratamento padrão ou uma mensagem de erro, se necessário
        //$error = "Erro desconhecido ao tentar carregar os arquivos do banco de dados;";
        //require_once('./core/php/error.php');
        //exit;
}

/*
|----------------------------------------------------------------
| Carrega o arquivo inicial da aplicação
|----------------------------------------------------------------
*/

// Caminho do arquivo inicial da aplicação
$appFilePath = 'app/App.php';

// Verifica se o caminho da aplicação é 'ctrl' para carregar o arquivo de configuração do controlador
if ($app->path(0) == 'ctrl') {
    // Página controladora
    $root->get();
    require_once('core/Php/Controllers/Controller.init.php');
} else if (file_exists($appFilePath)) {
    // Carrega a página prestart
    require_once("config/Startup.php");
    // Carrega a página principal da aplicação
    require_once($appFilePath);
} else {
    // Erro na aplicação se o arquivo inicial não for encontrado
    //die('Error: The application file was not found.');
    $app->msgError("File '$appFilePath' was not found.");
    exit();
}
