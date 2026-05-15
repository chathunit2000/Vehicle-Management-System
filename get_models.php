<?php
header('Content-Type: application/json');

include 'database/db.php';

$makeId = isset($_GET['make_id']) ? (int)$_GET['make_id'] : 0;

if ($makeId <= 0) {
    echo json_encode([]);
    exit;
}

$database = new Database();
$conn = $database->connect();

$stmt = $conn->prepare("SELECT id, description FROM MODEL WHERE makeid = :makeid ORDER BY description ASC");
$stmt->bindParam(':makeid', $makeId, PDO::PARAM_INT);
$stmt->execute();
$models = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($models);
