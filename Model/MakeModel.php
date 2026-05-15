<?php

class MakeModel {

    private $conn;
    private $table = "MAKE";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addMake($description) {
        $query = "INSERT INTO {$this->table} (description) VALUES (:description)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":description", $description);
        return $stmt->execute();
    }

    public function getMakes() {
        $query = "SELECT * FROM {$this->table} ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
