<?php

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;  
}

require("db.php");

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
if (isset($data['email']) && isset($data['password']) && isset($data['confirmPassword']) && isset($data['token'])) {
    $email = $data['email'];
    $password = $data['password'];
    $confirmPassword = $data['confirmPassword'];
    $token = $data['token'];

    $checkTokenQuery = "SELECT * FROM password_resets WHERE token = '$token' AND email = '$email'";
    $result = mysqli_query($conn, $checkTokenQuery);
    if (mysqli_num_rows($result) == 0) {
        echo json_encode(["success" => false, "message" => "Invalid or expired token."]);
        exit;
    }

    if ($password === $confirmPassword) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $update = "UPDATE users SET password='$hashedPassword' WHERE email='$email'";

        if (mysqli_query($conn, $update)) {
            echo json_encode(["success" => true, "message" => "Password updated successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Something went wrong. Try again."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Passwords do not match."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "All fields are required."]);
}
?>