<?php
session_start();

// Check if user is logged in
if (isset($_SESSION['welcome_message'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Handle Sign Out
if (isset($_POST['sign_out'])) {
    session_unset();
    session_destroy();
    header("Location: index.php"); // Redirect to login/registration page after sign out
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Availability System</title>
    <link rel="stylesheet" href="../style/navbar.css">
    <link rel="stylesheet" href="../style/styles.css">
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

    <!-- Hero Section -->
    <section id="home" class="intro">
        <div class="hero-section">
            <img src="../media/hero-image.jpg" alt="Department Building" class="hero-image">
            <div class="hero-text">
                <h2>Welcome to the Staff Availability System</h2>
                <p>Manage staff availability, appointments, and more for the Computer Science Department.</p>
                <button class="cta-btn">Learn More</button>
            </div>
        </div>
        <div class="updates">
            <h3>Latest Updates</h3>
            <ul id="notification-list">
                <?php
                // Include database connection
                include 'db.php'; // Ensure this path is correct based on your directory structure

                // Query to get latest news updates
                $sql = "SELECT title, content, created_at FROM news_updates ORDER BY created_at DESC LIMIT 5"; // Fetch the latest 5 updates
                $stmt = $pdo->query($sql);

                // Displaying news updates
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch()) {
                        echo "<li><strong>" . htmlspecialchars($row['title']) . ":</strong> " . htmlspecialchars($row['content']) . " <em>(" . $row['created_at'] . ")</em></li>";
                    }
                } else {
                    echo "<li>No updates available.</li>";
                }
                ?>
            </ul>
        </div>
        <!-- <button class="notify-btn">Clear Notifications</button> -->
    </section>

    <footer>
        <p>&copy; 2024 Computer Science Department. All rights reserved.</p>
    </footer>
</body>
</html>
