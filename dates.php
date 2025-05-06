<?php
session_start();
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

include("db.php");

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$from = $_GET['from'];
$to = $_GET['to'];
$query = "SELECT DISTINCT flight_date FROM flights WHERE from_location=? AND to_location=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $from, $to);
$stmt->execute();
$result = $stmt->get_result();

$dates = [];
while ($row = $result->fetch_assoc()) {
    $dates[] = $row['flight_date'];
}

echo json_encode($dates);
$conn->close();
?>
