<?php
// Definindo diretório raiz
define('E', explode('core', __DIR__));
define('ROOT_DIR', E[0]);

$data = require(ROOT_DIR . 'config/DataBase.php');

$separadorLinha = "------------------------------------------------\n";

// Definindo códigos de cores
$cyan = '36';   // Código de cor para ciano
$yellow = '33'; // Código de cor para amarelo
$red = '31';    // Código de cor para vermelho
$green = '32';  // Código de cor para verde
$blue = '34';   // Código de cor para azul

// Função para adicionar cor ao texto
function colorizar($texto, $codigoCor)
{
    return "\033[{$codigoCor}m{$texto}\033[0m";
}

// Função para copiar um diretório e seu conteúdo
function copyDirectory($source, $destination)
{
    if (!is_dir($destination)) {
        mkdir($destination, 0777, true);
    }

    $dir = opendir($source);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($source . '/' . $file)) {
                copyDirectory($source . '/' . $file, $destination . '/' . $file);
            } else {
                copy($source . '/' . $file, $destination . '/' . $file);
            }
        }
    }
    closedir($dir);
    return true;
}

function execMulti($sql, $pdo)
{
    // Separando as instruções SQL por ponto e vírgula
    $queries = explode(';', $sql);

    // Executando cada instrução SQL individualmente
    foreach ($queries as $query) {
        // Ignorando instruções em branco ou de comentário
        $query = trim($query);
        if (!empty($query) && substr($query, 0, 2) != '/*') {
            try {
                // Executando a consulta
                $pdo->exec($query);
            } catch (PDOException $e) {
                // Se houver algum erro, imprime o erro
                echo " Erro ao executar consulta: " . colorizar($e->getMessage(), 31)."\n";
            }
        }
    }
}

// Função para exibir uma tabela no prompt
function exibirTabela($comando, $descricao)
{
    echo " " . str_pad($comando, 25) . "| " . str_pad($descricao, 60) . PHP_EOL;
}

$package = @$argv[1];

// Definindo os pacotes e seus respectivos nomes
$packages = [
    'userflow' => 'jandersongarcia/userflow'
];

if (!array_key_exists(strtolower($package), $packages)) {
    echo "\n$separadorLinha";
    echo colorizar(" Erro: ", $red) . "Nome do pacote desconhecido ou incompleto\n\n";
    exibirTabela('Nome do Pacote ', 'Comando');
    echo $separadorLinha;
    exibirTabela('Controle de acesso ', colorizar("mobControl", 33));
    echo "$separadorLinha\n";
    echo colorizar(" Exemplo de uso: ", $yellow) . " Composer " . colorizar("mob-package-install", $cyan) . " nome-do-pacote\n\n";
    exit;
}

$packageName = $packages[$package];

// Verifica se o diretório "vendor" existe
if (is_dir(ROOT_DIR . "/vendor/$packageName")) {
    echo "\n$separadorLinha";
    echo colorizar(" Erro: ", $red) . "O pacote $packageName já está instalado em sua aplicação. \n\n";
    exit;
}

// Executa o comando para instalar o pacote via Composer
$output = shell_exec("composer require $packageName");

if ($packageName == 'jandersongarcia/mobcontrol') {
    require_once('PackageMobControll.php');
    InstallMobControl($data);
}

echo colorizar(" Instalação de pacote concluída!\n",$yellow);