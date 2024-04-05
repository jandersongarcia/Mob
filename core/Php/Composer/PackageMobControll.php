<?php

function Security($value = true)
{

    // Caminho do arquivo
    $filePath = ROOT_DIR . 'config/Startup.php';

    // Carregar o conteúdo do arquivo em uma string
    $fileContents = file_get_contents($filePath);

    // Verifica se o arquivo foi lido corretamente
    if ($fileContents === false) {
        die('Erro ao ler o arquivo');
    }

    if (strpos($fileContents, 'mobControl()') === false) {
        $modifiedContents = "$fileContents\n// Ativar segurança de sessions\n".'function mobControl(){ $security = true; if($security){$uri=$_SERVER["REQUEST_URI"];$keyword="accounts";if(strpos($uri,$keyword)!==false&&isset($_SESSION["STATE_USER"])){unset($_SESSION["STATE_USER"]);}if(empty($_SESSION["STATE_USER"])){if(strpos($uri,"/$keyword/")!==0){if(substr($uri,-1)!=="/"){header("Location: ".$uri."/");exit;}else{header("Location: /$keyword/");exit;}}}}} mobControl();';
    } else {
        $modifiedContents = str_replace('$security = true;', '$security = false;', $fileContents);
    }

    // Substituir $security = true; por $security = false;
    // if ($value === true) {
    //     $modifiedContents = str_replace('$security = false;', '$security = true;', $fileContents);
    // } else {
    //     $modifiedContents = str_replace('$security = true;', '$security = false;', $fileContents);
    // }

    echo " Ajustando arquivo de inicialização:";

    // Salvar a string modificada de volta no arquivo
    $result = file_put_contents($filePath, $modifiedContents);

    // Verifica se a escrita foi bem-sucedida
    if ($result === false) {
        echo colorizar("  [ERROR]\n", 31);
    } else {
        echo colorizar("  [OK]\n", 32);
    }

}

function Clean($page)
{

    // Caminho do arquivo
    $filePath = ROOT_DIR . "app/Pages/$page/$page" . "View.php";

    // Carregar o conteúdo do arquivo em uma string
    $fileContents = file_get_contents($filePath);

    // Limpa a página
    $modifiedContents = "<?php\n\n// Página sem conteúdo";

    // Salvar a string modificada de volta no arquivo
    file_put_contents($filePath, $modifiedContents);

    EditController($page);

}

function trataUrlPack($value)
{

    //accounts-recovery-error
    $originalString = str_replace('accounts-', '', $value);
    $camelCaseString = preg_replace_callback('/-(.)/', function ($matches) {
        return strtoupper($matches[1]);
    }, $originalString);

    return $camelCaseString;

}

function EditController($page)
{

    $ctrl = trataUrlPack($page);

    // Caminho do arquivo
    $filePath = ROOT_DIR . "app/Pages/$page/$page" . "Controller.php";

    // Carregar o conteúdo do arquivo em uma string
    $fileContents = file_get_contents($filePath);

    if (strpos($fileContents, 'jandersongarcia\mobcontrol\Dash') === false) {
        // Limpa a página
        $modifiedContents = str_replace("use", "use jandersongarcia\mobcontrol\Dash;\nuse", $fileContents);
        $modifiedContents .= "\n\n" . '$dash = new Dash;';
        $modifiedContents .= "\n\n" . '$dash->controller(' . "'$ctrl'" . ');';
        // Salvar a string modificada de volta no arquivo
        file_put_contents($filePath, $modifiedContents);
    }
}

