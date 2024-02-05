<?php

namespace Sql;

use PDO;
use PDOException;
use Mobi\Root;

class MySQL extends Root
{

    // Propriedades para armazenar detalhes da conexão e instância do PDO.
    private $host;
    private $username;
    private $password;
    private $database;
    private $port;
    private $driver;

    private $pdo;

    // Construtor da classe, que configura e inicia a conexão automaticamente.
    function __construct()
    {
        $this->configureConnection(CONN); // Supõe-se que a constante CONN seja definida em algum lugar.
        $this->connect();
    }

    // Método privado para configurar detalhes da conexão com o banco de dados.
    private function configureConnection(array $connectionDetails)
    {
        $this->driver = $connectionDetails['driver'];
        $this->host = $connectionDetails['host'];
        $this->username = $connectionDetails['username'];
        $this->password = $connectionDetails['password'];
        $this->database = $connectionDetails['database'];
        $this->port = $connectionDetails['port'];
    }

    // Método privado para estabelecer a conexão com o banco de dados usando PDO.
    private function connect()
    {
        try {
            $this->pdo = new PDO(
                $this->driver . ':host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->database,
                $this->username,
                $this->password
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->handleConnectionError($e);
        }
    }

    // Método privado para lidar com erros de conexão.
    private function handleConnectionError(PDOException $e)
    {
        $error = "Não foi possível conectar ao banco de dados <strong>{$this->driver}</strong>.<br><strong>Erro: </strong><span>{$e->getMessage()}</span>";
        require_once("./core/php/error.php"); // Supõe-se que o arquivo error.php esteja no caminho especificado.
        exit();
    }

    /**
     * Executa uma consulta SQL e retorna os resultados em JSON.
     *
     * @param string $sql A consulta SQL a ser executada.
     * @param array $params Parâmetros para a consulta (opcional).
     * @return string|false Os resultados da consulta em JSON ou false em caso de erro.
     */
    public function query($sql, $params = [])
    {
        try {
            // Prepara a consulta SQL e a executa com os parâmetros fornecidos.
            $statement = $this->pdo->prepare($sql);
            $statement->execute($params);
            // Retorna os resultados da consulta como um array associativo convertido para JSON.
            return json_encode($statement->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            // Se ocorrer um erro durante a consulta, pode-se lidar com ele aqui.
            // Exemplo: logar o erro, lançar exceção personalizada, etc.
            return false;
        }
    }

    // Método para inserir dados no banco de dados.
    /**
     * Insere dados em uma tabela e retorna um JSON indicando sucesso ou falha.
     *
     * @param string $table O nome da tabela.
     * @param array $data Os dados a serem inseridos na tabela.
     * @return string JSON indicando sucesso ou falha na inserção.
     */
    public function insert($table, $data)
    {
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO $table ($columns) VALUES ($values)";

        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute(array_values($data));

            // Obter o ID da última inserção
            $lastInsertId = $this->pdo->lastInsertId();

            return json_encode(['success' => true, 'id' => $lastInsertId]);
        } catch (PDOException $e) {
            // Lidar com erros de inserção
            return json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }


    // Método para obter todos os registros de uma tabela.
    /**
     * Obtém todos os registros de uma tabela e retorna um JSON.
     *
     * @param string $table O nome da tabela.
     * @return string JSON contendo todos os registros da tabela ou false em caso de erro.
     */
    public function getAll($table)
    {
        $sql = "SELECT * FROM $table";

        try {
            $statement = $this->pdo->query($sql);
            return json_encode($statement->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            // Lidar com erros de consulta
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Obtém um registro por um campo específico de uma tabela e retorna um JSON.
     *
     * @param string $table O nome da tabela.
     * @param string $field O nome do campo a ser verificado.
     * @param mixed $value O valor do campo a ser comparado.
     * @return string JSON contendo o registro ou false em caso de erro.
     */
    public function getByField($table, $field, $value)
    {
        $sql = "SELECT * FROM $table WHERE $field = ?";

        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute([$value]);

            // Retorna um array associativo com todos os dados do registro
            return json_encode($statement->fetch(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            // Lidar com erros de consulta
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    // Método para obter um registro por ID de uma tabela.
    /**
     * Obtém um registro por ID de uma tabela e retorna um JSON.
     *
     * @param string $table O nome da tabela.
     * @param int $id O ID do registro a ser obtido.
     * @return string JSON contendo o registro ou false em caso de erro.
     */
    public function getById($table, $tupla, $id)
    {
        $sql = "SELECT * FROM $table WHERE $tupla = ?";

        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute([$id]);
            return json_encode($statement->fetch(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            // Lidar com erros de consulta
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    // Método para atualizar um registro em uma tabela.
    /**
     * Atualiza um registro em uma tabela e retorna um JSON indicando sucesso ou falha.
     *
     * @param string $table O nome da tabela.
     * @param array $data Os novos dados a serem atualizados na tabela.
     * @param int $id O ID do registro a ser atualizado.
     * @return string JSON indicando sucesso ou falha na atualização.
     */
    public function update($table, $data, $id)
    {
        $setClause = implode('=?, ', array_keys($data)) . '=?';
        $sql = "UPDATE $table SET $setClause WHERE SEQ_ID = ?";

        try {
            $values = array_merge(array_values($data), [$id]);
            $statement = $this->pdo->prepare($sql);
            $statement->execute($values);
            return json_encode(['success' => true]);
        } catch (PDOException $e) {
            // Lidar com erros de atualização
            return json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // Método para excluir um registro de uma tabela.
    /**
     * Exclui um registro de uma tabela e retorna um JSON indicando sucesso ou falha.
     *
     * @param string $table O nome da tabela.
     * @param int $id O ID do registro a ser excluído.
     * @return string JSON indicando sucesso ou falha na exclusão.
     */
    public function delete($table, $id)
    {
        $sql = "DELETE FROM $table WHERE id = ?";

        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute([$id]);
            return json_encode(['success' => true]);
        } catch (PDOException $e) {
            // Lidar com erros de exclusão
            return json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
