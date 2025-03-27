<?php
$host = "localhost"; 
$user = "root";      // MySQL username
$password = "";      //  MySQL password
$dbname = "task_manager";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
