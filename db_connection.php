<?php
$servername = "localhost";   
$username = "root";         
$password = "123456789"; 
$dbname = "users";       
$port = 3306;              

$conn = new mysqli($servername, $username, $password, $dbname, $port);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "";
}
?>
