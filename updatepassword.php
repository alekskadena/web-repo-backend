<?php

header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Kontrolloni nëse të gjitha fushat janë të pranishme
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email']) || !isset($data['password']) ) {
    echo json_encode(["success" => false, "message" => "All fields are required."]);
    exit;
}

$email = $data['email'];
$new_password = $data['password'];


// Përshtatja e lidhjes me bazën e të dhënave
require 'db.php';

// Kontrolloni nëse email-i ekziston në databazë
$query = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Përdoruesi është gjetur, mund të përditësojmë fjalëkalimin
    $user = $result->fetch_assoc();

    // Kripto fjalëkalimin e ri
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Përditësojmë fjalëkalimin e përdoruesit në databazë
    $updateQuery = "UPDATE users SET password = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("si", $hashed_password, $user['id']);
    $updateStmt->execute();

    echo json_encode(["success" => true, "message" => "Password updated successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Email not found."]);
}
?>
