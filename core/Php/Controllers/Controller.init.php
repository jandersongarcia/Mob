<?php

// Obtém o nome do arquivo de controller com base no caminho do aplicativo
$page = "Controller".ucfirst($app->path(1)).".php";
$ctrl = ucfirst($app->path(1));
$type = false;
$message = '';
$path = 'app/Modules/';

// Verifica se é um módulo
if (file_exists("$path$ctrl")) {

    // Verifica se o cabeçalho é válido ou se o modo de aplicativo é 0 (sem verificação de cabeçalho)
    if ($app->checkHeader() || APP['mode'] == 0) {

        $file = [
            "app/Modules/$ctrl/{$ctrl}Modal.php",
            "app/Modules/$ctrl/{$ctrl}Controller.php"
        ];

        $error = false;

        foreach($file as $url){
            if(!file_exists($url)){
                $error = true;
            } else {
                require_once($url);
            }
        }

        if($error){
            // Log e mensagem de erro se a classe do controller não existir
            $type = 'error';
            $message = "An error was encountered when trying to load the $ctrl module.";
        }

        /*
        // Inclui o arquivo de controller
        if (file_exists("app/Modules/$ctrl/{$ctrl}modal.php")) {
            require_once("app/Modules/$ctrl/{$ctrl}modal.php");

            // Verifica se a classe do controller existe
            if (class_exists("$ctrl")) {
                $name = "$ctrl";
                $$name = new $ctrl;

                // Inclui o arquivo modal se existir
                if (file_exists("app/Modules/$ctrl/{$ctrl}Controller.php")) {
                    require_once("app/Modules/$ctrl/{$ctrl}Controller.php");
                }
            } else {
                // Log e mensagem de erro se a classe do controller não existir
                $type = 'error';
                $message = "The '$ctrl' class does not exist.";
            }

        } else {
            // Log e mensagem de erro se o arquivo de controller não existir
            $type = 'error';
            $message = "The '$ctrl' controller does not exist in '$path'.";
        }
        */
    } else {
        // Bloqueia o acesso ao módulo se o cabeçalho não for válido e o modo de aplicativo não for 0
        $type = 'blocked';
        $message = "Attempt to access module '$ctrl'.";
    }
} else if (file_exists("core/Php/Controllers/$page")) {
    // Inclui o arquivo de controller a partir do diretório 'core/php/controller' se o arquivo não for encontrado no diretório 'app/controllers'
    require_once("core/Php/Controllers/$page");
} else {
    // Mensagem de erro se o arquivo de controller não for encontrado em ambos os diretórios
    $type = 'error';
    $message = 'Controller not found';
}

// Se houver um tipo de erro definido, registra o erro e retorna a mensagem como JSON
if ($type !== false) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $errorMessage = "$message";
    $mob->error($errorMessage);

    $msg = [
        'type' => $type,
        'msg' => $message
    ];

    // Retorna a mensagem de erro como JSON
    echo json_encode($msg, JSON_UNESCAPED_UNICODE);
}
