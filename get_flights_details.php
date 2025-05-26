<?php
session_start();

// Lejo origin-in e front-end-it tënd (mund ta ndryshosh sipas nevojës)
$allowedOrigin = "http://localhost:5173";

// Headerat për CORS
header("Access-Control-Allow-Origin: $allowedOrigin");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// Përgjigje për OPTIONS request (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Lidhu me bazën e të dhënave
$connection = new mysqli("localhost", "root", "", "dbapollo");
if ($connection->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed: " . $connection->connect_error]);
    exit();
}

// Kontrollo nëse erdhi ID dhe është numer
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid or missing flight ID"]);
    exit();
}
$id = (int)$_GET['id'];

// Përgatit query me prepared statement
$sql = "SELECT * FROM flights WHERE id = ?";
$stmt = $connection->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: failed to prepare statement"]);
    exit();
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $flight = $result->fetch_assoc();
    echo json_encode($flight);
} else {
    http_response_code(404);
    echo json_encode(["error" => "Flight not found"]);
}

// Pastro
$stmt->close();
$connection->close();
?>