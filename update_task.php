<?php
session_start();
include 'db.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Check if task_id and status are set in the URL
if (isset($_GET['task_id']) && isset($_GET['status'])) {
    $task_id = $_GET['task_id'];
    $status = $_GET['status']; // 0 for Pending, 1 for Completed
    $user_id = $_SESSION['user_id'];

    // Validate that the task belongs to the logged-in user
    $stmt = $conn->prepare("SELECT user_id FROM task WHERE id = ?");
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $task = $result->fetch_assoc();

    if ($task && $task['user_id'] == $user_id) {
        // Update the status of the task
        $stmt = $conn->prepare("UPDATE task SET completed = ? WHERE id = ?");
        $stmt->bind_param("ii", $status, $task_id);
        if ($stmt->execute()) {
            // Redirect back to dashboard
            header("Location: dashbroad.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        // Redirect if task doesn't belong to the current user
        header("Location: dashbroad.php");
        exit();
    }
} else {
    // Redirect if task_id or status is not set
    header("Location: dashbroad.php");
    exit();
}
?>