function InstallMobControl($data)
{
    $sourceDir = ROOT_DIR . '/vendor/jandersongarcia/mobcontrol/src/mobcontrol';
    $destinationDir = ROOT_DIR . '/packages/Mobcontrol';

    $separadorLinha = "------------------------------------------------\n";

    // Definindo códigos de cores
    $cyan = '36';   // Código de cor para ciano
    $yellow = '33'; // Código de cor para amarelo
    $red = '31';    // Código de cor para vermelho
    $green = '32';  // Código de cor para verde
    $blue = '34';   // Código de cor para azul

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
                // $code = file_get_contents(ROOT_DIR . 'vendor/jandersongarcia/mobcontrol/src/mobcontrol.sql');
                $code = "SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0; DROP TABLE IF EXISTS `mb_user_logs`; CREATE TABLE IF NOT EXISTS `mb_user_logs` (`SEQ_ID` int(11) NOT NULL AUTO_INCREMENT, `USER_ID` int(11) DEFAULT NULL, `IP_ACCESS` varchar(45) DEFAULT NULL, `USER_AGENT` text DEFAULT NULL, `LEVEL` varchar(10) DEFAULT NULL, `ACTION` varchar(255) DEFAULT NULL, `LOG_TIME` timestamp NOT NULL DEFAULT current_timestamp(), PRIMARY KEY (`SEQ_ID`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; DROP TABLE IF EXISTS `mb_user_type`; CREATE TABLE IF NOT EXISTS `mb_user_type` (`SEQ_ID` int(11) NOT NULL AUTO_INCREMENT, `NAME` varchar(50) NOT NULL DEFAULT '0', `CATEGORY` varchar(50) NOT NULL DEFAULT '0', `SITUATION` tinyint(1) NOT NULL DEFAULT 0, PRIMARY KEY (`SEQ_ID`)) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; INSERT INTO `mb_user_type` (`SEQ_ID`, `NAME`, `CATEGORY`, `SITUATION`) VALUES (1, 'Administrator', 'admin', 1), (2, 'User', 'user', 1), (3, 'Restricted User', 'read_only', 1), (4, 'Manager', 'manager', 1) ON DUPLICATE KEY UPDATE `SEQ_ID`=VALUES(`SEQ_ID`), `NAME`=VALUES(`NAME`), `CATEGORY`=VALUES(`CATEGORY`), `SITUATION`=VALUES(`SITUATION`); SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;";
                echo execMulti($code, $pdo);

            } catch (PDOException $e) {
                die("Erro de conexão com o MySQL: " . $e->getMessage() . "\n");
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
                echo " Copiando arquivos do pacote: " . colorizar(" [OK] ", $green) . "\n";
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
        echo " Copiando arquivos de e-mail: " . colorizar(" [OK] ", $green) . "\n";
    } else {
        echo colorizar(" Erro: ", $red) . "O diretório src/email não existe no pacote.\n";
    }

    echo "> Criando páginas " . colorizar("'accounts'\n", $yellow);

    // Criando as páginas
    shell_exec("composer mob-create-page accounts-login accounts");
    shell_exec("composer mob-create-page accounts-new-account accounts/new-account");
    shell_exec("composer mob-create-page accounts-forgot-pass accounts/forgot-pass");
    shell_exec("composer mob-create-page accounts-new-pass accounts/newpass");
    shell_exec("composer mob-create-page accounts-recovery-error accounts/error");

    echo "> Reescrevendo páginas " . colorizar("'accounts'\n", $yellow);

    clean('accounts-login');
    clean('accounts-new-account');
    clean('accounts-forgot-pass');
    clean('accounts-new-pass');
    clean('accounts-recovery-error');

    Security(true);
}

function RemoveMobControl()
{
    $directory = ROOT_DIR . "/packages/Mobcontrol/";

    $separadorLinha = "------------------------------------------------\n";

    // Definindo códigos de cores
    $cyan = '36';   // Código de cor para ciano
    $yellow = '33'; // Código de cor para amarelo
    $red = '31';    // Código de cor para vermelho
    $green = '32';  // Código de cor para verde
    $blue = '34';   // Código de cor para azul

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

    echo "> Excluindo páginas " . colorizar("'accounts'\n", $yellow);

    // Criando as páginas
    shell_exec("composer mob-remove-page accounts-login");
    shell_exec("composer mob-remove-page accounts-new-account");
    shell_exec("composer mob-remove-page accounts-forgot-pass");
    shell_exec("composer mob-remove-page accounts-new-pass");
    shell_exec("composer mob-remove-page accounts-recovery-error");

    Security(false);
}