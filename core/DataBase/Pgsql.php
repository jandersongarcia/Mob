<?php

namespace Sql;

use PDO;
use PDOException;

class PostgreSQL {

    private $host = '';
    private $username = '';
    private $password = '';
    private $database = '';
    private $port = '';
    private $driver = '';

    public function __construct() {
        $this->testConnect(CONN);
    }

    private function testConnect(array $connectionDetails) {
        $this->driver = $connectionDetails['driver'];
        $this->host = $connectionDetails['host'];
        $this->username = $connectionDetails['username'];
        $this->password = $connectionDetails['password'];
        $this->database = $connectionDetails['database'];
        $this->port = $connectionDetails['port'];

        try {
            $pdo = new PDO(
                $this->driver . ':host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->database,
                $this->username,
                $this->password
            );
            
            // Configurar para lançar exceções em caso de erro
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            echo 'Conexão bem-sucedida!';
        } catch (PDOException $e) {
            $error = "Não foi possível conectar ao banco de dados <strong>{$this->driver}</strong>.<br><strong>Erro: </strong><span>{$e->getMessage()}</span>";

            // Exibindo uma página de erro ou redirecionando para uma página de erro
            require_once("./core/php/error.php");

            exit();
        }
        print_r($connectionDetails);
    }
}
