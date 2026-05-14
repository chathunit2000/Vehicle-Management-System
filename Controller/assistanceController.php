<?php
session_start();
include '../model/assistanceModel.php';

//Get all assistance or particular employee assistance list
if(isset($_POST['assistanceId'])) {
    
    $rows = array();    
    $assistanceModelObj = new assistanceModel(); 
    
    $id = $_POST['assistanceId'];
    $rows = $assistanceModelObj->getUserAssistance($id);
    echo json_encode($rows);
}

//Insert , Update & delete Asssitance Details
if(isset($_POST['txtRequestId'])) {
$rows = 0;
header('Content-Type: application/json');
$status = $_POST['status'];
$txtRequestId = $_POST['txtRequestId'];
$movementType = $_POST['cmbMovementType'];
$flightDateTime = $_POST['dtpFlightDate'];
$flightNo = $_POST['txtFlightNo'];
$passengerName = $_POST['txtPaxName'];
$relationship = $_POST['cmbRelationship'];
$txtRemark = $_POST['txtRemark'];
$serviceNo = isset($_POST['serviceNo']) ? $_POST['serviceNo'] : $_SESSION["serviceNo"];
$employeeName = isset($_POST['employeeName']) ? $_POST['employeeName'] : $_SESSION["DISPLAYNAME"];
$designation = isset($_POST['designation']) ? $_POST['designation'] : $_SESSION["designation"];
$areas = isset($_POST['areas']) ? $_POST['areas'] : [];

$assistanceModelObj = new assistanceModel();  

if($status =="new")
{
    $rows = $assistanceModelObj->setAssistanceRequest($movementType,$flightDateTime,$flightNo,$passengerName,$relationship,$txtRemark,$areas,$serviceNo,$employeeName,$designation); 
}
else if($status =="edit")
{

    $rows = $assistanceModelObj->updateAssistanceRequest($movementType,$flightDateTime,$flightNo,$passengerName,$relationship,$txtRemark,$areas,$txtRequestId,$serviceNo,$employeeName,$designation);
}
else if($status =="delete")
{

    $rows = $assistanceModelObj->deleteAssistanceRequest($txtRequestId);
}
    
if($rows >0)
{
    $response = [
        "status" => "success",
        "message" => "Update successfully",
        "id" => $rows
    ]; 
    
}
else
{
    $response = [
        "status" => "failed",
        "message" => "Update error",
        "id" => $rows
    ]; 
}


echo json_encode($response);
}


if(isset($_POST['approvalList'])) {
    $rows = array();  
    $id = $_POST['approvalList'];
    $status = '';
    $assistanceModelObj = new assistanceModel(); 
    $rows = $assistanceModelObj->getApprovalList($id);
    echo json_encode($rows);
}
//Set Approval
if(isset($_POST['approveRequestId'])) {
    $rows = 0;  
    $id = $_POST['approveRequestId'];
    $assistanceModelObj = new assistanceModel(); 
    $rows = $assistanceModelObj->setApproval($id,"Approved");
    
    if($rows >0)
    {
        $response = [
            "status" => "success",
            "message" => "Update successfully",
            "id" => $rows
        ]; 

    }
    else
    {
        $response = [
            "status" => "failed",
            "message" => "Update error",
            "id" => $rows
        ]; 
    }
    echo json_encode($response);
}
//Set rejection
if(isset($_POST['rejectrequestId'])) {
    $rows = 0;  
    $id = $_POST['rejectrequestId'];
    $assistanceModelObj = new assistanceModel(); 
    $rows = $assistanceModelObj->setApproval($id,"Rejected");
    
    if($rows >0)
    {
        $response = [
            "status" => "success",
            "message" => "Update successfully",
            "id" => $rows
        ]; 

    }
    else
    {
        $response = [
            "status" => "failed",
            "message" => "Update error",
            "id" => $rows
        ]; 
    }
    echo json_encode($response);
}





?>