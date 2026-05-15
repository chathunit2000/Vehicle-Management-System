<?php

class StatusModel {

    private $conn;
    private $table = "vehicle_status";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addStatus($description) {
        $query = "INSERT INTO " . $this->table . " (description) VALUES(:description)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":description", $description);

        return $stmt->execute();
    }

    public function getStatuses() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
}
?>