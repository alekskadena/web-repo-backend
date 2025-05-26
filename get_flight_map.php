<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

$connection = new mysqli("localhost", "root", "", "dbapollo");
if ($connection->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$flight_id = trim($_GET['flight_id'] ?? '');
if (!ctype_digit($flight_id)) {
    echo json_encode(["error" => "Invalid flight_id"]);
    exit;
}
$flight_id = intval($flight_id);

$sql = "
SELECT f.id, f.flight_code, f.from_location, f.to_location, f.departure, f.arrival,
       c_from.latitude AS from_lat, c_from.longitude AS from_lng,
       c_to.latitude AS to_lat, c_to.longitude AS to_lng
FROM flights f
JOIN cities c_from ON f.from_location COLLATE utf8mb4_general_ci = c_from.city_name COLLATE utf8mb4_general_ci
JOIN cities c_to ON f.to_location COLLATE utf8mb4_general_ci = c_to.city_name COLLATE utf8mb4_general_ci
WHERE f.id = ?
";

$stmt = $connection->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to prepare statement: " . $connection->error]);
    exit;
}

$stmt->bind_param("i", $flight_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["error" => "Flight not found"]);
    exit;
}

$flight = $result->fetch_assoc();
echo json_encode($flight);

$connection->close();
?>