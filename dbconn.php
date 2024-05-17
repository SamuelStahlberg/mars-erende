<?php
// Databasuppgifter
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "erende"; 

// Skapar anslutning
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Kontrollerar anslutning
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
