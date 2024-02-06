<?php

// Função para adicionar cor ao texto
function colorizar($texto, $codigoCor)
{
    return "\033[{$codigoCor}m{$texto}\033[0m";
}

echo "------------------------------------------------\n";

// Verifica se foram passados argumentos corretos
if ($argc !== 3) {
    echo "Comando com informações insuficientes.\n";
    echo colorizar("Uso: composer mobi-rename-route ", 31) . colorizar("NomeAtual NovoNome",33) . "\n\n";
    exit;
}

$nomeAtual = strtolower($argv[1]);
$novoNome = strtolower($argv[2]);

// Carrega o conteúdo do arquivo de rotas
$arquivoRotas = 'core/json/routes.json';

if (!file_exists($arquivoRotas)) {
    echo colorizar("Erro: O arquivo de rotas não foi encontrado.", 31) . "\n\n";
    exit;
}

$jsonRotas = file_get_contents($arquivoRotas);

if ($jsonRotas === false) {
    echo colorizar("Erro ao ler o arquivo de rotas.", 31) . "\n\n";
    exit;
}

$rotas = json_decode($jsonRotas, true);

if ($rotas === null) {
    echo colorizar("Erro ao decodificar o JSON do arquivo de rotas.", 31) . "\n\n";
    exit;
}

// Itera sobre as rotas e renomeia a rota se encontrar uma correspondência
$rotaEncontrada = false;
foreach ($rotas['routes'] as &$rota) {
    // Remove barras invertidas escapadas da comparação
    $rotaPath = str_replace('\/', '/', $rota['path']);

    if ($rotaPath === "/$nomeAtual") {
        $rota['path'] = "/$novoNome";
        $rotaEncontrada = true;
        break;
    }
}

// Salva as alterações de volta no arquivo
if ($rotaEncontrada) {
    $jsonAtualizado = json_encode($rotas, JSON_PRETTY_PRINT);

    if (file_put_contents($arquivoRotas, $jsonAtualizado) !== false) {
        echo colorizar("Rota '{$nomeAtual}' renomeada para '{$novoNome}' com sucesso.", 32) . "\n\n";
    } else {
        echo colorizar("Erro ao salvar as alterações no arquivo de rotas.", 31) . "\n\n";
    }
} else {
    echo colorizar("Rota '{$nomeAtual}' não encontrada.", 31) . "\n\n";
}
