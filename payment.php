<?php

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);


if (!$input || !isset($input['name'], $input['email'], $input['amount'], $input['paypal_order_id'])) {
    echo json_encode(['success' => false, 'message' => 'Të dhëna të munguar']);
    exit();
}

$conn = new mysqli("localhost", "root", "", "apollo_skies_db"); // Ndrysho sipas konfigurimit tënd
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Lidhja me databazën dështoi']);
    exit();
}

$name = $conn->real_escape_string($input['name']);
$email = $conn->real_escape_string($input['email']);
$amount = floatval($input['amount']);
$order_id = $conn->real_escape_string($input['paypal_order_id']);

$sql = "INSERT INTO payments (name, email, amount, paypal_order_id) VALUES ('$name', '$email', $amount, '$order_id')";

if ($conn->query($sql)) {
    echo json_encode(['success' => true, 'message' => 'Pagesa u ruajt me sukses']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gabim gjatë ruajtjes së pagesës']);
}

$conn->close();