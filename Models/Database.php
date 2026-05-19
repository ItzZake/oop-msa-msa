<?php

class Database
{
    private static $instance;
    private $connection;
    private $host = 'localhost';
    private $dbname = 'wellucation';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';

    private function __construct()
    {
        $this->connect();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function query($sql, $params = [])
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        $stmt = $this->connection->prepare($sql);
        if ($stmt === false) {
            return false;
        }

        if (!$stmt->execute($params)) {
            return false;
        }

        return $stmt;
    }

    public function connect()
    {
        if ($this->isConnected()) {
            return;
        }

        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $exception) {
            throw new RuntimeException('Database connection failed: ' . $exception->getMessage());
        }
    }

    public function disconnect()
    {
        $this->connection = null;
    }

    public function isConnected()
    {
        return $this->connection instanceof PDO;
    }

    public function fetchAll($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        if ($stmt === false) {
            return [];
        }

        return $stmt->fetchAll();
    }

    public function fetchOne($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        if ($stmt === false) {
            return null;
        }

        return $stmt->fetch();
    }

    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }
}
