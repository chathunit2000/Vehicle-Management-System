<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

class Database {

    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $database = "vehicle_management_system";
    private $conn;

    public function connect() {
        $this->conn = null;
        try {
            $dsn = "mysql:unix_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock;dbname={$this->database};charset=utf8";
            $this->conn = new PDO($dsn, $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
        return $this->conn;
    }
}
