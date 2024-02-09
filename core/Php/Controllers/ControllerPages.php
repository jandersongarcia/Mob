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

            $e = explode("/", $controllerName);

            if (isset($e[1])) {
                $controllerName = ucfirst($e[1]);
                $controllerPath = ucfirst("{$e[0]}/{$e[1]}");
            }

            $files = [
                'modal' => "app/Pages/$controllerPath/{$controllerName}Modal.php",
                'controller' => "app/Pages/$controllerPath/{$controllerName}Controller.php",
                'view' => "app/Pages/$controllerPath/{$controllerName}View.php",
                'css' => "app/Pages/$controllerPath/$controllerName.css",
            ];

            if ($route['path'] == $page){

                if($app->fileExists($files)){
                    $exist = $files;
                } else {
                    $msg = "We have identified an inconsistency in the MVC structure of this page.";
                    $app->msgError($msg,0);
                    $mob->error($msg);
                }

                break;
            }
        }

        if ($exist) {
            // Define o nome da página a ser carregada
            $files = $exist;

            // Classe de idiomas
            $lang = new Languages\Language;

            // Carrega o css da página
            $allStyles = (file_exists($files['css'])) ? file_get_contents($files['css']) : '';

            // Carrega o css
            // Utiliza o Minify\CSS para compactar os estilos
            $minifier = new Minify\CSS();
            $minifier->add($allStyles);
            

            try {
                $lang = new Language();
                require_once($files['modal']);
                require_once($files['controller']);

                // Carrega os componentes declarados no modal
                $var = strtolower($controllerName);
                if(isset($$var->components)){
                    $minifier->add(printCss($$var->components));
                }

                echo "<style>{$minifier->minify()}</style>";
                require_once($files['view']);
                //require_once($path);
            } catch (\Exception $e) {
                // Log or print the error
                echo 'Caught exception: ', $e->getMessage(), "\n";
            }

        } 
    }
} else {
    // Retorna uma mensagem de acesso negado caso o cabeçalho não esteja presente ou o modo seja diferente de 0
    $return = ['type' => '403', "message" => "Acesso negado!"];
    echo json_encode($return);
}

function printCss($array){
    $css = '';
    if(is_array($array) && !empty($array)){
        header('Content-Type: text/css');

        foreach($array as $key){
            $key = ucfirst($key);
            $file = "app/Components/$key/$key.css";

            if(file_exists($file)){
                $idName = "#{$key}Component";
                $lines = file($file);
                foreach ($lines as $line) {
                    $var = htmlspecialchars(trim($line));
                    $procura = array('.', '#', 'h1', 'h2', 'h3', 'h4');
                
                    $primeiroCaractere = substr($var, 0, 1);
                
                    // Verificar se o primeiro caractere está na lista de busca
                    if (in_array($primeiroCaractere, $procura)) {
                        $css .= "$idName $var\n";
                    } else {
                        $css .= "$var\n";
                    }

                    //$css = str_replace("$idName $idName","$idName",$css);
                }
            }
        }
    }

    // Remove quebras de linha e excesso de espaços
    //$css = preg_replace('/\s+/', ' ', $css);
    //$css = str_replace(["\r\n", "\r", "\n", "\t"], '', $css);
    //$css = str_replace('#'.$key.'MBComponent to','to',$css);

    // Remove #PreloaderMBComponent se o próximo caractere for um número ou @
    //$css = preg_replace('/#'.$key.'Component (?=[0-9@])/', '', $css);

    return $css;
}
