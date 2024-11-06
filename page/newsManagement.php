<?php
session_start(); // Start the session
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to the database
$servername = "localhost";
$username = "csc210user";
$password = "CSC210!";
$database = "group6";

$conn = new mysqli($servername, $username, $password, $database);
// Handle Sign Out
if (isset($_POST['sign_out'])) {
    session_unset();
    session_destroy();
    header("Location: index.php"); // Redirect to login/registration page after sign out
    exit();
}
// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for adding or editing a news update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save_news"])) {
    $news_title = $_POST["news_title"];
    $news_content = $_POST["news_content"];
    $news_id = isset($_POST["news_id"]) ? $_POST["news_id"] : null;

    if ($news_id) {
        // Update existing news
        $sql = "UPDATE news_updates SET title='$news_title', content='$news_content' WHERE id='$news_id'";
        $message = "News updated successfully!";
    } else {
        // Add new news
        $sql = "INSERT INTO news_updates (title, content) VALUES ('$news_title', '$news_content')";
        $message = "News added successfully!";
    }

    if ($conn->query($sql) !== TRUE) {
        $error = "Error: " . $conn->error;
    }
}

// Handle deletion of a news item
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM news_updates WHERE id='$delete_id'";
    if ($conn->query($sql) === TRUE) {
        $message = "News deleted successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Fetch all news from the database
$newsQuery = "SELECT * FROM news_updates ORDER BY created_at DESC";
$newsResult = $conn->query($newsQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Availability System</title>
    <link rel="stylesheet" href="../style/navbar.css">
    <link rel="stylesheet" href="../style/stylenewsManagement.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax//libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl17CVkqkXNQ/ZH/XLlvwZoJyj7Yy7tcenmp01ypASozpmT/E0iptmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>
<body>
<header class="header" id="header">
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
        <!-- Sign Out Button -->
        <form method="POST" style="display: inline;">
            <button type="submit" name="sign_out" class="login-btn">Sign Out</button>
        </form>
    </div>
</header>

<h2 class="section-title">News Management</h2>

<!-- Display success or error messages -->
<div class="message-container">
    <?php if (isset($message)) { echo "<p class='success'>$message</p>"; } ?>
    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
</div>

<!-- Add or edit news form -->
 <center>
<form method="POST" action="newsManagement.php" class="news-form">
    <h3><?php echo isset($_GET['edit_id']) ? "Edit News" : "Add News"; ?></h3>
    <?php
    if (isset($_GET['edit_id'])):
        $edit_id = $_GET['edit_id'];
        $editQuery = "SELECT * FROM news_updates WHERE id='$edit_id'";
        $editResult = $conn->query($editQuery);
        if ($editResult && $editResult->num_rows > 0) {
            $editData = $editResult->fetch_assoc();
    ?>
        <input type="hidden" name="news_id" value="<?php echo $editData['id']; ?>">
        <label for="news_title">Title:</label>
        <input type="text" id="news_title" name="news_title" value="<?php echo htmlspecialchars($editData['title']); ?>" required><br>
        <label for="news_content">Content:</label>
        <textarea id="news_content" name="news_content" required><?php echo htmlspecialchars($editData['content']); ?></textarea><br>
    <?php
        } else {
            echo "<p class='error'>Error: News item not found.</p>";
        }
    else:
    ?>
        <label for="news_title">Title:</label>
        <input type="text" id="news_title" name="news_title" required><br>
        <label for="news_content">Content:</label>
        <textarea id="news_content" name="news_content" required></textarea><br>
    <?php endif; ?>
    <button type="submit" name="save_news" class="submit-button"><?php echo isset($_GET['edit_id']) ? "Update" : "Add"; ?> News</button>
</form>

<!-- Display news list -->
<h3 class="existing-news-title">Existing News</h3>
<table class="news-table" border="1">
    <tr>
        <th>Title</th>
        <th>Content</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $newsResult->fetch_assoc()): ?>
    <tr>
        <td><?php echo htmlspecialchars($row['title']); ?></td>
        <td><?php echo htmlspecialchars($row['content']); ?></td>
        <td>
            <a class="edit-link" href="newsManagement.php?edit_id=<?php echo $row['id']; ?>">Edit</a> |
            <a class="delete-link" href="newsManagement.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this news?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table><center>
<footer>
    <p>&copy; 2024 Computer Science Department. All rights reserved.</p>
</footer>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
