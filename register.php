<?php
include "db.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = trim($data["fullname"]);
    $email = trim($data["email"]);
    $username = trim($data["username"]);
    $password = $data["password"];
    $confirm_password = $data["confirm_password"];

    if (empty($fullname) || empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        echo json_encode(["message" => "All fields are required."]);
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["message" => "Invalid email format."]);
        exit();
    }
    if (strlen($password) < 6) {
        echo json_encode(["message" => "Password must be at least 6 characters."]);
        exit();
    }
    if ($password !== $confirm_password) {
        echo json_encode(["message" => "Passwords do not match."]);
        exit();
    }

    $email = mysqli_real_escape_string($conn, $email);
    $username = mysqli_real_escape_string($conn, $username);

    $checkQuery = "SELECT * FROM users WHERE email='$email' OR username='$username'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        echo json_encode(["message" => "Email or username already in use."]);
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $role_id = 1;

    $insertQuery = "INSERT INTO users (fullname, email, username, password, role_id) 
                    VALUES ('$fullname', '$email', '$username', '$hashedPassword', $role_id)";

    if (mysqli_query($conn, $insertQuery)) {
        echo json_encode(["message" => "Registration successful!"]);
    } else {
        echo json_encode(["message" => "Something went wrong: " . mysqli_error($conn)]);
    }
}
?>