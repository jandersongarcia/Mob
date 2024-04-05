<?php

// Função para adicionar cor ao texto
function colorizar($texto, $codigoCor)
{
    return "\033[{$codigoCor}m{$texto}\033[0m";
}

// Função para exibir uma tabela no prompt
function exibirTabela($comando, $descricao)
{
    echo " " . str_pad($comando, 35) . "| " . str_pad($descricao, 60) . PHP_EOL;
}

$separadorLinha = "---------------------------------------------------------------\n";

$component = @$argv[1];

$actions = ['create', 'remove'];

if (!in_array($action, $actions)) {
    echo "\n$separadorLinha";
    echo colorizar("Erro: ", 31) . "Linha de comando não reconhecida ou incompleta\n\n";
    echo "USO: " . colorizar("composer", 33) . " module " . colorizar("action nameModule", 33) . "\n\n";
    echo " Lista de " . colorizar("actions \n", 33);
    echo $separadorLinha;
    echo exibirTabela('create ' . colorizar("nameModule", 33), 'Cria um novo módulo');
    echo exibirTabela('remove ' . colorizar("nameModule", 33), 'Exclui um módulo existente');
    echo "$separadorLinha\n";
    echo colorizar("nameModule", 33) . " - o nome do módulo que será criado\n\n";
    exit;
}

// Verificar se não começa com número
if (preg_match('/^\d/', $component)) {
    $msgErr = "O nome do módulo não deve começar com um número.\n";
} else {
    // Remover caracteres especiais, exceto underline
    $limpa = preg_replace('/[^a-zA-Z0-9_]/', '', $component);

    // Verificar se a string mudou após a remoção dos caracteres especiais
    if ($component !== $limpa) {
        $msgErr = "O módulo não deve conter caracteres especiais, exceto underline (_).\n";
    }
}

if (isset($msgErr)) {
    $mensagemErro = colorizar("Erro: ", 31) . $msgErr;
    echo "$separadorLinha $mensagemErro\n";
    exit();
}

$nomeComponent = ucfirst($component);

// Verificar se $nomeComponent está vazio após o tratamento
if (empty($nomeComponent)) {
    $mensagemErro = colorizar("Erro: ", 31) . "Nome do módulo inválido\n";
    echo "$separadorLinha $mensagemErro";
    exit();
}

if ($action == 'create') {

    echo $separadorLinha . "Iniciando o processo de criação...\n";

    // Verificar se já existe um componente com este nome
    if (file_exists("app/modules/$nomeComponent")) {
        $mensagem = colorizar("Atenção: ", 31) . "O módulo " . colorizar("'$nomeComponent'", 36) . " já existe em app/components\n Operação cancelada.\n\n";
        echo "$separadorLinha $mensagem";
        exit();
    }

    echo "Realizando o tratamento do nome do módulo: " . colorizar("[OK]", 32) . "\n";

    // Tentar criar o diretório da página
    if (mkdir("app/modules/$nomeComponent", 0777, true)) {
        echo "Criação do diretório $nomeComponent: " . colorizar("[OK]", 32) . "\n";
    } else {
        echo "Erro ao tentar criar o diretório " . colorizar("'$nomeComponent'", 36) . "\n Operação cancelada.\n\n";
        exit();
    }

    // Cria o Controller
    file_put_contents("app/modules/$nomeComponent/{$nomeComponent}Modal.php", "<?php\n\n// Modal $nomeComponent\n\nnamespace App\Modules;\n\nclass $nomeComponent{\n\n\n}");
    echo "Controller do módulo $nomeComponent: " . colorizar("[OK]", 32) . "\n";

    $nameClass = strtolower($nomeComponent);

    file_put_contents("app/modules/$nomeComponent/{$nomeComponent}Controller.php", "<?php\n\nuse App\Modules\\$nomeComponent;\n\n $$nameClass = new $nomeComponent();\n\n");

    echo "Modal do módulo $nomeComponent: " . colorizar("[OK]", 32) . "\n";

}

if ($action == 'remove') {

    echo "$separadorLinha\nIniciando o processo de exclusão para o módulo '$nomeComponent'...\n";

    // Verificar se o componente existe
    $caminhoDiretorio = "app/Modules/$nomeComponent";

    // Excluir arquivos associados
    $caminhosArquivosExcluir = [
        "$caminhoDiretorio/{$nomeComponent}Controller.php",
        "$caminhoDiretorio/{$nomeComponent}Modal.php"
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

    echo colorizar("Módulo", 33) . colorizar(" '$nomeComponent' ", 94) . colorizar("excluído com sucesso!\n\n", 33);


}


