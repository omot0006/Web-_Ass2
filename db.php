<?php
$host = "localhost"; // or 127.0.0.1
$user = "root";      // your MySQL username
$password = "";      // your MySQL password
$dbname = "task_manager";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
