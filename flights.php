<?php
$conn = new mysqli("localhost", "root", "", "apolloDB");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT flight_number, origin, destination, departure_time, arrival_time, price FROM flights";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Flights</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid black; text-align: center; }
        th { background-color: #007bff; color: white; }
    </style>
</head>
<body>

    <h2>List of Available Flights</h2>

    <table>
        <tr>
            <th>Flight Number</th>
            <th>Origin</th>
            <th>Destination</th>
            <th>Departure Time</th>
            <th>Arrival Time</th>
            <th>Price (AL)</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['flight_number']}</td>
                        <td>{$row['origin']}</td>
                        <td>{$row['destination']}</td>
                        <td>{$row['departure_time']}</td>
                        <td>{$row['arrival_time']}</td>
                        <td>{$row['price']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No flights available</td></tr>";
        }
        $conn->close();
        ?>
 </table>

</body>
</html>
