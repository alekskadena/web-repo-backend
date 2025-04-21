<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (empty($fullname) || empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        echo "All fields are required.";
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }
    if (strlen($password) < 6) {
        echo "Password must be at least 6 characters.";
        exit();
    }
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }
    $checkQuery = "SELECT * FROM users WHERE email='$email' OR username='$username'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "Email or username already in use.";
        exit();
    }
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $role_id = 1;

    $insertQuery = "INSERT INTO users (fullname, email, username, password, role_id) 
                    VALUES ('$fullname', '$email', '$username', '$hashedPassword', $role_id)";

    if (mysqli_query($conn, $insertQuery)) {
        echo "Registration successful!";
    } else {
        echo "Something went wrong: " . mysqli_error($conn);
    }
}
?>
