<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type");

$connection = new mysqli("localhost", "root", "", "dbapollo");

if ($connection->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $connection->connect_error]));
}

$data = json_decode(file_get_contents("php://input"), true);

// Marrim te dhenattt
$departure = $connection->real_escape_string($data["departure"]);
$arrival = $connection->real_escape_string($data["arrival"]);
$departureDate = $connection->real_escape_string($data["departureDate"]);
$returnDate = $connection->real_escape_string($data["returnDate"]);
$tripType = $connection->real_escape_string($data["tripType"]);

$flights = [];

$sql = "SELECT * FROM flights WHERE from_location='$departure' AND to_location='$arrival' AND departure LIKE '$departureDate%'";
$result = $connection->query($sql);
while ($row = $result->fetch_assoc()) {
    $flights[] = $row;
}

if ($tripType === "Return" && !empty($returnDate)) {
    $sqlReturn = "SELECT * FROM flights WHERE from_location='$arrival' AND to_location='$departure' AND departure LIKE '$returnDate%'";
    $resultReturn = $connection->query($sqlReturn);
    while ($row = $resultReturn->fetch_assoc()) {
        $flights[] = $row;
    }
}

echo json_encode($flights);
$connection->close();
