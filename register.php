<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin === 'http://localhost:5173') {
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Credentials: true");
}
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json"); 

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

session_start();
include "db.php"; 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);
    if (!$data) {
        echo json_encode(["status" => "error", "message" => "Invalid JSON input."]);
        exit();
    }
    $username = trim($data["username"] ?? '');
    $email = trim($data["email"] ?? '');
    $fullname = trim($data["fullname"] ?? '');
    $password = $data["password"] ?? '';
    $confirm_password = $data["confirm_password"] ?? '';

    if ($username === '' || $email === '' || $fullname === '' || $password === '') {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit();
    }

    if ($password !== $confirm_password) {
        echo json_encode(["status" => "error", "message" => "Passwords do not match."]);
        exit();
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $role_id = (strpos($email, '@apollo.team') !== false) ? 2 : 1;
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, username, password, role_id) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
        exit();
    }

    $stmt->bind_param("ssssi", $fullname, $email, $username, $hashed_password, $role_id);
    if ($stmt->execute()) {
        $_SESSION["user_id"] = $stmt->insert_id;
        $_SESSION["username"] = $username;
        $_SESSION["role"] = $role_id;
        $redirect = $role_id === 2 ? "admin.jsx" : "Profile.jsx";
        echo json_encode([
            "status" => "success",
            "message" => "User registered successfully.",
            "redirect" => $redirect,
            "role" => $role_id 
        ]);
        exit();
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
        exit();
    }

    $stmt->close();
    $conn->close();
    exit();
}
?> 
