<?php
session_start();
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

include 'db.php';  

$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data['username'])) {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit;
}

$username = trim($data['username']);
$user_id = $_SESSION['user_id'];

if ($username === '') {
    echo json_encode(["status" => "error", "message" => "Username cannot be empty"]);
    exit;
}

$stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("si", $username, $user_id);

if (!$stmt->execute()) {
    if ($conn->errno == 1062) {
        echo json_encode(["status" => "error", "message" => "Username already taken"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
    }
    $stmt->close();
    exit;
}
$stmt->close();
echo json_encode(["status" => "success"]);