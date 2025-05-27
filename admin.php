<?php
session_start();
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized.']);
    exit;
}

include('db.php');

$query = "SELECT id, username, email, role_id FROM users";
$result = mysqli_query($conn, $query);

if ($result) {
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = [
            'id' => $row['id'],
            'username' => $row['username'],
            'email' => $row['email'],
            'role' => ($row['role_id'] == 2) ? 'Admin' : 'User'
        ];
    }
    echo json_encode(['users' => $users]);
} else {
    echo json_encode(['error' => 'Error fetching users.']);
}
?>
