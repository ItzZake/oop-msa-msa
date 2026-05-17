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
        // Code to execute a query with parameters
    }
    public function Connect()
    {

        // Code to establish database connection
    }
    public function Disconnect()
    {
        // Code to close database connection
    }
    public function IsConnected()
    {
        // Code to check if database connection is active
    }
    public function fetchAll($sql, $params = [])
    {
        // Code to fetch all results from a query
    }

    public function fetchOne($sql, $params = [])
    {
        // Code to fetch a single result from a query
    }
}

?>