<?php
require_once "../database/db.php";

require_once "../Model/VehicleModel.php";

class VehicleController {

    private $model;

    public function __construct() {

        $database = new Database();
        $db = $database->connect();

        $this->model = new VehicleModel($db);
    }

    // Add Vehicle
    public function addVehicle() {

        if(isset($_POST['add_vehicle'])) {

            $description = trim($_POST['description']);

            if(!empty($description)) {

                $this->model->addVehicle($description);

                // Reload page to show updated list
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        }
    }

    // Display Vehicles
    public function getVehicles() {

        return $this->model->getVehicles();
    }
}
?>