<?php
session_start();
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

include('db.php');

$response = [];

$query = "SELECT id, username, email, role_id FROM users";
$result = mysqli_query($conn, $query);

if ($result) {
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $role = ($row['role_id'] == 2) ? 'Admin' : 'User';
        $users[] = [
            'id' => $row['id'],
            'username' => $row['username'],
            'email' => $row['email'],
            'role' => $role
        ];
    }
    echo json_encode(['users' => $users]);
} else {
    echo json_encode(['error' => 'Error fetching users from database.']);
}
