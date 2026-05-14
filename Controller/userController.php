<?php
session_start();
include '../model/userModel.php';
if(isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rows = array();    
    $userModelObj = new userModel();  
    $stmt = $userModelObj->getUser($username, $password);    
   //echo '<pre>',print_r($rows).'</pre>';
    $rows = count($stmt);
    if ($rows >0) 
    {
        
            foreach ($stmt as $val) {
    if (isset($val['UserName'])) {
        $_SESSION["USER"] = $val['UserName'];
        $_SESSION["TYPE"] = $val['UserType'];
        $_SESSION["DISPLAYNAME"] = $val['display_name'];
        $_SESSION["DIVISION"] = $val['division_name'];
       
 
         

        header("Location:../home.php");
        exit;
    }
}

    }
    else {
        //redirect to login page again ... 
        header("Location:../index.php?message=1");
        //echo "No data";
    }
} else {
    header("Location: ../index.php?success=1");
exit();

      
}


?>