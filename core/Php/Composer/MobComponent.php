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

$action = @$argv[1];
$component = @$argv[2];

$actions = ['create', 'remove'];

if (!in_array($action, $actions)) {
    echo "\n$separadorLinha";
    echo colorizar("Erro: ", 31) . "Linha de comando não reconhecida ou incompleta\n\n";
    echo "USO: " . colorizar("composer", 33) . " component " . colorizar("action nameComponent", 33) . "\n\n";
    echo " Lista de " . colorizar("actions \n", 33);
    echo $separadorLinha;
    echo exibirTabela('create ' . colorizar("nameComponent", 33), 'Cria um novo componente');
    echo exibirTabela('remove ' . colorizar("nameComponent", 33), 'Exclui um componente existente');
    echo "$separadorLinha\n";
    echo colorizar("nameComponent", 33) . " - o nome do componente que será criado\n\n";
    exit;
}

// Verificar se não começa com número
if (preg_match('/^\d/', $component)) {
    $msgErr = "O componente não pode começar com um número.\n";
} else {
    // Remover caracteres especiais, exceto underline
    $limpa = preg_replace('/[^a-zA-Z0-9_]/', '', $component);

    // Verificar se a string mudou após a remoção dos caracteres especiais
    if ($component !== $limpa) {
        $msgErr = "O componente não deve ter caracteres especiais, exceto underline (_).\n";
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
    $mensagemErro = colorizar("Erro: ", 31) . "Nome do componente inválido\n";
    echo "$separadorLinha $mensagemErro";
    exit();
}

if ($action == 'create') {

    echo $separadorLinha . "Iniciando o processo de criação...\n";

    // Verificar se já existe um componente com este nome
    if (file_exists("app/components/$nomeComponent")) {
        $mensagem = colorizar("Atenção: ", 31) . "O componente " . colorizar("'$nomeComponent'", 36) . " já existe em app/components\n Operação cancelada.\n\n";
        echo "$separadorLinha $mensagem";
        exit();
    }

    echo "Realizando o tratamento do nome do componente: " . colorizar("[OK]", 32) . "\n";

    // Tentar criar o diretório da página
    if (mkdir("app/components/$nomeComponent", 0777, true)) {
        echo "Criação do diretório $nomeComponent: " . colorizar("[OK]", 32) . "\n";
    } else {
        echo "Erro ao tentar criar o diretório " . colorizar("'$nomeComponent'", 36) . "\n Operação cancelada.\n\n";
        exit();
    }

    // Criar arquivos dentro da pasta recém-criada
    // Página View
    file_put_contents("app/Components/$nomeComponent/{$nomeComponent}View.php", "<h2> View do componente $nomeComponent</h2>");
    echo "Página View do componente $nomeComponent: " . colorizar("[OK]", 32) . "\n";

    // Página Modal
    file_put_contents("app/Components/$nomeComponent/{$nomeComponent}Modal.php", "<?php\n\nnamespace app\Components;\n\nclass $nomeComponent {\n\n    public " . '$title' . " = '$nomeComponent';\n\n    // Declarar os componentes que serão usados na página.\n    public " . '$components' . " = [];\n\n}");
    echo "Modal da página $nomeComponent: " . colorizar("[OK]", 32) . "\n";

    $arrayName = strtolower($nomeComponent);

    // Página Controller
    file_put_contents("app/Components/$nomeComponent/{$nomeComponent}Controller.php", "<?php\n\nuse app\Components\\$nomeComponent;\n\n$$arrayName = new $nomeComponent();\n\n");
    echo "Controlador da página $nomeComponent: " . colorizar("[OK]", 32) . "\n";

    // Folha de estilos CSS
    file_put_contents("app/Components/$nomeComponent/$nomeComponent.css", "/* Estilos CSS para $nomeComponent */");
    echo "Arquivo CSS para $nomeComponent: " . colorizar("[OK]", 32) . "\n";

    // JS
    file_put_contents("app/Components/$nomeComponent/$nomeComponent.js", "// Scripts JavaScript para $nomeComponent");
    echo "Arquivo JavaScript para $nomeComponent: " . colorizar("[OK]", 32) . "\n";

}

if ($action == 'remove') {
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
        "$caminhoDiretorio/{$nomeComponent}Controller.php",
        "$caminhoDiretorio/$nomeComponent.css",
        "$caminhoDiretorio/$nomeComponent.js",
        "$caminhoDiretorio/{$nomeComponent}View.php",
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
            echo colorizar("Erro ao tentar excluir o diretório $nomeComponent: {$ultimoErro['message']}", 31) . "\n Operação cancelada.\n\n";
            exit();
        }
    } else {
        echo "Erro: O diretório $nomeComponent não está vazio. Por favor, certifique-se de excluir todos os arquivos dentro do diretório antes de tentar excluir o componente.\n";
        exit();
    }

    echo colorizar("Componente", 33) . colorizar(" '$nomeComponent' ", 94) . colorizar("excluído com sucesso!\n\n", 33);
}


