<?php

// Importa namespaces necessários
use Core\MClass\Mob;

$mob = new Mob();

// Obtém o caminho da página atual a partir da URL
$page = "/" . strtolower($app->path(2));

// Analisa a URL para extrair o caminho da página
$query = explode('components/', $_SERVER['QUERY_STRING']);
$page = (substr($query[1], -1) == "/") ? "/" . substr($query[1], 0, -1) : "/" . $query[1];

// Verifica se o cabeçalho está presente ou se o modo do aplicativo é 0
if ($app->checkHeader() || APP['mode'] == 0) {

    $page = ucfirst($page);
    
    $file = "app/Components/$page/";
    $modal = "$file/{$page}Modal.php";
    $controller = "$file/{$page}Controller.php";

    if(!file_exists($modal) || !file_exists($controller)){
        
        $mob->ErrorMini('err3009','Problem when trying to load the Components MC',false);
        exit();
    }

    require_once($modal);
    require_once($controller);

} else {
    // Retorna uma mensagem de acesso negado caso o cabeçalho não esteja presente ou o modo seja diferente de 0
    $return = ['type' => '403', "message" => "Acesso negado!"];
    echo json_encode($return);
}