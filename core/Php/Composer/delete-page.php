<?php

// Função para adicionar cor ao texto
function colorizar($texto, $codigoCor) {
    return "\033[{$codigoCor}m{$texto}\033[0m";
}

$separadorLinha = "------------------------------------------------\n";

// Verificar se o número correto de argumentos foi fornecido
if ($argc !== 2 || empty($argv[1])) {
    $mensagemAtencao = colorizar("Atenção: ", 31) . "Por favor, forneça o nome da página a ser deletada\n";
    $uso = "Uso correto: composer mobi-delete-page " . colorizar("nome-da-pagina", 32) . "\n\n";

    echo "$separadorLinha $mensagemAtencao $uso";
    exit();
}

// Obter o nome da página e o nome da rota a partir dos argumentos da linha de comando
$entrada = trim($argv[1]);

// Verificar se não começa com número
if (preg_match('/^\d/', $entrada)) {
    $msgErr = "O nome da página não pode começar com um número.\n";
} else {
    if (preg_match('/[^a-zA-Z0-9_-]/', $entrada)) {
        $msgErr = "O nome da página não deve conter caracteres especiais, exceto hífen (-) ou underline (_).\n";
    }
}

$nomePagina = ucfirst($entrada);

// Verificar se a página existe
$caminhoDiretorio = "app/pages/$nomePagina";

if (!file_exists($caminhoDiretorio)) {
    $mensagem = colorizar("Atenção: ", 31) . "A página " . colorizar("'$nomePagina'", 32) . " não existe em app/pages\n Operação cancelada.\n\n";
    echo "$separadorLinha $mensagem";
    exit();
}

echo "$separadorLinha\nIniciando o processo de exclusão para a página '$nomePagina'...\n";

// Excluir arquivos associados
$caminhosArquivosExcluir = [
    "$caminhoDiretorio/$nomePagina.controller.php",
    "$caminhoDiretorio/$nomePagina.css",
    "$caminhoDiretorio/$nomePagina.js",
    "$caminhoDiretorio/$nomePagina.view.php"
];

foreach ($caminhosArquivosExcluir as $caminhoArquivo) {
    if (file_exists($caminhoArquivo)) {
        unlink($caminhoArquivo); // Excluir arquivo
    }
}

// Excluir o diretório da página
$arquivosNoDiretorio = glob("$caminhoDiretorio/*");
if (count($arquivosNoDiretorio) === 0) {
    if (rmdir($caminhoDiretorio)) {
        echo "Exclusão do diretório $nomePagina: " . colorizar("[OK]", 32) . "\n";
    } else {
        $ultimoErro = error_get_last();
        echo colorizar("Erro ao tentar excluir o diretório $nomePagina: {$ultimoErro['message']}", 31) . "\n Operação cancelada.\n\n";
        exit();
    }
} else {
    echo "Erro: O diretório $nomePagina não está vazio. Por favor, certifique-se de excluir todos os arquivos dentro do diretório antes de tentar excluir a página.\n";
    exit();
}

// Carregar rotas existentes do arquivo JSON
$caminhoArquivoRotas = 'core/json/routes.json';
if (file_exists($caminhoArquivoRotas)) {
    $rotasJson = file_get_contents($caminhoArquivoRotas);
    $rotas = json_decode($rotasJson, true);

    // Remover a rota correspondente do JSON
    $rotas['routes'] = array_filter($rotas['routes'], function ($rota) use ($nomePagina) {
        return $rota['controller'] !== $nomePagina;
    });

    // Salvar o JSON atualizado de volta ao arquivo
    file_put_contents($caminhoArquivoRotas, json_encode($rotas, JSON_PRETTY_PRINT));

    echo "Rota correspondente removida de routes.json: " . colorizar("[OK]", 32) . "\n";
}

echo colorizar("Página", 33) . colorizar(" '$nomePagina' ", 94) . colorizar("excluída com sucesso!\n\n", 33);
