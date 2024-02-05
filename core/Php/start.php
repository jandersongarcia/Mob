<?php

// Inclui o autoloader do Composer para carregar as classes automaticamente
require_once 'vendor/autoload.php';

// Importa os aliases necessários do namespace 'Mobi\Root'
use Mobi\Root as RootAlias;
use Mobi\Routes;
use Mobi\Application;
use Mobi\Mobi;
use languages\Language;
use Sql\MySQL;

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
| Define a variável para a classe responsável pelas rotas
|----------------------------------------------------------------
*/

// Cria uma instância da classe 'Routes' para gerenciar as rotas da aplicação
$routes = new Routes;

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
$mobi = new Mobi;

$data = CONN;

// Cria a instancia do banco de dados
switch (@$data['driver']) {
    case 'mysql':
        require_once "./core/database/{$data['driver']}.php";
        $sql = new Sql\MySQL;
        break;

    case 'pgsql':
        require_once "./core/database/{$data['driver']}.php";
        $sql = new Sql\PostgreSQL;
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
$appFilePath = 'app/app.php';

// Verifica se o caminho da aplicação é 'ctrl' para carregar o arquivo de configuração do controlador
if ($app->path(0) == 'ctrl') {
    // Página controladora
    $root->get();
    require_once('core/php/controller/controller.ini.php');
} else if (file_exists($appFilePath)) {
    // Carrega a página prestart
    require_once("config/prestart.php");
    // Carrega a página principal da aplicação
    require_once($appFilePath);
} else {
    // Erro na aplicação se o arquivo inicial não for encontrado
    die('Error: The application file was not found.');
}
