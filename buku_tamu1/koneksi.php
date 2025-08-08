<?php
// connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "receptionist";

//Create Connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

//Check Connection
if (!$conn) {
    die ("Connection failed: " . mysqli_connect_error());
}
else {
    
}
?>