<?php

use MatthiasMullie\Minify;

// Obtém a lista de rotas do aplicativo
$routes = $app->listRoutes();

// Obtém o caminho da página atual a partir da URL
$query = explode('pages.js/',$_SERVER['QUERY_STRING']);
$page = (substr($query[1],-1) == "/") ? "/".substr($query[1],0,-1) : "/".$query[1];

$nameF = 'mbF'.ucfirst(strtolower($app->path(2)));

// Verifica se o cabeçalho está presente ou se o modo do aplicativo é 0
if ($app->checkHeader() || APP['mode'] == 0) {

    // Se não houver rotas definidas, retorna a lista de rotas em formato JSON
    if (empty($routes['data']['routes'])) {
        echo json_encode($routes, JSON_UNESCAPED_UNICODE);
    } else {

        $exist = false;
        $allStyles = "";

        // Itera sobre as rotas definidas
        foreach ($routes['data']['routes'] as $route) {

            $controllerName = $route['controller'];
            $controllerPath = $controllerName;

            $e = explode("/",$controllerName);

            if(isset($e[1])){
                $controllerName = $e[1];
                $controllerPath = "{$e[0]}/{$e[1]}";
            }

            $path = "app/pages/$controllerPath/$controllerName.js";
            $ctrlPath = "app/pages/$controllerPath/$controllerName.controller.php";

            // Verifica se o arquivo da página existe e se a rota corresponde à página atual
            if (file_exists($path) && $route['path'] == strtolower($page)) {
                
                // REALIZA O TRATAMENTO DOS COMPONENTES
                if (file_exists($path)) {
                    $exist = $controllerName;
                }
                break;
            }
        }

        if ($exist) {
            // Define o nome da página a ser carregada
            $pageName = $exist;

            // Classe de idiomas
            $lang = new Languages\Language;
            
            // Carrega o css da página
            $allStyles .= (file_exists($path)) ? file_get_contents($path) . PHP_EOL : '';

            // Carrega o controller da página
            if(file_exists($ctrlPath)) require_once($ctrlPath);

            // Verifica se existe uma classe na controladora e vincula em uma string
            if (class_exists("$pageName")){
                $$pageName = new $pageName;
            } else {
                $errorMessage = "[error] '$pageName' page class not declared.\n";
                $mob->error($errorMessage);
            }

            // Verifica se a página tem componentes
            $components = $$pageName->components;

            foreach($components as $component){
                // Trata o nome do componente
                $component = ucfirst(strtolower($component));
                $caminho = "app/components/$component/$component";

                // Verifica e carrega o arquivo css
                if(file_exists("$caminho.js")){
                    $allStyles .= file_get_contents("$caminho.js") . PHP_EOL;
                }
            }

            echo "function $nameF(){";
            // Utiliza o Minify\CSS para compactar os estilos
            $minifier = new Minify\JS();
            $minifier->add($allStyles);
            echo $minifier->minify();
            echo "} $nameF();";
        } else {
            $errorMessage = "$page' page not found in routes file.\n";
            $mob->error($errorMessage);
        }

    }
} else {
    // Retorna uma mensagem de acesso negado caso o cabeçalho não esteja presente ou o modo seja diferente de 0
    $return = ['type' => '403', "message" => "Acesso negado!"];
    echo json_encode($return);
}
