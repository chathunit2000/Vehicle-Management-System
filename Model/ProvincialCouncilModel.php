<?php

class ProvincialCouncilModel {

    private $conn;
    private $table = "PROVINCIAL_COUNCIL";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addProvincialCouncil($description) {
        $query = "INSERT INTO {$this->table} (description) VALUES (:description)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":description", $description);
        return $stmt->execute();
    }

    public function getProvincialCouncils() {
        $query = "SELECT * FROM {$this->table} ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
