<?php 
header("Access-Control-Allow-Origin: http://localhost:5173");  
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");   
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 
header("Access-Control-Allow-Credentials: true"); 

session_start();
include "db.php"; 

if (isset($_SESSION["role"])) {
    if ($_SESSION["role"] == 2) {
        echo json_encode(["status" => "success", "role" => "admin"]);
    } else {
        echo json_encode(["status" => "success", "role" => "user"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
}
?>

