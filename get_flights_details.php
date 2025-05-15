<?php
require 'db.php';
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'Missing flight ID']);
    exit;
}

$id = intval($_GET['id']);

$stmt = mysqli_prepare($conn, "SELECT * FROM flights WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Flight not found']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
