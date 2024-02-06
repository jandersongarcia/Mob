<?php

// Função para adicionar cor ao texto
function colorizar($texto, $codigoCor)
{
    return "\033[{$codigoCor}m{$texto}\033[0m";
}

$separadorLinha = "------------------------------------------------\n";

// Verificar se o argumento foi informado
if (empty($argv[1])) {
    $mensagemAtencao = colorizar("Atenção: ", 31) . "Forneça o nome do Módulo\n";
    $uso = "Uso correto: composer mobi-create-module " . colorizar("nome-do-modulo", 36) . "\n\n";
    echo "$separadorLinha $mensagemAtencao $uso)";
    exit();
}

// Obter o nome do componente a partir dos argumentos da linha de comando
$nomeComponent = ucfirst(strtolower(preg_replace('/[^a-zA-Z0-9-_]/', '', $argv[1])));

// Verificar se $nomeComponent está vazio após o tratamento
if (empty($nomeComponent)) {
    $mensagemErro = colorizar("Erro: ", 31) . "Nome do módulo inválido\n";
    echo "$separadorLinha $mensagemErro";
    exit();
}

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
