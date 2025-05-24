

<?php
session_start();
$flight_id = $_GET['flight_id'];
$connection = new mysqli("localhost", "root", "", "dbapollo");

if ($connection->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $connection->connect_error]));
}


$sql = "
    SELECT b.id, u.name AS user_name, f.flight_code, f.from_location, f.to_location, 
           f.departure, f.arrival, b.booking_date, b.status 
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN flights f ON b.flight_id = f.id
    WHERE b.flight_id = ?"; 

$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $flight_id);
$stmt->execute();
$result = $stmt->get_result();

$bookingDetails = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookingDetails[] = $row;
    }
}

echo json_encode($bookingDetails);
$connection->close();
?>