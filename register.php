<?php
header("Access-Control-Allow-Origin: http://localhost:5173");  
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");   
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 
header("Access-Control-Allow-Credentials: true"); 

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

session_start(); 
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $data = json_decode(file_get_contents("php://input"), true);
    $username = trim($data["username"] ?? ''); 
    $email = trim($data["email"] ?? ''); 
    $fullname = trim($data["fullname"] ?? ''); 
    $password = $data["password"] ?? ''; 
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); 
    if (strpos($email, '@apollo.team') !== false) {
        $role_id = 2; // Admin
    } else {
        $role_id = 1; // User
    }

    $query = "INSERT INTO users (fullname, email, username, password, role_id) 
              VALUES ('$fullname', '$email', '$username', '$hashed_password', $role_id)";
    
    if (mysqli_query($conn, $query)) {
      
        $_SESSION["user_id"] = mysqli_insert_id($conn); 
        $_SESSION["username"] = $username;
        $_SESSION["role"] = $role_id;
        if ($role_id == 2) {
            echo json_encode([
                "status" => "success",
                "message" => "User registered successfully.",
                "redirect" => "admin.jsx" 
            ]);
        } else {
            echo json_encode([
                "status" => "success",
                "message" => "User registered successfully.",
                "redirect" => "Profile.jsx" 
            ]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Registration failed."]);
    }
    mysqli_close($conn);
}
?>