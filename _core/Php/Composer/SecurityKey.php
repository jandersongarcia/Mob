<?php

// Função para adicionar cor ao texto
function colorizar($texto, $codigoCor)
{
    return "\033[{$codigoCor}m{$texto}\033[0m";
}

$separadorLinha = "\n---------------------------------------------------------\n";

$error = false;

if ($argc > 1) {
    if ($argv[1] == 'create') {
        echo $separadorLinha;
        generate();
        echo $separadorLinha."\n";

    } else if ($argv[1] == 'key') {
        echo $separadorLinha;
        printKey();
        echo $separadorLinha."\n";

    } else {
        $error = true;
    }
} else {
    $error = true;
}

if ($error) {
    $mensagemAtencao = colorizar("Atenção: ", 31) . "Comando usado de forma incorreta.\n";
    $uso = "----------------------------------------------------------------------\n";
    $uso .= " | Criar uma nova chave de segurança   | composer mob-security " . colorizar("create", 36) . " |\n";
    $uso .= " | Imprimir a chave de segurança atual | composer mob-security " . colorizar("key", 36) . "    |\n";
    $uso .= " ----------------------------------------------------------------------";
    echo "\n $mensagemAtencao $uso\n";
    exit;
}

function generate()
{

    $file = "core\\Php\\Api\\apiKey.php";

    if (file_exists($file)) {

        $config = include $file;
        $client = ($config['client'] == '') ? generateUuid() : $config['client'];
        $secret = generateUuid();
        $key = generateKey($client, $secret);

        $newContent = "<?php\n\nreturn [\n    'client' => '$client',\n    'secret' => '$secret'\n];\n";

        file_put_contents($file, $newContent);
        echo colorizar("Chave de segurança: ", 32) . "$key";

    }

}

function printKey()
{
    $file = "core\\Php\\Api\\apiKey.php";

    if (file_exists($file)) {
        $config = include $file;
        $client = ($config['client'] == '') ? generateUuid() : $config['client'];
        $secret = ($config['secret'] == '') ? generateUuid() : $config['secret'];
        $key = generateKey($client, $secret);

        $newContent = "<?php\n\nreturn [\n    'client' => '$client',\n    'secret' => '$secret'\n];\n";

        file_put_contents($file, $newContent);
        echo colorizar("Chave de segurança: ", 32) . "$key";
    }
}

function generateUuid()
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}

function generateKey($client, $secret)
{
    // Concatenar client e secret com um delimitador (pode ser qualquer string)
    $combinedString = $client . ':' . $secret;

    // Gerar um código hash HMAC usando SHA256
    $hash = hash_hmac('sha256', $combinedString, $secret);

    // Formatando a chave
    $key = sprintf(
        '%s-%s-%s-%s-%s',
        substr($hash, 0, 8),
        substr($hash, 8, 4),
        substr($hash, 12, 4),
        substr($hash, 16, 4),
        substr($hash, 20, 12)
    );

    return $key;
}


function verifyKey($key, $client, $secret)
{
    // Concatenar client e secret com um delimitador (pode ser qualquer string)
    $combinedString = $client . ':' . $secret;

    // Gerar um código hash HMAC usando SHA256
    $hash = hash_hmac('sha256', $combinedString, $secret);

    // Formatando a chave
    $expectedKey = sprintf(
        '%s-%s-%s-%s-%s',
        substr($hash, 0, 8),
        substr($hash, 8, 4),
        substr($hash, 12, 4),
        substr($hash, 16, 4),
        substr($hash, 20, 12)
    );

    // Verifica se a chave gerada é igual à esperada
    return($key === $expectedKey);
}