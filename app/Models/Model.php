<?php

namespace App\Models;

use PDO;
use PDOStatement;

abstract class Model
{
    protected PDO $conn;
    protected string $tableName;
    protected string $primaryKey;
    protected string $sql;
    protected array $values;
    protected array $types;

    public function __construct()
    {
        $host = $_ENV["DB_HOST"];
        $port = $_ENV["DB_PORT"];
        $username = $_ENV["DB_USERNAME"];
        $password = $_ENV["DB_PASSWORD"];
        $dbName = $_ENV["DB_NAME"];
        $this->conn = new PDO("mysql:host=$host;port=$port;dbname=$dbName", $username, $password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->tableName = $this->setTableName();
        $this->primaryKey = $this->setPrimaryKey();
    }

    abstract protected function setTableName(): string;

    abstract protected function setPrimaryKey(): string;

    final protected function setSql(string $sql): Model
    {
        $this->sql = $sql;

        return $this;
    }

    final protected function setValues(array $values): Model
    {
        $this->values = $values;

        return $this;
    }

    final protected function setTypes(array $types): Model
    {
        $this->types = $types;

        return $this;
    }

    final protected function execute(): PDOStatement
    {
        $stmt = $this->conn->prepare($this->sql);
        $stmt->execute();
        $this->clear();

        return $stmt;
    }

    final protected function prepareExecute(): PDOStatement
    {
        $stmt = $this->conn->prepare($this->sql);

        foreach ($this->values as $index => $value) {
            $stmt->bindValue($index + 1, $value, $this->types[$index]);
        }

        $stmt->execute();
        $this->clear();

        return $stmt;
    }

    final protected function beginTransaction(): void
    {
        $this->conn->beginTransaction();
    }

    final protected function commit(): void
    {
        $this->conn->commit();
    }

    final protected function rollback(): void
    {
        $this->conn->rollBack();
    }

    private function clear(): void
    {
        unset($this->sql);
        unset($this->values);
    }

    final public function all(): array
    {
        $table = $this->tableName;
        $sql = "SELECT * FROM $table";

        return $this->setSql($sql)->execute()->fetchAll();
    }

    final public function first(): array
    {
        $table = $this->tableName;
        $sql = "SELECT * FROM $table";

        return $this->setSql($sql)->execute()->fetch();
    }

    final public function find(int $var): array
    {
        $table = $this->tableName;
        $primaryKey = $this->primaryKey;
        $sql = "SELECT * FROM $table WHERE $primaryKey = ?";

        return $this->setSql($sql)
            ->setTypes([PDO::PARAM_INT])
            ->setValues([$var])
            ->prepareExecute()
            ->fetch();
    }
}
