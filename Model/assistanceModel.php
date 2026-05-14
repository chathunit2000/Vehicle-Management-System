<?php
require_once "../database/db.php"; // Adjust path if needed
require_once "../database/sqldb.php"; // Adjust path if needed

class assistanceModel
{
    //Get all assistance or particular employee assistance list
    public function getUserAssistance($id)
    {
        $dbObject = new db();
        $conn = $dbObject->getEmployeeConnection();
        $rows = [];
        $rows = [];
        $sql = "";
        if ($id == 0) {
            $sql = "SELECT * FROM tblPaxAssistance WHERE userId ={$_SESSION["USER"]} order by paxAssistanceId desc";
            //   $stmt = sqlsrv_prepare($conn, $sql, array($_SESSION["USER"]));
            $stmt = sqlsrv_query($conn, $sql);

            // Check if the query was successful
            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

          
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $rows[] = $row;
            }
        } else {
            $sql = "SELECT * FROM tblPaxAssistance WHERE userId ={$_SESSION["USER"]} and paxAssistanceId={$id}";

            //   $stmt = sqlsrv_prepare($conn, $sql, array($_SESSION["USER"]));
            $stmt = sqlsrv_query($conn, $sql);

            // Check if the query was successful
            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

          
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $rows["header"][] = $row;
            }

            $sql = "select Area from tblPaxAssistance pa join tblAssistanceArea aa on aa.assistanceId = pa.paxAssistanceId where pa.paxAssistanceId ={$id}";

            //   $stmt = sqlsrv_prepare($conn, $sql, array($_SESSION["USER"]));
            $stmt = sqlsrv_query($conn, $sql);

