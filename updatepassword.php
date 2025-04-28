<?php
require("db.php");

header('Content-Type: application/json');

// Marrim të dhënat nga kërkesa JSON
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['email']) && isset($data['Password']) && isset($data['ConfirmPassword'])) {
    $email = $data['email'];
    $password = $data['Password'];
    $confirmPassword = $data['ConfirmPassword'];

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
