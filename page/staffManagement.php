<?php
session_start(); // Start the session
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$database = "staff_availability_system";
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for adding a staff member
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_staff"])) {
    $name = $_POST["name"];
    $position = $_POST["position"];
    $office_location = $_POST["office_location"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $office_hours = $_POST["office_hours"];

    $sql = "INSERT INTO staff (name, position, office_location, email, phone, office_hours) 
            VALUES ('$name', '$position', '$office_location', '$email', '$phone', '$office_hours')";

    if ($conn->query($sql) === TRUE) {
        $message = "Staff member added successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Handle form submission for editing a staff member
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["edit_staff"])) {
    $id = $_POST["staff_id"];
    $name = $_POST["name"];
    $position = $_POST["position"];
    $office_location = $_POST["office_location"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $office_hours = $_POST["office_hours"];

    $sql = "UPDATE staff 
            SET name='$name', position='$position', office_location='$office_location', 
                email='$email', phone='$phone', office_hours='$office_hours' 
            WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        $message = "Staff member updated successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Handle deletion of a staff member
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // First, delete any related entries in staff_availability
    $sql = "DELETE FROM staff_availability WHERE staff_id='$delete_id'";
    $conn->query($sql); // Execute delete from staff_availability

    // Now delete the staff member
    $sql = "DELETE FROM staff WHERE id='$delete_id'";
    if ($conn->query($sql) === TRUE) {
        $message = "Staff member deleted successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Fetch all staff members from the database
$staffQuery = "SELECT * FROM staff";
$staffResult = $conn->query($staffQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Availability System</title>
    <link rel="stylesheet" href="../style/navbar.css">
    <link rel="stylesheet" href="../style/stylestaffManagement.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl17CVkqkXNQ/ZH/XLlvwZoJyj7Yy7tcenmp01ypASozpmT/E0iptmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>
<body>
<header class="header" id="header">
        <div class="navbar">
            <div class="logo">
                <img src="../media/department-logo.png" alt="Department Logo" />
            </div>
            <h1>Staff Availability System</h1>
            <nav>
                <ul class="navbar-links" id="dropdown-content">
                    <li><a href="homeadmin.php">Homepage</a></li>
                    <li><a href="AppointmentManagement.php">Appointment Management</a></li>
                    <li><a href="userManagement.php">User Management</a></li>
                    <li><a href="staffManagement.php">Staff Management</a></li>
                    <li><a href="scheduleManagement.php">Schedule Management</a></li>
                    <li><a href="newsManagement.php">News Management</a></li>
                </ul>
            </nav>
            <a href="index.php" class="login-btn">Sign Out</a>
        </div>
</header>
<h2 id="staff-management-title">Staff Management</h2>

<!-- Display success or error messages -->
<?php if (isset($message)) { echo "<p class='success'>$message</p>"; } ?>
<?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

<!-- Add staff form -->
<center>
<form id="add-staff-form" method="POST" action="staffManagement.php">
    <h3>Add New Staff Member</h3>
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required><br>
    <label for="position">Position:</label>
    <input type="text" name="position" id="position" required><br>
    <label for="office_location">Office Location:</label>
    <input type="text" name="office_location" id="office_location"><br>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required><br>
    <label for="phone">Phone:</label>
    <input type="text" name="phone" id="phone"><br>
    <label for="office_hours">Office Hours:</label>
    <input type="text" name="office_hours" id="office_hours"><br>
    <button type="submit" name="add_staff" class="submit-btn">Add Staff</button>
</form>

<!-- Edit staff form -->
<?php if (isset($_GET['edit_id'])): 
    $edit_id = $_GET['edit_id'];
    $editQuery = "SELECT * FROM staff WHERE id='$edit_id'";
    $editResult = $conn->query($editQuery);
    $editData = $editResult->fetch_assoc();
?>
<form id="edit-staff-form" method="POST" action="staffManagement.php">
    <h3>Edit Staff Member</h3>
    <input type="hidden" name="staff_id" value="<?php echo $editData['id']; ?>">
    <label for="edit_name">Name:</label>
    <input type="text" name="name" id="edit_name" value="<?php echo $editData['name']; ?>" required><br>
    <label for="edit_position">Position:</label>
    <input type="text" name="position" id="edit_position" value="<?php echo $editData['position']; ?>" required><br>
    <label for="edit_office_location">Office Location:</label>
    <input type="text" name="office_location" id="edit_office_location" value="<?php echo $editData['office_location']; ?>"><br>
    <label for="edit_email">Email:</label>
    <input type="email" name="email" id="edit_email" value="<?php echo $editData['email']; ?>" required><br>
    <label for="edit_phone">Phone:</label>
    <input type="text" name="phone" id="edit_phone" value="<?php echo $editData['phone']; ?>"><br>
    <label for="edit_office_hours">Office Hours:</label>
    <input type="text" name="office_hours" id="edit_office_hours" value="<?php echo $editData['office_hours']; ?>"><br>
    <button type="submit" name="edit_staff" class="submit-btn">Update Staff</button>
</form>
<?php endif; ?>

<!-- Display staff list -->
<h3 id="existing-staff-title">Existing Staff Members</h3>
<table id="staff-list" border="1">
    <tr>
        <th>Name</th>
        <th>Position</th>
        <th>Office Location</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Office Hours</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $staffResult->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['position']; ?></td>
        <td><?php echo $row['office_location']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td><?php echo $row['phone']; ?></td>
        <td><?php echo $row['office_hours']; ?></td>
        <td>
            <a href="?edit_id=<?php echo $row['id']; ?>">Edit</a>
            <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this staff member?');">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
</center>

<?php
$conn->close(); // Close the database connection
?>
</body>
</html>
