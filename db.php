<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'dbapollo';

$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Connection failed: ' . mysqli_connect_error()
    ]);
    exit;
}
?>
