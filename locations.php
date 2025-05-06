<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$connection = new mysqli("localhost", "root", "", "dbapollo");

if ($connection->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $connection->connect_error]));
}

$sql = "SELECT DISTINCT from_location FROM flights
        UNION
        SELECT DISTINCT to_location FROM flights";

$result = $connection->query($sql);

$locations = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_row()) {
        $locations[] = $row[0];
    }
}

echo json_encode($locations);
$connection->close();
