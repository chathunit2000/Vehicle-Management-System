<?php

 //Database information
        $host = "192.168.1.63";
        $username = "logdb_user";
        $password = '$$HilF50r';
        $db = "dbDutyfreePOS"; 

        
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        //Error handling
        try {
                 $mysqli = new mysqli($host,$username, $password, $db);
                // $mysqli->set_charset("utf8mb4");
                 return $mysqli;

        } catch(Exception $e) {
            
       // header("Location:error.php?id=".$e->getMessage());
          error_log($e->getMessage());
          exit('Error connecting to database'); //Should be a message a typical user could understand
        }

?>