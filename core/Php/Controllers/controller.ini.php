<?php

// Obtém o nome do arquivo de controller com base no caminho do aplicativo
$page = "controller.{$app->path(1)}.php";
$ctrl = ucfirst(strtolower($app->path(1)));
$type = false;
$message = '';

// Verifica se é um módulo
if (file_exists("app/modules/$ctrl")) {

    // Verifica se o cabeçalho é válido ou se o modo de aplicativo é 0 (sem verificação de cabeçalho)
    if ($app->checkHeader() || APP['mode'] == 0) {

        // Inclui o arquivo de controller
        if (file_exists("app/modules/$ctrl/$ctrl.modal.php")) {
            require_once("app/modules/$ctrl/$ctrl.modal.php");

            // Verifica se a classe do controller existe
            if (class_exists("$ctrl")) {
                $name = "$ctrl";
                $$name = new $ctrl;

                // Inclui o arquivo modal se existir
                if (file_exists("app/modules/$ctrl/$ctrl.controller.php")) {
                    require_once("app/modules/$ctrl/$ctrl.controller.php");
                }
            } else {
                // Log e mensagem de erro se a classe do controller não existir
                $type = 'error';
                $message = "The '$ctrl' class does not exist.";
            }

        } else {
            // Log e mensagem de erro se o arquivo de controller não existir
            $type = 'error';
            $message = "The '$ctrl' controller does not exist.";
        }
    } else {
        // Bloqueia o acesso ao módulo se o cabeçalho não for válido e o modo de aplicativo não for 0
        $type = 'blocked';
        $message = "Attempt to access module '$ctrl'.";
    }
} else if (file_exists("core/php/controller/$page")) {
    // Inclui o arquivo de controller a partir do diretório 'core/php/controller' se o arquivo não for encontrado no diretório 'app/controllers'
    require_once("core/php/controller/$page");
} else {
    // Mensagem de erro se o arquivo de controller não for encontrado em ambos os diretórios
    $type = 'error';
    $message = 'Controller not found';
}

// Se houver um tipo de erro definido, registra o erro e retorna a mensagem como JSON
if ($type !== false) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $errorMessage = "[" . date('Y-m-d H:i:s') . "] [$ip] [$type] $message.\n";
    $mobi->error($errorMessage);

    $msg = [
        'type' => $type,
        'msg' => $message
    ];

    // Retorna a mensagem de erro como JSON
    echo json_encode($msg, JSON_UNESCAPED_UNICODE);
}
