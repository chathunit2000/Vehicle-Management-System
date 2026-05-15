<?php



class VehicleModel {

    private $conn;
    private $table = "vehicle_class";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Insert Vehicle Class
    public function addVehicle($description) {

       $query = "INSERT INTO vehicle_class(description)
              VALUES(:description)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":description", $description);

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