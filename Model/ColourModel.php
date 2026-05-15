<?php

class ColourModel {

    private $conn;
    private $table = "COLOUR";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addColour($description) {
        $query = "INSERT INTO {$this->table} (description) VALUES (:description)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":description", $description);
        return $stmt->execute();
    }

    public function getColours() {
        $query = "SELECT * FROM {$this->table} ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
