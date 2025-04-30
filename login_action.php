<?php
session_start();
include('db.php'); 

$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE username = :username";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':username', $username);
$stmt->execute();

$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username']; 
    $_SESSION['email'] = $user['email']; 
    $_SESSION['role'] = 'user'; 
    if (strpos($user['email'], 'apollo.team') !== false) {
        $_SESSION['role'] = 'admin'; 
    }

    header('Location: profile.php');
    exit();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Username or password is incorrect.']);
    exit();
}
?>
