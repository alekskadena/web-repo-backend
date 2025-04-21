<?php

$host = "localhost";       
$user = "root";             
$password = "";             
$dbname = "dbapollo";       
$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Nuk u lidh me databasen: " . mysqli_connect_error());
}
?>

