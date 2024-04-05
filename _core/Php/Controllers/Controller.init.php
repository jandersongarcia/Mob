<?php

// Obtém o nome do arquivo de controller com base no caminho do aplicativo
$page = "Controller".ucfirst($app->path(1)).".php";
$ctrl = ucfirst($app->path(1));
$type = false;
$message = '';
$path = 'app/Modules/';

if(APP['mode'] == 1){

    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Extrai o domínio da requisição
        $origin = $_SERVER['HTTP_ORIGIN'];
    
        // Verifica se o domínio da requisição é igual ao domínio permitido
        $allowedDomain = 'http://www.seudominio.com';
        if ($origin === $allowedDomain) {
            // O domínio da requisição é o mesmo que o domínio permitido
            // Faça o processamento da requisição aqui
            //echo "Requisição válida. Domínio permitido: $allowedDomain";
        } else {
            // O domínio da requisição não é permitido
            // Retorne um código de status 403 - Acesso Negado
            http_response_code(403);
            $type = 'error';
            $message = "Access denied. Domain not allowed: $origin";
            // Você também pode encerrar o script aqui, se desejar
            // exit();
        }
    } else {
        // Se o cabeçalho "Origin" não estiver presente na requisição
        // Isso pode indicar uma requisição não-CORS ou uma tentativa de acesso direto ao script PHP
        // Nesse caso, você pode optar por retornar um código de status 403 - Acesso Negado ou executar outras ações conforme necessário
        http_response_code(403);
        $type = 'error';
        $message = "Access denied. Missing 'Origin' header.";
    }

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
        exit();
    }    

}

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
