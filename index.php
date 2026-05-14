<?php
/*
 session_start();
 require './db.php';
 if(isset($_POST["epf"]))
 {   
     echo $_POST['epf'];
     $_SESSION["divId"] = $_POST['divId'];
     $_SESSION["desigId"] = $_POST['desigId'];
     $_SESSION["epf"] = $_POST['epf'];
     $_SESSION["TYPE"] = $_POST['userType'];
     $_SESSION["DISPLAYNAME"] = $_POST['displayName'];
     $_SESSION["USERNAME"] = $_POST['userName'];

     $dbObject = new db();
     $conn2 = $dbObject->getUCFCConnection();  
     $sql = "select r.role_name from tblLogin l
 join userRole ur on ur.userId = l.SSN
 join systemRole sr on sr.sR_id  = ur.systemRoleId
 join tblrole r on r.role_id = sr.role_id
 join tblsystem s on s.sys_id = sr.sys_id
 where l.SSN ={$_POST["epf"]} and s.sys_id =32";

     $sth = $conn2->query($sql);
     $rows = $sth->fetchAll();

     foreach($rows as $row) {      
        echo $_SESSION["TYPE"] = $row['role_name'];
     }

     header("Location:dashboard.php");
 }
 else
 {
    header("Location: https://www.airport.lk/home.php");
     exit();
 }

*/

session_start();
$_SESSION["serviceNo"] = '007947';
$_SESSION["USER"] = '007947';
$_SESSION["DISPLAYNAME"] = 'Erandi Yapa';;
$_SESSION["DIVISION"] = 'Information Technology'; 
$_SESSION["designation"] = "HOD";
$_SESSION["divId"] = 6;
$_SESSION["TYPE"] = "HOD";    
header("Location: home.php");
exit();




require_once __DIR__ . '/Controller/VehicleController.php';
$controller = new VehicleController();

/* Add Vehicle */
$controller->addVehicle();

/* Get Vehicle List */
$vehicles = $controller->getVehicles();

/* Load View */
require_once __DIR__ . '/Views/addvehicle.php';

?>



