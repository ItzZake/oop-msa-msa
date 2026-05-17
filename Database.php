<?php

class Database
{
    private static $instance;
    private $connection;
    private $host = 'localhost';
    private $dbname = 'Wellucation';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';


    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->connection->prepare($sql);
        if ($stmt) {
            $stmt->execute($params);
            return $stmt;
        }
        // Code to execute a query with parameters
    }
    public function Connect()
    {
            $dsn = "mysql:host=$this->host;dbname=$this->dbname;charset=$this->charset";
            try {
                $this->connection = new PDO($dsn, $this->username, $this->password);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        // Code to establish database connection
    }
    public function Disconnect()
    {
            $this->connection = null;
            self::$instance = null;
        // Code to close database connection
    }
    public function IsConnected()
    {
            return $this->connection != null;
        // Code to check if database connection is active
    }
    public function fetchAll($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        // Code to fetch all results from a query
    }

    public function fetchOne($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        if ($stmt) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        // Code to fetch a single result from a query
    }
}

?>