<?php



class VehicleModel {

    private $conn;
    private $table = "vehicle_class";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Insert Vehicle Class
    public function addVehicle($class_name) {

        $query = "INSERT INTO " . $this->table . " (class_name)
                  VALUES (:class_name)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":class_name", $class_name);

        return $stmt->execute();
    }

    // Get All Vehicle Classes
    public function getVehicles() {

        $query = "SELECT * FROM " . $this->table . "
                  ORDER BY id DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }
}
?>