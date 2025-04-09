<?php
session_start();
include 'db.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_name = trim($_POST['task_name']);
    $due_date = $_POST['due_date'];
    $priority = $_POST['priority'];
    $user_id = $_SESSION['user_id'];

    // Insert task into database
    $sql = "INSERT INTO task (user_id, name, due_date, priority, completed) VALUES (?, ?, ?, ?, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $task_name, $due_date, $priority);

    if ($stmt->execute()) {
        header("Location: dashbroad.php"); // Redirect back to dashboard after adding task
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
