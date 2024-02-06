<?php

require_once('core\class\mobi.php');
use Mobi\Mobi;

$mob = new Mobi;

// Função para adicionar cor ao texto
function colorizar($texto, $codigoCor)
{
    if (is_numeric($codigoCor)) {
        // Se $codigoCor for um número, usá-lo diretamente
        return "\033[{$codigoCor}m{$texto}\033[0m";
    }

    $cores = [
        'red' => 31,
        'green' => 32,
        'yellow' => 33,
        'blue' => 34,
        'magenta' => 35,
        'cyan' => 36,
        'white' => 37,
    ];

    // Verificar se a cor fornecida é válida
    if (!isset($cores[$codigoCor])) {
        // Se não for válida, retornar o texto sem cor
        return $texto;
    }

    // Obter o código de cor correspondente
    $codigoCor = $cores[$codigoCor];

    // Retornar o texto com a cor ANSI apropriada
    return "\033[{$codigoCor}m{$texto}\033[0m";
}

$separadorLinha = "------------------------------------------------\n\n";

if (!file_exists("config/database.php")) {
    $mensagemErro = colorizar("Erro: ", 'red') . "Arquivo " . colorizar("database.php", 'cyan') . " não encontrado.\n É recomendado a " . colorizar("reinstalação", 'yellow') . " do " . colorizar("MobiPHP", 'yellow');
    echo "$separadorLinha $mensagemErro\n\n";
    exit();
}

$config = include "config/database.php";

if (trim($config['app_data_type']) == '') {
    $mensagemErro = colorizar("Erro: ", 'red') . "Arquivo " . colorizar("database.php", 'cyan') . " não foi configurado.\n Preencha as informações do banco de dados no arquivo " . colorizar("'/config/database.php'", 'cyan') . " antes de continuar";
    echo "$separadorLinha $mensagemErro\n\n";
    exit();
}

if ($config['app_data_type'] != 'mysql') {
    $mensagemErro = colorizar("Atenção: ", 'yellow') . "Esta versão do " . colorizar("MobiPHP", 'cyan') . " realiza a instalação do painel apenas para banco de dados " . colorizar("MySQL", 'cyan') . ".";
    echo "$separadorLinha $mensagemErro\n\n";
    exit();
}

