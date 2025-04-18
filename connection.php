<?php
 
 $servername = "localhost";
 $username = "root"; 
 $password = ""; 
 $dbname = "testing"; 
 
 $conn = mysqli_connect("localhost","root", "", "testing");
 
 if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
 }
?> 