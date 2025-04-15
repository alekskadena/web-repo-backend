<?php
include 'db.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // e enkriptuar
$default_role = 1; // 1 = passenger

// Kontrollo nëse ekziston email ose username
$check = $conn->prepare("SELECT * FROM users WHERE fullname=? OR email=?");
$check->bind_param("ss", $fullname, $email);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "User already exists!";
} else {
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, role_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $fullname, $email, $password, $default_role);
    
    if ($stmt->execute()) {
        echo "Registration successful! <a href='login.html'>Login now</a>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
