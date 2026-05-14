<?php
require_once __DIR__ . '/../database/db.php';
require_once __DIR__ . '/../model/VehicleModel.php';

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

            $class_name = trim($_POST['class_name']);

            if(!empty($class_name)) {

                $this->model->addVehicle($class_name);

                header("Location: index.php");
            }
        }
    }

    // Display Vehicles
    public function getVehicles() {

        return $this->model->getVehicles();
    }
}
?>