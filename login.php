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
    $identifier = trim($data["identifier"] ?? ''); 
    $password = $data["password"] ?? ''; 

    if (empty($identifier) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Please fill in all fields."]);
        exit();
    }

    $query = "SELECT users.*, roles.name AS role_name 
              FROM users 
              JOIN roles ON users.role_id = roles.id
              WHERE email = ? OR username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $identifier, $identifier); 
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role_name"];
            
        //bejm validimin nese perdoruesi eshte admin or just a normal user. ROLE ID 1 eshte per normal user dhe 2 per adminat
            if (strpos($user["email"], "@apollo.team") !== false || $user["role_name"] == 2) {
                echo json_encode([
                    "status" => "success",
                    "role" => "admin",
                    "redirect" => "admin.jsx" 
                ]);
            } else {
                echo json_encode([
                    "status" => "success",
                    "role" => "user",
                    "redirect" => "Profile.jsx" 
                ]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Incorrect password."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not found."]);
    }
    mysqli_stmt_close($stmt);
}

