<?php

declare(strict_types=1);

namespace App\Classes;

class DBBox extends AbstractBox
{
    private static ?DBBox $instance = null;

    private function __construct(
        private \PDO $pdo,
        private string $tableName

    ) {
        $this->load();
    }

    public static function getInstance(
        \PDO $pdo,
        string $tableName,
    ): DBBox {
        if (!self::$instance) {
            self::$instance = new self($pdo, $tableName);
        }

        return self::$instance;
    }

    public function __clone()
    {
        throw new \RuntimeException('Cloning the DBBox singleton instance is not allowed.');
    }

    public function setData(string $key, int|float|string|bool|array $value): void
    {
        if (array_key_exists($key, $this->data)) {
            $this->data[$key] = $value;
        } else {
            parent::setData($key, $value);
        }
    }

    public function save(): void
    {
        try {
            $this->pdo->beginTransaction();

            $this->createTableIfNotExists();
            $this->truncateTable();
            $this->insertData();

            $this->pdo->commit();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            throw new \Exception("Error while saving data: " . $e->getMessage());
        }
    }

    public function load(): void
    {
        try {
            $query = "SELECT `key`, `value` FROM $this->tableName";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            $this->data = [];

            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $this->data[$row['key']] = unserialize($row['value']);
            }
        } catch (\PDOException $e) {
            throw new \Exception("Error while loading data: " . $e->getMessage());
        }
    }

    private function createTableIfNotExists(): void
    {
        $query = "CREATE TABLE IF NOT EXISTS $this->tableName (
            `key` VARCHAR(255) PRIMARY KEY,
            `value` TEXT
        )";

        $this->pdo->exec($query);
    }

    private function truncateTable(): void
    {
        $query = "DELETE FROM $this->tableName";

        $this->pdo->exec($query);
    }

    private function insertData(): void
    {
        $query = "INSERT INTO $this->tableName (`key`, `value`) VALUES (:key, :value)";
        $stmt = $this->pdo->prepare($query);

        foreach ($this->data as $key => $value) {
            $stmt->bindValue(':key', $key, \PDO::PARAM_STR);
            $stmt->bindValue(':value', serialize($value), \PDO::PARAM_STR);
            $stmt->execute();
        }
    }
}
