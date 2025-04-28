<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = trim($_POST["identifier"]); 
    $password = $_POST["password"];

    if (empty($identifier) || empty($password)) {
        echo "Please fill in all fields.";
        exit();
    }

    $query = "SELECT users.*, roles.name AS role_name 
              FROM users 
              JOIN roles ON users.role_id = roles.id
              WHERE email='$identifier' OR username='$identifier'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role_name"];

            if ($user["role_name"] == "admin") {
                echo "admin";
            } else {
                echo "passenger";
            }
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "User not found.";
    }
}
?>