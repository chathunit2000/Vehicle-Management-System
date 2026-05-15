<?php

class ModelModel {

    private $conn;
    private $table = "MODEL";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addModel($makeId, $description) {
        $query = "INSERT INTO {$this->table} (makeid, description) VALUES (:makeid, :description)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":makeid", $makeId, PDO::PARAM_INT);
        $stmt->bindParam(":description", $description);
        return $stmt->execute();
    }

    public function getModelsByMake($makeId) {
        $query = "SELECT m.id, m.description FROM {$this->table} m
                  WHERE m.makeid = :makeid ORDER BY m.id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":makeid", $makeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function getAllMakes() {
        $stmt = $this->conn->prepare("SELECT id, description FROM MAKE ORDER BY description ASC");
        $stmt->execute();
        return $stmt;
    }
}
?>
