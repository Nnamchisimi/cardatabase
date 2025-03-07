<?php
$servername = "localhost";   // If MySQL Workbench is on the same machine, use localhost or 127.0.0.1
$username = "root";          // The MySQL username (as in your MySQL Workbench)
$password = "123456789"; // Your MySQL password (if set in MySQL Workbench)
$dbname = "users";           // The database name you're working with
$port = 3306;                // The port MySQL is using (default is 3306)

$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "";
}
?>
