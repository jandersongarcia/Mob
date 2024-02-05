<?php

use Languages\Language;
use MatthiasMullie\Minify;

// Obtém a lista de rotas do aplicativo
$routes = $app->listRoutes();

// Obtém o caminho da página atual a partir da URL
$page = "/" . strtolower($app->path(2));

$query = explode('pages/', $_SERVER['QUERY_STRING']);
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

            $e = explode("/",$controllerName);

            if(isset($e[1])){
                $controllerName = $e[1];
                $controllerPath = "{$e[0]}/{$e[1]}";
            }

            $path = "app/pages/$controllerPath/$controllerName.view.php";
            $cssPath = "app/pages/$controllerPath/$controllerName.css";
            $ctrlPath = "app/pages/$controllerPath/$controllerName.controller.php";

            // Verifica se o arquivo da página existe e se a rota corresponde à página atual
            if (file_exists($path) && $route['path'] == $page) {

                // REALIZA O TRATAMENTO DOS COMPONENTES
                if (file_exists($ctrlPath)) {
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
            $allStyles = (file_exists($cssPath)) ? file_get_contents($cssPath) : '';

            // Carrega o controller da página
            if (file_exists($ctrlPath))
                require_once($ctrlPath);

            // Verifica se existe uma classe na controladora e vincula em uma string
            if (class_exists("$pageName")) {
                $$pageName = new $pageName;
            } else {
                $errorMessage = "'$pageName' page class not declared.\n";
                $mobi->error($errorMessage);
            }

            // Verifica se a página tem componentes
            $components = $$pageName->components;

            foreach ($components as $component) {
                // Trata o nome do componente
                $component = ucfirst(strtolower($component));
                $caminho = "app/components/$component/$component";

                // Verifica e carrega o arquivo css
                if (file_exists("$caminho.css")) {
                    $allStyles .= file_get_contents("$caminho.css");
                }

                // Verifica e carrega o controller
                if (file_exists("$caminho.controller.php")) {
                    require_once("$caminho.controller.php");
                    // Verifica se existe uma classe na controladora e vincula em uma string
                    if (class_exists("$component")) {
                        $$component = new $component;
                    } else {
                        $errorMessage = "[error] '$component' component class not declared.\n";
                        $mobi->error($errorMessage);
                    }
                }
            }

            // Carrega o css
            // Utiliza o Minify\CSS para compactar os estilos
            $minifier = new Minify\CSS();
            $minifier->add($allStyles);
            echo "<style>{$minifier->minify()}</style>";

            try {
                $lang = new Language();
                require_once($path);
            } catch (\Exception $e) {
                // Log or print the error
                echo 'Caught exception: ', $e->getMessage(), "\n";
            }

        } else {
            $errorMessage = "[error] '$page' page not found in routes file.\n";
            $mobi->error($errorMessage);
        }

    }
} else {
    // Retorna uma mensagem de acesso negado caso o cabeçalho não esteja presente ou o modo seja diferente de 0
    $return = ['type' => '403', "message" => "Acesso negado!"];
    echo json_encode($return);
}