            // Check if the query was successful
            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

          
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $rows["area"][] = $row;
            }
        }

        return $rows;
    }

    //Get Approval Assistance List
    public function getApprovalList($id)
    {
        $dbObject = new db();
        $conn = $dbObject->getEmployeeConnection();
        $rows = [];
        $rows = [];
        $sql = "";
        if ($id == 0) {
            
            //Load Approval list for AM
            if ($_SESSION["divId"] == 6 && $_SESSION["TYPE"] == "HOD") {
                $sql = "SELECT * FROM tblPaxAssistance WHERE status = 'HOD Approved' order by paxAssistanceId desc";
                //   $stmt = sqlsrv_prepare($conn, $sql, array($_SESSION["USER"]));
                $stmt = sqlsrv_query($conn, $sql);

                // Check if the query was successful
                if ($stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

              
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $rows[] = $row;
                }
            } 
             //Load Approval list for Security
            else if ($_SESSION["divId"] == 20 && $_SESSION["TYPE"] == "HOD") {
                 echo "Hi";
                $sql = "SELECT * FROM tblPaxAssistance WHERE status = 'AM Approved' order by paxAssistanceId desc";
                //   $stmt = sqlsrv_prepare($conn, $sql, array($_SESSION["USER"]));
                $stmt = sqlsrv_query($conn, $sql);

                // Check if the query was successful
                if ($stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

              
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $rows[] = $row;
                }
             //particular division approvals
            } elseif ($_SESSION["TYPE"] == "HOD") {
                $sql = "SELECT * FROM tblPaxAssistance WHERE status = 'pending' and division = '{$_SESSION["DIVISION"]}' order by paxAssistanceId desc";
                //   $stmt = sqlsrv_prepare($conn, $sql, array($_SESSION["USER"]));
                $stmt = sqlsrv_query($conn, $sql);

                // Check if the query was successful
                if ($stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

              
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $rows[] = $row;
                }
            }
        } else {
            $sql = "SELECT * FROM tblPaxAssistance WHERE userId ={$_SESSION["USER"]} and paxAssistanceId={$id}";

            //   $stmt = sqlsrv_prepare($conn, $sql, array($_SESSION["USER"]));
            $stmt = sqlsrv_query($conn, $sql);

            // Check if the query was successful
            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

          
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $rows["header"][] = $row;
            }

            $sql = "select Area from tblPaxAssistance pa join tblAssistanceArea aa on aa.assistanceId = pa.paxAssistanceId where pa.paxAssistanceId ={$id}";

            //   $stmt = sqlsrv_prepare($conn, $sql, array($_SESSION["USER"]));
            $stmt = sqlsrv_query($conn, $sql);

            // Check if the query was successful
            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

          
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $rows["area"][] = $row;
            }
        }

        return $rows;
    }

    // Insert Asssitance Details
    public function setAssistanceRequest(
        $movementType,
        $flightDateTime,
        $flightNo,
        $passengerName,
        $relationship,
        $txtRemark,
        $areas,
        $serviceNo,
        $employeeNamem,
        $designation
    ) {
        $dbObject = new db();
        $conn = $dbObject->getEmployeeConnection();

        $Status = "Pending";
        // Query to insert email
        $query1 =
            "INSERT INTO tblPaxAssistance ([movementType],[movementDateTime],[flightNo],[passengerName],[relationship],[remarks] ,[status],userId,service_no,employeeName,designation,division) VALUES (?,?,?,?,?,?,?,?,?,?,?,?); SELECT SCOPE_IDENTITY() AS last_id;";
        // Prepare the query
        $stmt1 = sqlsrv_prepare($conn, $query1, [
            $movementType,
            $flightDateTime,
            $flightNo,
            $passengerName,
            $relationship,
            $txtRemark,
            $Status,
            $_SESSION["USER"],
            $serviceNo,
            $employeeNamem,
            $designation,
            $_SESSION["DIVISION"],
        ]);

        // Execute the query
        if (sqlsrv_execute($stmt1)) {
            if (sqlsrv_rows_affected($stmt1) > 0) {
                //get Last inserted Id
                sqlsrv_next_result($stmt1);
                // Fetch the result of SCOPE_IDENTITY()
                if ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
                    $res = $row["last_id"];
                } else {
                    $res = -1;
                }

                if ($res > 0) {
                    // Add Area granted
                    foreach ($areas as $loc) {
                        $query1 = "INSERT INTO tblAssistanceArea (assistanceId,Area) VALUES (?,?)";
                        // Prepare the query
                        $stmt1 = sqlsrv_prepare($conn, $query1, [$res, $loc]);
                        sqlsrv_execute($stmt1);
                    }

                    //Add History
                    $query1 = "INSERT INTO tblAssistanceHistory (userId,Remark) VALUES (?,?)";
                    // Prepare the query
                    $remark = "created at " . $res . " " . date("Y-m-d H:i:s");
                    $stmt1 = sqlsrv_prepare($conn, $query1, [$_SESSION["USER"], $remark]);
                    sqlsrv_execute($stmt1);
                }
            } else {
                $res = -1;
            }
        } else {
            $res = -1;
        }
        return $res;
    }

    // Update Asssitance Details
    public function updateAssistanceRequest(
        $movementType,
        $flightDateTime,
        $flightNo,
        $passengerName,
        $relationship,
        $txtRemark,
        $areas,
        $txtRequestId,
        $serviceNo,
        $employeeNamem,
        $designation
    ) {
        $dbObject = new db();
        $conn = $dbObject->getEmployeeConnection();

        $Status = "Pending";
        $flightDateTime = str_replace("T", " ", $flightDateTime);
        // Query to insert email
        $query1 =
            "UPDATE tblPaxAssistance SET movementType=?,movementDateTime=?,flightNo=?,passengerName=?,relationship=?,remarks=?,service_no=?,employeeName=?,designation=? WHERE paxAssistanceId=?";
        // Prepare the query
        $stmt1 = sqlsrv_prepare($conn, $query1, [
            $movementType,
            $flightDateTime,
            $flightNo,
            $passengerName,
            $relationship,
            $txtRemark,
            $serviceNo,
            $employeeNamem,
            $designation,
            $txtRequestId,
        ]);

        // Execute the query
        if (sqlsrv_execute($stmt1)) {
            if (sqlsrv_rows_affected($stmt1) > 0) {
                // Delete already set access
                $query1 = "DELETE tblAssistanceArea WHERE paxAssistanceId=?";
                // Prepare the query
                $stmt1 = sqlsrv_prepare($conn, $query1, [$txtRequestId]);
                sqlsrv_execute($stmt1);

                foreach ($areas as $loc) {
                    $query1 = "INSERT INTO tblAssistanceArea (assistanceId,Area) VALUES (?,?)";
                    // Prepare the query
                    $stmt1 = sqlsrv_prepare($conn, $query1, [$txtRequestId, $loc]);
                    sqlsrv_execute($stmt1);
                }

                //Add History
                $query1 = "INSERT INTO tblAssistanceHistory (userId,Remark) VALUES (?,?)";
                // Prepare the query
                $remark = "updated at " . $txtRequestId . " " . date("Y-m-d H:i:s");
                $stmt1 = sqlsrv_prepare($conn, $query1, [$_SESSION["USER"], $remark]);
                sqlsrv_execute($stmt1);

                $res = sqlsrv_rows_affected($stmt1);
            } else {
                $res = -1;
            }
        } else {
            $res = -1;
        }
        return $res;
    }

    //delete a particular assistanced request
    public function deleteAssistanceRequest($txtRequestId)
    {
        $dbObject = new db();
        $conn = $dbObject->getEmployeeConnection();

        // Delete assistance details
        $query1 = "DELETE tblAssistanceArea WHERE assistanceId=?";
        // Prepare the query
        $stmt1 = sqlsrv_prepare($conn, $query1, [$txtRequestId]);
        sqlsrv_execute($stmt1);

        // Delete assistance area
        $query1 = "DELETE tblPaxAssistance WHERE paxAssistanceId=?";
        // Prepare the query
        $stmt1 = sqlsrv_prepare($conn, $query1, [$txtRequestId]);
        sqlsrv_execute($stmt1);

        //Add History
        $query1 = "INSERT INTO tblAssistanceHistory (userId,Remark) VALUES (?,?)";
        // Prepare the query
        $remark = "deleted " . $txtRequestId . " at " . date("Y-m-d H:i:s");
        $stmt1 = sqlsrv_prepare($conn, $query1, [$_SESSION["USER"], $remark]);
        sqlsrv_execute($stmt1);

        $res = sqlsrv_rows_affected($stmt1);
        return $res;
    }

    //update request status
    public function setApproval($txtRequestId, $status)
    {
        $dbObject = new db();
        $conn = $dbObject->getEmployeeConnection();
        $res = 0;
        
        if($status =="Approved")
        {
            //Set AM Approval
            if ($_SESSION["divId"] == 6 && $_SESSION["TYPE"] == "HOD") {
                    $status = "AM Approved";
            } 
             //Set Security Approval
            else if ($_SESSION["divId"] == 20 && $_SESSION["TYPE"] == "HOD") {
                    $status = "Access Granted";
             // HOD Approval
            } elseif ($_SESSION["TYPE"] == "HOD") {
                    $status = "HOD Approved";
            }
        }
        else if($status =="Rejected")
        {
             //Set AM Rejection
            if ($_SESSION["divId"] == 6 && $_SESSION["TYPE"] == "HOD") {
                    $status = "AM Rejected";
            } 
             //Set Security Rejection
            else if ($_SESSION["divId"] == 20 && $_SESSION["TYPE"] == "HOD") {
                    $status = "Access Denied";
             // HOD Rejection
            } elseif ($_SESSION["TYPE"] == "HOD") {
                    $status = "HOD Rejected";
            }
        }
        
      

        // Update Status of request
        $query1 = "UPDATE tblPaxAssistance SET status = ? WHERE paxAssistanceId=?";
        // Prepare the query
        $stmt1 = sqlsrv_prepare($conn, $query1, [$status, $txtRequestId]);
        if (sqlsrv_execute($stmt1)) {
            $res = sqlsrv_rows_affected($stmt1);

            //Add History
            $query1 = "INSERT INTO tblAssistanceHistory (userId,Remark) VALUES (?,?)";
            // Prepare the query
            $remark = "HOD Approved at " . $txtRequestId . " " . date("Y-m-d H:i:s");
            $stmt1 = sqlsrv_prepare($conn, $query1, [$_SESSION["USER"], $remark]);
            sqlsrv_execute($stmt1);

            $res = sqlsrv_rows_affected($stmt1);
        } else {
            $res = -1;
        }
        return $res;
    }
}
?>
