<?php

// Função para adicionar cor ao texto
function colorizar($texto, $codigoCor) {
    return "\033[{$codigoCor}m{$texto}\033[0m";
}

$separadorLinha = "------------------------------------------------\n";

// Verificar se o número correto de argumentos foi fornecido
if (empty($argv[1])) {
    $mensagemAtencao = colorizar("Atenção: ", 31) . "Por favor, forneça do componente a ser deletado\n";
    $uso = "Uso correto: composer mobi-delete-component " . colorizar("nome-do-componente", 32) . "\n\n";

    echo "$separadorLinha $mensagemAtencao $uso";
    exit();
}

$entrada = trim($argv[1]);

// Verificar se não começa com número
if (preg_match('/^\d/', $entrada)) {
    $msgErr = "O componente não pode começar com um número.\n";
} else {
    // Remover caracteres especiais, exceto underline
    $limpa = preg_replace('/[^a-zA-Z0-9_]/', '', $entrada);

    // Verificar se a string mudou após a remoção dos caracteres especiais
    if ($entrada !== $limpa) {
        $msgErr = "O componente não deve ter caracteres especiais, exceto underline (_).\n";
    }
}

if (isset($msgErr)) {
    $mensagemErro = colorizar("Erro: ", 31) . $msgErr;
    echo "$separadorLinha $mensagemErro\n";
    exit();
}

$nomeComponent = ucfirst($entrada);

// Verificar se o componente existe
$caminhoDiretorio = "app/components/$nomeComponent";

if (!file_exists($caminhoDiretorio)) {
    $mensagem = colorizar("Atenção: ", 31) . "O componente " . colorizar("'$nomeComponent'", 32) . " não existe em app/components\n Operação cancelada.\n\n";
    echo "$separadorLinha $mensagem";
    exit();
}

echo "$separadorLinha\nIniciando o processo de exclusão para o componente '$nomeComponent'...\n";

// Excluir arquivos associados
$caminhosArquivosExcluir = [
    "$caminhoDiretorio/$nomeComponent.controller.php",
    "$caminhoDiretorio/$nomeComponent.css",
    "$caminhoDiretorio/$nomeComponent.js",
    "$caminhoDiretorio/$nomeComponent.view.php"
];

foreach ($caminhosArquivosExcluir as $caminhoArquivo) {
    if (file_exists($caminhoArquivo)) {
        unlink($caminhoArquivo); // Excluir arquivo
        echo "Excluindo $caminhoArquivo: " . colorizar("[OK]", 32) . "\n";
    }
}

// Excluir o diretório do componente
$arquivosNoDiretorio = glob("$caminhoDiretorio/*");
if (count($arquivosNoDiretorio) === 0) {
    if (rmdir($caminhoDiretorio)) {
        echo "Exclusão do diretório $nomeComponent: " . colorizar("[OK]", 32) . "\n";
    } else {
        $ultimoErro = error_get_last();
        echo colorizar("Erro ao tentar excluir o diretório $nomeComponent: {$ultimoErro['message']}", 31) . "\n Operação cancelada.\n\n";
        exit();
    }
} else {
    echo "Erro: O diretório $nomeComponent não está vazio. Por favor, certifique-se de excluir todos os arquivos dentro do diretório antes de tentar excluir o componente.\n";
    exit();
}

echo colorizar("Componente", 33) . colorizar(" '$nomeComponent' ", 94) . colorizar("excluído com sucesso!\n\n", 33);
