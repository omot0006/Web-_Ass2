<?php
$host = "localhost"; 
$user = "root";  // login name for myworkbecnh    
$password = "Saganrx12.";// password for that db  
$dbname = "task_manager"; // name of the mysqlworkbecnh 

$conn = new mysqli($host, $user, $password, $dbname);
//look at connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

