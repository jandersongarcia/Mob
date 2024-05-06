<?php

// Obtém todos os cabeçalhos da requisição
$headers = apache_request_headers();

// Verifica se o cabeçalho 'Authorization' está presente na requisição
if (isset($headers['Authorization'])) {

    // Obtém o valor do cabeçalho 'Authorization'
    $authorizationHeader = $headers['Authorization'];
    $client = isset($headers['Client']) ? $headers['Client'] : @$headers['client'];

    // Verifica se a string 'Bearer' está presente no cabeçalho
    if (strpos($authorizationHeader, 'Bearer') !== false) {

        // Divide o cabeçalho para obter o tipo e o token
        list($tokenType, $token) = explode(' ', $authorizationHeader);

        // O valor do token está na variável $token
        if ($api->verify($client, $token)) {

            $api->module();

        } else {
            $array = [
                'error' => 'Falha na verificação do token'
            ];
            echo json_encode($array, JSON_UNESCAPED_UNICODE);
        }
    } else {
        // Tipo de token inválido
        $array = [
            'error' => 'Token de autenticação inválido'
        ];
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
    }

} else {
    // Cabeçalho 'Authorization' não está presente na requisição
    $array = [
        'error' => 'Cabeçalho de autorização ausente'
    ];
    echo json_encode($array, JSON_UNESCAPED_UNICODE);
}