<?php

// Inclui o autoloader do Composer para carregar as classes automaticamente
require_once ROOT . 'vendor/autoload.php';

// Importa os aliases necessários do namespace 'Mobi\Root'
use Core\MClass\Root as RootAlias;
use Core\MClass\Application;
use Core\MClass\Mob;
use MatthiasMullie\Minify;
use languages\Language;

require_once 'core/Packages/PanelControl.php';

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
| Define as funções da classe 'Mob'
|----------------------------------------------------------------
*/

// Cria uma instância da classe 'Mob' para utilizar suas funções
$mob = new Mob;

$data = CONN;

$driver = ucfirst(@$data['driver']);

// Cria a instancia do banco de dados
switch ($driver) {
    case 'Mysql':
        require_once ROOT."/core/DataBase/$driver.php";
        $sql = new Database\MySQL\MySQL;
        break;

    case 'Pgsql':
        
        require_once ROOT."/core/DataBase/$driver}.php";
        $sql = new Database\Postgre\PostgreSQL;
        break;
    default:
}

/*
|----------------------------------------------------------------
| Carrega o arquivo inicial da aplicação
|----------------------------------------------------------------
*/

// Conteúdo da página PHP
ob_start();

// Caminho do arquivo inicial da aplicação
$appFilePath = 'app/App.php';

// Verifica se o caminho da aplicação é 'ctrl' para carregar o arquivo de configuração do controlador
if ($app->path(0) == 'ctrl') {
    // Página controladora
    $root->get();
    require_once(ROOT . '/core/Php/Controllers/Controller.init.php');
} else if ($app->path(0) == 'api'){
    require_once(ROOT . '/core/Php/Api/apiModal.php');
    require_once(ROOT . '/core/Php/Api/apiController.php');
    require_once(ROOT . '/core/Php/Api/apiView.php');
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

$output = ob_get_clean();

if (APP['minify']) {
    $output = preg_replace('/\s+/', ' ', $output);
}

echo $output;