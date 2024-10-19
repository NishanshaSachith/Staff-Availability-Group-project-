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

// Handle Sign Out
if (isset($_POST['sign_out'])) {
    session_unset();
    session_destroy();
    header("Location: index.php"); // Redirect to login/registration page after sign out
    exit();
}

// Handle form submission for adding a user
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_user"])) {
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password for security
    $role = $_POST["role"]; // 'student' or 'staff'

    $sql = "INSERT INTO users (full_name, email, password, role) 
            VALUES ('$full_name', '$email', '$password', '$role')";

    if ($conn->query($sql) === TRUE) {
        $message = "User added successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Handle form submission for editing a user
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["edit_user"])) {
    $id = $_POST["user_id"];
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $role = $_POST["role"];

    $sql = "UPDATE users 
            SET full_name='$full_name', email='$email', role='$role' 
            WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        $message = "User updated successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Handle deletion of a user
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $sql = "DELETE FROM users WHERE id='$delete_id'";
    if ($conn->query($sql) === TRUE) {
        $message = "User deleted successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Fetch all users from the database
$userQuery = "SELECT * FROM users";
$userResult = $conn->query($userQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Availability System</title>
    <link rel="stylesheet" href="../style/navbar.css">
    <link rel="stylesheet" href="../style/styleuserManagement.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax//libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl17CVkqkXNQ/ZH/XLlvwZoJyj7Yy7tcenmp01ypASozpmT/E0iptmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>
<body>
<header class="header" id="header">
    <div class="navbar">
        <div class="logo">
            <img src="../media/department-logo.png" alt="Department Logo" />
        </div>
        <h1>Staff Availability System(Admin)</h1>
        <nav>
            <ul class="a" id="dropdown-content">
                <li><a href="homeadmin.php">Homepage</a></li>
                <li><a href="AppointmentManagement.php">Appointment Management</a></li>
                <li><a href="userManagement.php">User Management</a></li>
                <li><a href="staffManagement.php">Staff Management</a></li>
                <li><a href="scheduleManagement.php">Schedule Management</a></li>
                <li><a href="newsManagement.php">News Management</a></li>
            </ul>
        </nav>
        <form method="POST" action="userManagement.php" style="display: inline;">
            <button type="submit" name="sign_out" class="login-btn">Sign Out</button>
        </form>
    </div>
</header>

<h2>User Management</h2>

<?php if (isset($message)) { echo "<p class='success'>$message</p>"; } ?>
<?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

<form method="POST" action="userManagement.php" class="user-form">
    <h3>Add New User</h3>
    <label for="full_name">Username:</label>
    <input type="text" name="full_name" required id="full_name"><br>
    <label for="email">Email:</label>
    <input type="email" name="email" required id="email"><br>
    <label for="password">Password:</label>
    <input type="password" name="password" required id="password"><br>
    <label for="role">Role:</label>
    <select name="role" required id="role">
        <option value="student">Student</option>
        <option value="staff">Staff</option>
    </select><br>
    <button type="submit" name="add_user">Add User</button>
</form>

<?php if (isset($_GET['edit_id'])): 
    $edit_id = $_GET['edit_id'];
    $editQuery = "SELECT * FROM users WHERE id='$edit_id'";
    $editResult = $conn->query($editQuery);
    $editData = $editResult->fetch_assoc();
?>
<form method="POST" action="userManagement.php" class="user-form">
    <h3>Edit User</h3>
    <input type="hidden" name="user_id" value="<?php echo $editData['id']; ?>">
    <label for="edit_username">Username:</label>
    <input type="text" name="full_name" value="<?php echo $editData['full_name']; ?>" required id="edit_username"><br>
    <label for="edit_email">Email:</label>
    <input type="email" name="email" value="<?php echo $editData['email']; ?>" required id="edit_email"><br>
    <label for="edit_role">Role:</label>
    <select name="role" required id="edit_role">
        <option value="student" <?php if ($editData['role'] == 'student') echo 'selected'; ?>>Student</option>
        <option value="staff" <?php if ($editData['role'] == 'staff') echo 'selected'; ?>>Staff</option>
    </select><br>
    <button type="submit" name="edit_user">Update User</button>
</form>
<?php endif; ?>

<h3><b>Existing Users</b></h3>
<table>
    <tr>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $userResult->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['full_name']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td><?php echo ucfirst($row['role']); ?></td>
        <td>
            <a href="userManagement.php?edit_id=<?php echo $row['id']; ?>">Edit</a> |
            <a href="userManagement.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<footer>
    <p>&copy; 2024 Computer Science Department. All rights reserved.</p>
</footer>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
