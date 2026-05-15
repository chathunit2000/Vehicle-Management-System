<?php

class FuelTypeModel {

    private $conn;
    private $table = "FUEL_TYPE";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addFuelType($description) {
        $query = "INSERT INTO {$this->table} (description) VALUES (:description)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":description", $description);
        return $stmt->execute();
    }

    public function getFuelTypes() {
        $query = "SELECT * FROM {$this->table} ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
