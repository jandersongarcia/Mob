<?php

// Função para adicionar cor ao texto
function colorizar($texto, $codigoCor) {
    return "\033[{$codigoCor}m{$texto}\033[0m";
}

$separadorLinha = "------------------------------------------------\n";

// Verificar se o número correto de argumentos foi fornecido
if (empty($argv[1])) {
    $mensagemAtencao = colorizar("Atenção: ", 31) . "Por favor, forneça do módulo a ser deletado\n";
    $uso = "Uso correto: composer mobi-delete-module " . colorizar("nome-do-modulo", 32) . "\n\n";

    echo "$separadorLinha $mensagemAtencao $uso";
    exit();
}

// Obter o nome do componente a partir dos argumentos da linha de comando
$nomeComponent = ucfirst(strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $argv[1])));

// Verificar se o componente existe
$caminhoDiretorio = "app/modules/$nomeComponent";

if (!file_exists($caminhoDiretorio)) {
    $mensagem = colorizar("Atenção: ", 31) . "O módulo " . colorizar("'$nomeComponent'", 32) . " não existe em app/modules\n Operação cancelada.\n\n";
    echo "$separadorLinha $mensagem";
    exit();
}

echo "$separadorLinha\nIniciando o processo de exclusão para o módulo '$nomeComponent'...\n";

// Excluir arquivos associados
$caminhosArquivosExcluir = [
    "$caminhoDiretorio/$nomeComponent.controller.php",
    "$caminhoDiretorio/$nomeComponent.modal.php"
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
        echo colorizar("Erro ao tentar excluir o módulo $nomeComponent: {$ultimoErro['message']}", 31) . "\n Operação cancelada.\n\n";
        exit();
    }
} else {
    echo "Erro: O diretório $nomeComponent não está vazio. Por favor, certifique-se de excluir todos os arquivos dentro do diretório antes de tentar excluir o componente.\n";
    exit();
}

echo colorizar("Componente", 33) . colorizar(" '$nomeComponent' ", 94) . colorizar("excluído com sucesso!\n\n", 33);
