<?php

// Definindo diretório raiz
define('E', explode('core', __DIR__));
define('ROOT_DIR', E[0]);

// Função para adicionar cor ao texto
function colorizar($texto, $codigoCor)
{
    return "\033[{$codigoCor}m{$texto}\033[0m";
}

$separadorLinha = "------------------------------------------------\n";

// Definindo códigos de cores
$cyan = '36';   // Código de cor para ciano
$yellow = '33'; // Código de cor para amarelo
$red = '31';    // Código de cor para vermelho
$green = '32';  // Código de cor para verde
$blue = '34';   // Código de cor para azul

// Função para exibir uma tabela no prompt
function exibirTabela($comando, $descricao)
{
    echo " " . str_pad($comando, 25) . "| " . str_pad($descricao, 60) . PHP_EOL;
}

$package = @$argv[1];

// Definindo os pacotes e seus respectivos nomes
$packages = [
    'mobcontrol' => 'jandersongarcia/mobcontrol'
];

echo $separadorLinha;

if (!array_key_exists(strtolower($package), $packages)) {
    echo "\n$separadorLinha";
    echo colorizar(" Erro: ", $red) . "Nome do pacote desconhecido ou incompleto\n\n";
    echo exibirTabela('Nome do Pacote ', 'Comando');
    echo $separadorLinha;
    echo exibirTabela('Controle de acesso ', colorizar("mobcontrol", 33));
    echo "$separadorLinha\n";
    echo colorizar(" Exemplo de uso: ", $yellow) . " Composer " . colorizar("mob-package-install", $cyan) . " nome-do-pacote\n\n";
    exit;
}

$packageName = $packages[$package];

// Verifica se o diretório "vendor" existe
if (!is_dir(ROOT_DIR . "/vendor/$packageName")) {
    echo "\n$separadorLinha";
    echo colorizar(" Erro: ", $red) . "O pacote $packageName não está instalado em sua aplicação. \n\n";
    exit;
}

if ($packageName == 'jandersongarcia/mobcontrol') {

    require_once('PackageMobControll.php');
    removeMobControl();

}

echo " Removendo pacote $packageName\n";

$output = shell_exec("composer remove $packageName");

echo colorizar(" Exclusão de pacote concluída!\n",$yellow);