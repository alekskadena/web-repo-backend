<?php
session_start();
include "config.php"; // Lidhja me databazën

// Kontrollo nëse përdoruesi është i loguar
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

// Merr të dhënat e përdoruesit nga databaza
$user_id = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT fullname, email, username FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile - Apollo Airline</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($user['fullname']); ?>!</h1>
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
    <p>Username: <?php echo htmlspecialchars($user['username']); ?></p>

    <a href="logout.php">Logout</a>
</body>
</html>
