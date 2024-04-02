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
    'access-control' => 'jandersongarcia/mobcontrol'
];

if (!array_key_exists($package, $packages)) {
    echo "\n$separadorLinha";
    echo colorizar(" Erro: ", $red) . "Nome do pacote desconhecido ou incompleto\n\n";
    exibirTabela('Nome do Pacote ', 'Comando');
    echo $separadorLinha;
    exibirTabela('Controle de acesso ', colorizar("access-control", 33));
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
    $sourceDir = ROOT_DIR . '/vendor/jandersongarcia/mobcontrol/src/mobcontrol';
    $destinationDir = ROOT_DIR . '/packages/Mobcontrol';

    //Banco de Dados
    if ($data['app_data_type'] == '') {
        echo "\n$separadorLinha";
        echo colorizar(" ATENÇÃO: ", $yellow) . "Configure um banco de dados antes de iniciar.\n";
        exit;
    } elseif (isset($data['app_data_type'])) {

        $type = $data['app_data_type'];
        $config = $data[$type];

        if ($type == 'mysql') {
            try {
                $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']};charset={$config['charset']};port={$config['port']}";
                $pdo = new PDO($dsn, $config['username'], $config['password']);
                echo " Conexão com o MySQL realizada com sucesso!\n";

                // Testa a conexão com o banco de dados
                $code = file_get_contents(ROOT_DIR . 'vendor/jandersongarcia/mobcontrol/src/mobcontrol.sql');
                echo execMulti($code, $pdo);

            } catch (PDOException $e) {
                die("Erro de conexão com o MySQL: " . $e->getMessage() ."\n");
            }
        }

        if ($type == 'pgsql') {
            try {
                $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']};user={$config['username']};password={$config['password']};port={$config['port']}";
                $pdo = new PDO($dsn);
                $pdo->exec("set names utf8");
                echo " Conexão com o PostgreSQL realizada com sucesso!\n";
            } catch (PDOException $e) {
                die("Erro de conexão com o PostgreSQL: " . $e->getMessage());
            }
        }

    } else {
        echo colorizar(" ATENÇÃO: ", $yellow) . "Tipo de conexão com o banco de dados desconhecido.\n";
        exit;
    }

    // Verifica se o diretório de destino já existe
    if (!is_dir($destinationDir)) {
        // Verifica se a pasta vendor/jandersongarcia/mobcontrol existe
        if (is_dir($sourceDir)) {
            // Copia a pasta para packages/Mobcontrol
            if (!copyDirectory($sourceDir, $destinationDir)) {
                echo colorizar(" Erro: ", $red) . "Não foi possível copiar a pasta Mobcontrol para o diretório packages.\n";
                exit;
            } else {
                echo " Copiando arquivos do pacote: " . colorizar(" [OK] ", $green)."\n";
            }
        } else {
            echo colorizar(" Erro: ", $red) . "A pasta Mobcontrol não existe no diretório vendor.\n";
            exit;
        }
    } else {
        echo colorizar(" Erro: ", $red) . "A pasta Mobcontrol já existe no diretório packages.\n";
        exit;
    }

    $sourceDir = ROOT_DIR . 'vendor/jandersongarcia/mobcontrol/src/email';
    $destinationDir = ROOT_DIR . '/templates/Email';

    // Copia arquivos de src/email para templates/Email
    if (is_dir($sourceDir)) {
        $files = scandir($sourceDir);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $sourceFile = $sourceDir . '/' . $file;
                $destinationFile = $destinationDir . '/' . $file;
                if (!file_exists($destinationFile)) {
                    if (!copy($sourceFile, $destinationFile)) {
                        echo colorizar(" Erro: ", $red) . "Não foi possível copiar o arquivo $file para o diretório templates/Email.\n";
                    }
                }
            }
        }
        echo " Copiando arquivos de e-mail: " . colorizar(" [OK] ", $green) ."\n";
    } else {
        echo colorizar(" Erro: ", $red) . "O diretório src/email não existe no pacote.\n";
    }
}
