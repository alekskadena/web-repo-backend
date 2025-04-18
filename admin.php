<?php
session_start();

// Verifikimi i hyrjes si admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: part1.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f4f4f4;
            text-align: center;
            padding: 50px;
        }
        h1 {
            color: #c0392b;
        }
        .admin-options {
            margin-top: 40px;
        }
        .admin-options a {
            display: inline-block;
            margin: 10px;
            padding: 12px 25px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }
        .admin-options a:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

    <h1>Welcome, Admin <?php echo $_SESSION['username']; ?>!</h1>
    <p>You have access to the admin control panel.</p>

    <div class="admin-options">
        <a href="#">View Users</a>
        <a href="#">Manage Offers</a>
        <a href="part1.php">Go to Homepage</a>
        <a href="logout.php">Logout</a>
    </div>

</body>
</html>
