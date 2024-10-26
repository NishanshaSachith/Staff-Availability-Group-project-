<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['welcome_message'])) {
    // Redirect to login page if not logged in
    header("Location: index.php");
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
    <title>Staff Directory</title>
    <link rel="stylesheet" href="../style/navbar.css">
    <link rel="stylesheet" href="../style/stylesStaff_Directory1.css">
    <script defer src="../jscript/scriptStaff_Directory.js"></script>
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
            <h1>Staff Availability System</h1>
            <nav>
            <ul class="a" id="dropdown-content">
                    <li><a href="home1.php">Home</a></li>
                    <li><a href="Staff_Directory.php">Staff</a></li>
                    <li><a href="Staff_Schedules.php">Schedules</a></li>
                    <li><a href="Appointment_Management.php">Appointment</a></li>
                    <li><a href="About_Us_Page.php">About</a></li>
                    <li><a href="Contact_Us_Page.php">Contact Us</a></li>
                </ul>
            </nav>
              <!-- Sign Out Button -->
              <form method="POST" style="display: inline;">
                <button type="submit" name="sign_out" class="login-btn">Sign Out</button>
            </form>
        </div>
    </header>

    <section class="staff-directory">
        <center>
            <h2>Staff Directory</h2>
            <input type="text" id="searchInput" placeholder="Search by name or position...">
            <div class="filters">
                <label for="categorySelect">Filter by category:</label>
                <select id="categorySelect">
                    <option value="">Select One</option>
                    <option value="Lecturer">Lecturers</option>
                    <option value="Academic Support Staff">Academic Staff</option>
                    <option value="Administrative Staff">Administrative Staff</option>
                </select>
            </div>
        </center>
        
        <div class="staff-list" id="staffList">
            <?php
            include 'db.php'; // Include the database connection file

            // Fetch staff data from the database
            $stmt = $pdo->query("SELECT * FROM staff");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Display staff profile information
                echo '<div class="staff-profile" data-category="' . htmlspecialchars(strtolower($row['position'])) . '">';
                echo '<img src="../media/user.png" alt="' . htmlspecialchars($row['name']) . '" class="staff-photo">';
                echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                echo '<p>Position: ' . htmlspecialchars($row['position']) . '</p>';
                echo '<p>Office Location: ' . htmlspecialchars($row['office_location']) . '</p>';
                echo '<p>Email: ' . htmlspecialchars($row['email']) . '</p>';
                echo '<p>Phone: ' . htmlspecialchars($row['phone']) . '</p>';
                echo '<p>Office Hours: ' . htmlspecialchars($row['office_hours']) . '</p>';
                echo '</div>';
            }
            ?>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Computer Science Department. All rights reserved.</p>
    </footer>
    
    <script defer src="scriptStaff_Directory.js"></script>
</body>
</html>
