<?php
session_start();
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
include "db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header("Content-Type: application/json");
    echo json_encode(['error' => 'Access Denied']);
    exit();
}

$sql = "SELECT id, username, email, role_id FROM users";
$result = mysqli_query($conn, $sql);

$users = []; 
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {

        $role = ($row['role_id'] == 2) ? 'Admin' : 'User';
        $users[] = [
            'id' => $row['id'],
            'username' => $row['username'],
            'email' => $row['email'],
            'role' => $role
        ];
    }
} else {
    $users = null; 
}
mysqli_close($conn); 
header("Content-Type: application/json");
echo json_encode(['users' => $users]);
?>
