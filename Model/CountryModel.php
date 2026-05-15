<?php

class CountryModel {

    private $conn;
    private $table = "COUNTRY";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addCountry($description) {
        $query = "INSERT INTO {$this->table} (description) VALUES (:description)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":description", $description);
        return $stmt->execute();
    }

    public function getCountries() {
        $query = "SELECT * FROM {$this->table} ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
