<?php
session_start();
include 'db.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Default query to fetch all tasks
$sql = "SELECT id, name, due_date, priority, completed FROM task WHERE user_id = ?";

// Initialize filter variables
$whereClauses = [];
$filterParams = [];

// Apply filters if they are set in the form
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = "%" . $_GET['search'] . "%";
    $whereClauses[] = "name LIKE ?";
    $filterParams[] = $search;
}

if (isset($_GET['priority']) && !empty($_GET['priority'])) {
    $whereClauses[] = "priority = ?";
    $filterParams[] = $_GET['priority'];
}

if (isset($_GET['status']) && $_GET['status'] !== '') {
    $status = ($_GET['status'] === 'completed') ? 1 : 0;
    $whereClauses[] = "completed = ?";
    $filterParams[] = $status;
}

if (isset($_GET['due_date']) && !empty($_GET['due_date'])) {
    $whereClauses[] = "due_date = ?";
    $filterParams[] = $_GET['due_date'];
}

// Add filters to the SQL query
if (!empty($whereClauses)) {
    $sql .= " AND " . implode(" AND ", $whereClauses);
}

$stmt = $conn->prepare($sql);

// Check if there are filter parameters before binding
if (!empty($filterParams)) {
    // Create a string of types for bind_param dynamically
    $types = str_repeat("s", count($filterParams) + 1); // +1 for user_id which is always present
    array_unshift($filterParams, $user_id); // Add user_id as the first parameter
    $stmt->bind_param($types, ...$filterParams);
} else {
    // Bind only user_id if no filters
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <a href="logout.php">Logout</a>

    <h3>Your Tasks</h3>

    <!-- Search and Filter Form -->
    <form method="GET" action="dashbroad.php">
        <label for="search">Search Tasks:</label>
        <input type="text" name="search" id="search" placeholder="Search tasks" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">

        <label for="priority">Priority:</label>
        <select name="priority" id="priority">
            <option value="">-- All --</option>
            <option value="Low" <?php echo isset($_GET['priority']) && $_GET['priority'] === 'Low' ? 'selected' : ''; ?>>Low</option>
            <option value="Medium" <?php echo isset($_GET['priority']) && $_GET['priority'] === 'Medium' ? 'selected' : ''; ?>>Medium</option>
            <option value="High" <?php echo isset($_GET['priority']) && $_GET['priority'] === 'High' ? 'selected' : ''; ?>>High</option>
        </select>

        <label for="status">Status:</label>
        <select name="status" id="status">
            <option value="">-- All --</option>
            <option value="pending" <?php echo isset($_GET['status']) && $_GET['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="completed" <?php echo isset($_GET['status']) && $_GET['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
        </select>

        <label for="due_date">Due Date:</label>
        <input type="date" name="due_date" value="<?php echo isset($_GET['due_date']) ? $_GET['due_date'] : ''; ?>">

        <button type="submit">Apply Filters</button>
    </form>

    <!-- Add New Task Form -->
    <h3>Add New Task</h3>
    <form method="POST" action="add_task.php">
        <label for="task_name">Task Name:</label>
        <input type="text" name="task_name" id="task_name" required>

        <label for="due_date">Due Date:</label>
        <input type="date" name="due_date" required>

        <label for="priority">Priority:</label>
        <select name="priority" required>
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
        </select>

        <button type="submit">Add Task</button>
    </form>

    <!-- Display Tasks -->
    <table border="1">
    <tr>
        <th>Task</th>
        <th>Due Date</th>
        <th>Priority</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php while ($task = $result->fetch_assoc()): ?>
    <tr>
        <td data-label="Task"><?php echo htmlspecialchars($task['name']); ?></td>
        <td data-label="Due Date"><?php echo $task['due_date']; ?></td>
        <td data-label="Priority"><?php echo $task['priority']; ?></td>
        <td data-label="Status"><?php echo $task['completed'] ? "Completed" : "Pending"; ?></td>
        <td data-label="Action">
            <a href="update_task.php?task_id=<?php echo $task['id']; ?>&status=<?php echo $task['completed'] ? 0 : 1; ?>">
                <?php echo $task['completed'] ? "Mark as Pending" : "Mark as Completed"; ?>
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
        </div>
</body>

<!-- System.out.print ("Hello World " + " :)")  --->
</html>