// Verifique se o tipo de banco de dados é MySQL
if ($config['app_data_type'] === 'mysql') {
    // Obtenha as configurações do MySQL
    $mysqlConfig = $config['mysql'];

    // Tente estabelecer a conexão
    $conn = new mysqli(
        $mysqlConfig['host'],
        $mysqlConfig['username'],
        $mysqlConfig['password'],
        $mysqlConfig['database'],
        $mysqlConfig['port']
    );

    // Verifique se ocorreu algum erro durante a conexão
    if ($conn->connect_error) {
        $mensagemErro = colorizar("Erro: ", 'red') . "Falha na conexão com o MySQL: " . colorizar($conn->connect_error, 'yellow') . ".";
        echo "$separadorLinha $mensagemErro\n\n";
        exit();
    }

    echo $separadorLinha . colorizar(" Iniciando instalação.\n", 'yellow');
    echo " Teste de conexão com o MySQL " . colorizar("[OK]", 'green') . "\n";

    // Script SQL a ser executado
    $pass = $mob->createHash('Admin!123@');

    $scriptSql = "
    /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
    /*!40101 SET NAMES utf8 */;
    /*!50503 SET NAMES utf8mb4 */;
    /*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
    /*!40103 SET TIME_ZONE='+00:00' */;
    /*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
    /*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
    /*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
    
    -- Copiando estrutura para tabela mobidb.mb_user_account
    DROP TABLE IF EXISTS `mb_user_account`;
    CREATE TABLE IF NOT EXISTS `mb_user_account` (
      `SEQ_ID` int(11) NOT NULL AUTO_INCREMENT,
      `USERNAME` varchar(50) NOT NULL,
      `EMAIL` varchar(255) NOT NULL,
      `PASSWORD` varchar(255) NOT NULL,
      `RECOVER_PASS` varchar(255) DEFAULT NULL,
      `RECOVER_VALIDATE` timestamp NULL DEFAULT NULL,
      `CHANGE_PASSWORD` tinyint(1) NOT NULL DEFAULT 0,
      `TYPE_USER` int(11) NOT NULL DEFAULT 3,
      `AVATAR` varchar(255) DEFAULT NULL,
      `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (`SEQ_ID`) USING BTREE,
      KEY `FK_mb_user_account_mb_user_type` (`TYPE_USER`),
      CONSTRAINT `FK_mb_user_account_mb_user_type` FOREIGN KEY (`TYPE_USER`) REFERENCES `mb_user_type` (`SEQ_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    
    -- Copiando dados para a tabela mobidb.mb_user_account: ~1 rows (aproximadamente)
    INSERT INTO `mb_user_account` (`SEQ_ID`, `USERNAME`, `EMAIL`, `PASSWORD`, `RECOVER_PASS`, `RECOVER_VALIDATE`, `CHANGE_PASSWORD`, `TYPE_USER`, `AVATAR`, `CREATED_AT`) VALUES
        (1, 'Master Admin', 'admin@mobiphp.com', '$pass', NULL, NULL, 0, 1, NULL, '2024-01-28 01:27:08');
    
    -- Copiando estrutura para tabela mobidb.mb_user_logs
    DROP TABLE IF EXISTS `mb_user_logs`;
    CREATE TABLE IF NOT EXISTS `mb_user_logs` (
      `SEQ_ID` int(11) NOT NULL AUTO_INCREMENT,
      `USER_ID` int(11) DEFAULT NULL,
      `IP_ACCESS` varchar(45) DEFAULT NULL,
      `USER_AGENT` text DEFAULT NULL,
      `LEVEL` varchar(10) DEFAULT NULL,
      `ACTION` varchar(255) DEFAULT NULL,
      `LOG_TIME` timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (`SEQ_ID`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    
    -- Copiando dados para a tabela mobidb.mb_user_logs: ~0 rows (aproximadamente)
    
    -- Copiando estrutura para tabela mobidb.mb_user_type
    DROP TABLE IF EXISTS `mb_user_type`;
    CREATE TABLE IF NOT EXISTS `mb_user_type` (
      `SEQ_ID` int(11) NOT NULL AUTO_INCREMENT,
      `NAME` varchar(50) NOT NULL DEFAULT '0',
      `CATEGORY` varchar(50) NOT NULL DEFAULT '0',
      `SITUATION` tinyint(1) NOT NULL DEFAULT 0,
      PRIMARY KEY (`SEQ_ID`)
    ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    
    -- Copiando dados para a tabela mobidb.mb_user_type: ~4 rows (aproximadamente)
    INSERT INTO `mb_user_type` (`SEQ_ID`, `NAME`, `CATEGORY`, `SITUATION`) VALUES
        (1, 'Administrator', 'admin', 1),
        (2, 'User', 'user', 1),
        (3, 'Restricted User', 'read_only', 1),
        (4, 'Manager', 'manager', 1);
    
    /*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
    /*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
    /*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
    /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
    /*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
    ";

    // Execute o script SQL
    if ($conn->multi_query($scriptSql)) {

        // Aguarde a conclusão de todas as consultas
        while ($conn->more_results()) {
            $conn->next_result();
        }

        // Agora você pode executar uma nova consulta para verificar a tabela
        $result = $conn->query("SHOW TABLES LIKE 'mb_user_account'");

        // Verificar se a consulta foi bem-sucedida
        if ($result !== false) {
            // Verificar o número de linhas retornadas
            if ($result->num_rows > 0) {
                echo " Instalação da tabela " . colorizar("mb_user_account", 'cyan') . " no banco de dados. " . colorizar("[OK]", 'green') . "\n";
            } else {
                $mensagemErro = colorizar("Erro: ", 'red') . "A tabela 'mb_user_account' não foi encontrada após a execução do script.";
                echo "$separadorLinha $mensagemErro\n\n";
                exit();
            }
        } else {
            // Exibir detalhes do erro
            $mensagemErro = colorizar("Erro: ", 'red') . "Erro na consulta: " . colorizar($conn->error, 'yellow');
            echo "$separadorLinha $mensagemErro\n\n";
            exit();
        }



    } else {
        $mensagemErro = colorizar("Erro: ", 'red') . "Falha na criação do script.";
        echo "$separadorLinha $mensagemErro\n\n";
        exit();
    }

    // Não se esqueça de fechar a conexão quando não for mais necessária
    $conn->close();

    echo "\n";
} else {
    $mensagemErro = colorizar("Erro: ", 'red') . "A configuração especifica de outro tipo de banco de dados.";
    echo "$separadorLinha $mensagemErro\n\n";
    exit();
}