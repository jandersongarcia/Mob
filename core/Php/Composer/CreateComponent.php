<?php

// Função para adicionar cor ao texto
function colorizar($texto, $codigoCor)
{
    return "\033[{$codigoCor}m{$texto}\033[0m";
}

$separadorLinha = "------------------------------------------------\n\n";

// Verificar se o argumento foi informado
if (empty($argv[1])) {
    $mensagemAtencao = colorizar("Atenção: ", 31) . "Forneça o nome do componente\n";
    $uso = "Uso correto: composer mobi-create-component " . colorizar("nome-do-componente", 36) . "\n\n";
    echo "$separadorLinha $mensagemAtencao $uso)";
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

// Verificar se $nomeComponent está vazio após o tratamento
if (empty($nomeComponent)) {
    $mensagemErro = colorizar("Erro: ", 31) . "Nome do componente inválido\n";
    echo "$separadorLinha $mensagemErro";
    exit();
}

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
file_put_contents("app/components/$nomeComponent/$nomeComponent.view.php", "<h2> View do componente $nomeComponent</h2>");
echo "Página View do componente $nomeComponent: " . colorizar("[OK]", 32) . "\n";
// Folha de estilos CSS
file_put_contents("app/components/$nomeComponent/$nomeComponent.css", "/* Estilos CSS para $nomeComponent */");
echo "Arquivo CSS para $nomeComponent: " . colorizar("[OK]", 32) . "\n";
// JS
file_put_contents("app/components/$nomeComponent/$nomeComponent.js", "// Scripts JavaScript para $nomeComponent");
echo "Arquivo JavaScript para $nomeComponent: " . colorizar("[OK]", 32) . "\n";
// Página Controller
file_put_contents("app/components/$nomeComponent/$nomeComponent.controller.php", "<?php\n\n// Controlador do componente $nomeComponent\n\nclass Cmp$nomeComponent {\n\n}");
echo "Controlador do componente $nomeComponent: " . colorizar("[OK]", 32) . "\n";
echo colorizar("Componente '$nomeComponent' criado e configurado com sucesso em ", 33) . colorizar("./app/components\n\n", 94);
