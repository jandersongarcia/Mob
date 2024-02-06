<?php

// Função para adicionar cor ao texto
function colorizar($texto, $codigoCor)
{
    return "\033[{$codigoCor}m{$texto}\033[0m";
}

$separadorLinha = "\n------------------------------------------------\n";

$theme = [
    'panel-admin' => 'Painel Administrativo'
];

// Verificar se o número correto de argumentos foi fornecido
if ($argc !== 3 || empty($argv[1]) || empty($argv[2])) {
    $mensagemAtencao = colorizar("Atenção: ", 31) . "Escreva o nome do tema que será usado e do componente onde será instalado.\n";
    $uso = "Uso correto: composer mobi-theme-install " . colorizar("nome-do-tema", 36) . " " . colorizar("nome-do-componente", 36) . "\n\n " . colorizar("Lista de Temas", 36) . "\n";

    foreach ($theme as $nome => $descricao) {
        $uso .= " - $descricao: " . colorizar($nome, 36) . "\n";
    }

    echo "$separadorLinha $mensagemAtencao $uso";
    exit();
}

$nomeThema = strtolower(trim($argv[1]));
$nomeComponent = ucfirst(strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/', '', str_replace('-', '_', $argv[2])))));

if (empty($theme[$nomeThema])) {

    $mensagemAtencao = colorizar("Atenção: ", 31) . "O thema informado não existe.\n";
    $uso = "Informe um dos temas da lista baixo:\n\n";
    foreach ($theme as $nome => $descricao) {
        $uso .= " - $descricao: " . colorizar($nome, 36) . "\n";
    }
    echo "$separadorLinha $mensagemAtencao $uso";
    exit();

}

// Verificar se já existe um componente com este nome
if (file_exists("app/components/$nomeComponent")) {
    $mensagem = colorizar("Atenção: ", 31) . "O componente " . colorizar("'$nomeComponent'", 36) . " já existe em app/components\n Operação cancelada.\n\n";
    echo "$separadorLinha $mensagem";
    exit();
}

echo "$separadorLinha Iniciando o processo de instalação...\n";

$localTheme = "core/php/theme/$nomeThema.php";

if (!file_exists($localTheme)) {
    $mensagem = colorizar("Atenção: ", 31) . "O tema " . colorizar("'$nomeThema'", 36) . " não foi encontrado no core.\n Operação cancelada.\n\n";
    echo "$separadorLinha $mensagem\n";
    exit();
}

// verifica e o thema existe
echo " Carregando o pacote do tema $nomeThema: " . colorizar("[OK]", 32) . "\n";
require_once($localTheme);

if (!isset($themeStyle['css']) || !isset($themeStyle['js']) || !isset($themeStyle['html'])) {
    $mensagem = colorizar("Atenção: ", 31) . "Foi encontrado um erro na estrutura do tema " . colorizar("'$nomeThema'", 36) . " no core.\n Operação cancelada.\n\n";
    echo "$separadorLinha $mensagem\n";
    exit();
}

// Tentar criar o diretório da página
if (mkdir("app/components/$nomeComponent", 0777, true)) {
    echo "Criação do diretório $nomeComponent: " . colorizar("[OK]", 32) . "\n";
} else {
    echo "Erro ao tentar criar o diretório " . colorizar("'$nomeComponent'", 36) . "\n Operação cancelada.\n\n";
    exit();
}

/// Criar arquivos dentro da pasta recém-criada
// Página View
file_put_contents("app/components/$nomeComponent/$nomeComponent.view.php", $themeStyle['html']);
echo "Página View do componente $nomeComponent: " . colorizar("[OK]", 32) . "\n";
// Página Controller
file_put_contents("app/components/$nomeComponent/$nomeComponent.controller.php", "<?php\n\n// Controlador do componente $nomeComponent\n\nclass $nomeComponent {\n\n}");
echo "Controlador do componente $nomeComponent: " . colorizar("[OK]", 32) . "\n";
// Folha de estilos CSS
file_put_contents("app/components/$nomeComponent/$nomeComponent.css", $themeStyle['css']);
echo "Arquivo CSS para $nomeComponent: " . colorizar("[OK]", 32) . "\n";
// JS
file_put_contents("app/components/$nomeComponent/$nomeComponent.js", $themeStyle['js']);
echo "Arquivo JavaScript para $nomeComponent: " . colorizar("[OK]", 32) . "\n";

echo "O tema '$nomeThema' foi instalado com sucesso no componente '$nomeComponent'\n";