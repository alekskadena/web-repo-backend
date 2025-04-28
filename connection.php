<?php
 
 $servername = "localhost";
 $username = "root"; 
 $password = ""; 
 $dbname = "dbapollo"; 
 
 $conn = mysqli_connect("localhost","root", "", "dbapollo");
 
 if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
 }
?> 