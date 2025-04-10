<?php
$servername = "localhost";
$username = "root";  // Change this if necessary
$password = "";      // Change this if necessary
$dbname = "ticketing_system"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>
