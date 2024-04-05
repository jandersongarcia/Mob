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
    'access-control' => 'jandersongarcia/mobcontrol'
];

echo $separadorLinha;

if (!array_key_exists($package, $packages)) {
    echo "\n$separadorLinha";
    echo colorizar(" Erro: ", $red) . "Nome do pacote desconhecido ou incompleto\n\n";
    echo exibirTabela('Nome do Pacote ', 'Comando');
    echo $separadorLinha;
    echo exibirTabela('Controle de acesso ', colorizar("access-control", 33));
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

    $directory = ROOT_DIR . "/packages/Mobcontrol/";

    if (is_dir($directory)) {
        // Função para excluir recursivamente um diretório e seu conteúdo
        function deleteDirectory($dir)
        {
            if (!file_exists($dir))
                return true;
            if (!is_dir($dir))
                return unlink($dir);
            foreach (scandir($dir) as $item) {
                if ($item == '.' || $item == '..')
                    continue;
                if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item))
                    return false;
            }
            return rmdir($dir);
        }

        // Chamada da função para excluir o diretório e seu conteúdo
        if (deleteDirectory($directory)) {
            echo ' Removendo pacote do diretório ' . colorizar("packages/", $cyan) . ' ' . colorizar("[OK]", $green) . "\n";
        } else {
            echo ' Removendo pacote do diretório ' . colorizar("packages/", $cyan) . ' ' . colorizar("[ERROR]", $red) . "\n";
        }
    } else {
        echo colorizar(" Erro: ", $red) . "Diretório do pacote não localizado\n";
    }

    $baseDir = ROOT_DIR . 'vendor\jandersongarcia\mobcontrol\src\email';

    // Verifica se o diretório existe
    if (is_dir($baseDir)) {
        // Obtém a lista de arquivos e diretórios no diretório
        $arquivos = scandir($baseDir);

        // Remove os arquivos de e-mail
        $arquivos = array_diff($arquivos, array('..', '.'));

        // Exibe os arquivos
        foreach ($arquivos as $arquivo) {
            $emailFile = ROOT_DIR . "templates\Email\/$arquivo";
            if (file_exists($emailFile)) {
                if (unlink($emailFile)) {
                    echo ' Excluindo template de e-mail ' . colorizar("$arquivo", $cyan) . ' ' . colorizar("[OK]", $green) . "\n";
                } else {
                    echo ' Excluindo template de e-mail ' . colorizar("$arquivo", $cyan) . ' ' . colorizar("[ERROR]", $red) . "\n";
                }
            }
        }
    } else {
        echo colorizar(" Erro: ", $red) . "Diretório de templates não localizado.\n";
        echo $baseDir;
    }
}

echo "\n Removendo pacote $packageName\n";

$output = shell_exec("composer remove $packageName");