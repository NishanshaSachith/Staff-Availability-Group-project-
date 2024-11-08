<?php
session_start();

// Database connection
$servername = "localhost";
$username = "csc210user";
$password = "CSC210!";
$database = "group6";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Handle Sign Out
if (isset($_POST['sign_out'])) {
    session_unset();
    session_destroy();
    header("Location: index.php"); // Redirect to login/registration page after sign out
    exit();
}
// Handle form submission for adding a new schedule
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_schedule"])) {
    $staff_id = $_POST["staff_id"];
    $start_time = $_POST["start_time"];
    $end_time = $_POST["end_time"];

    // Prepared statement to insert a new schedule
    $sql = "INSERT INTO staff_availability (staff_id, start_time, end_time) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $staff_id, $start_time, $end_time);

    if ($stmt->execute()) {
        $message = "New schedule added successfully!";
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle form submission for editing a schedule
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["edit_schedule"])) {
    $schedule_id = $_POST["schedule_id"];
    $staff_id = $_POST["staff_id"];
    $start_time = $_POST["start_time"];
    $end_time = $_POST["end_time"];

    // Prepared statement to update schedule
    $sql = "UPDATE staff_availability SET staff_id = ?, start_time = ?, end_time = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $staff_id, $start_time, $end_time, $schedule_id);

    if ($stmt->execute()) {
        $message = "Schedule updated successfully!";
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch all staff members for the dropdown list
$staffQuery = "SELECT id, name FROM staff";
$staffResult = $conn->query($staffQuery);

// Fetch all schedules
$scheduleQuery = "SELECT schedules.id, staff.name, schedules.start_time, schedules.end_time 
                  FROM staff_availability AS schedules
                  JOIN staff ON schedules.staff_id = staff.id";
$scheduleResult = $conn->query($scheduleQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Availability System</title>
    <link rel="stylesheet" href="../style/navbar.css">
    <link rel="stylesheet" href="../style/stylescheduleManagement.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax//libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl17CVkqkXNQ/ZH/XLlvwZoJyj7Yy7tcenmp01ypASozpmT/E0iptmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        /* Add your custom styles here */
        .success { color: green; }
        .error { color: red; }
        .schedule-table { width: 80%; margin: 20px auto; border-collapse: collapse; }
        .schedule-table th, .schedule-table td { border: 1px solid black; padding: 10px; text-align: center; }
        .edit-link, .delete-link { color: blue; text-decoration: underline; cursor: pointer; }
    </style>
</head>
<body>
<header class="header">
    <div class="navbar">
        <div class="logo">
        <a href="Homeadmin.php"><img src="../media/department-logo.png" alt="Department Logo" /></a>
        </div>
        <h1>Staff Availability System</h1>
        <nav>
        <ul class="nav-list" id="dropdown-content">
                    <li><a href="Homeadmin.php">Home</a></li>
                    <li><a href="AppointmentManagement.php">Appointment</a></li>
                    <li><a href="userManagement.php">User</a></li>
                    <li><a href="staffManagement.php">Staff</a></li>
                    <li><a href="scheduleManagement.php">Schedule</a></li>
                    <li><a href="newsManagement.php">News</a></li>
                </ul>
        </nav>
        <form method="POST" style="display: inline;">
                <button type="submit" name="sign_out" class="login-btn">Sign Out</button>
            </form>
    </div>
</header>
<center>
<h2>Schedule Management</h2>

<?php if (isset($message)) { echo "<p class='success'>$message</p>"; } ?>
<?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

<h3>Add New Schedule</h3>
<form method="POST" action="scheduleManagement.php" class="add-schedule-form">
    <label>Staff:</label>
    <select name="staff_id" required>
        <?php while ($staff = $staffResult->fetch_assoc()): ?>
            <option value="<?php echo htmlspecialchars($staff['id']); ?>">
                <?php echo htmlspecialchars($staff['name']); ?>
            </option>
        <?php endwhile; ?>
    </select>
    <label>Available From:</label>
    <input type="time" name="start_time" required>
    <label>Available To:</label>
    <input type="time" name="end_time" required>
    <button type="submit" name="add_schedule">Add Schedule</button>
</form>

<?php if (isset($_GET['edit_id'])):
    $edit_id = $_GET['edit_id'];
    $editQuery = "SELECT * FROM staff_availability WHERE id=?";
    $editStmt = $conn->prepare($editQuery);
    $editStmt->bind_param("i", $edit_id);
    $editStmt->execute();
    $editResult = $editStmt->get_result();
    $editData = $editResult->fetch_assoc();
    $staffResult = $conn->query($staffQuery);
?>

<form method="POST" action="scheduleManagement.php" class="edit-schedule-form">
    <h3>Edit Schedule</h3>
    <input type="hidden" name="schedule_id" value="<?php echo htmlspecialchars($editData['id']); ?>">
    <label>Staff:</label>
    <select name="staff_id" required>
        <?php while ($staff = $staffResult->fetch_assoc()): ?>
            <option value="<?php echo htmlspecialchars($staff['id']); ?>" <?php if ($editData['staff_id'] == $staff['id']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($staff['name']); ?>
            </option>
        <?php endwhile; ?>
    </select>
    <label>Available From:</label>
    <input type="time" name="start_time" value="<?php echo htmlspecialchars($editData['start_time']); ?>" required>
    <label>Available To:</label>
    <input type="time" name="end_time" value="<?php echo htmlspecialchars($editData['end_time']); ?>" required>
    <button type="submit" name="edit_schedule">Update Schedule</button>
</form>
<?php endif; ?>

<h3>Staff Schedules</h3>
<?php if ($scheduleResult && $scheduleResult->num_rows > 0): ?>
<table class="schedule-table" id="tal">
    <tr>
        <th>Staff</th>
        <th>Available From</th>
        <th>Available To</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $scheduleResult->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['start_time']; ?></td>
            <td><?php echo $row['end_time']; ?></td>
            <td>
                <a href="scheduleManagement.php?edit_id=<?php echo $row['id']; ?>" class="edit-link">Edit</a>
                <a href="delete_schedule.php?id=<?php echo $row['id']; ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this schedule?');">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
    <p>No schedules found.</p>
<?php endif; ?>

</center>

<footer>
    <p>&copy; 2024 Department Of Computer Science. All rights reserved.</p>
</footer>
</body>
</html>

<?php
$conn->close();
?>
