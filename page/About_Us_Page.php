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
// Database connection
$servername = "localhost"; // Update if your server is different
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "staff_availability_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch news updates
$newsUpdates = [];
$sql = "SELECT title, content, created_at FROM news_updates ORDER BY created_at DESC LIMIT 5";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $newsUpdates[] = $row;
    }
}

// Fetch staff members
$staffMembers = [];
$sql = "SELECT name, position, email FROM staff ORDER BY name";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $staffMembers[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Computer Science Department</title>
    <link rel="stylesheet" href="../style/navbar.css">
    <link rel="stylesheet" href="../style/stylesAbout_Us_Page.css">
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
    <section id="about">
        <div class="container">
            <h2>About the Computer Science Department</h2>

            <!-- Department Information -->
            <div class="section" id="department-info">
                <h3>Department Information</h3>
                <p>
                    Welcome to the Computer Science Department, where we are dedicated to fostering innovation,
                    excellence, and growth in the field of technology. Our mission is to equip students with the
                    skills and knowledge they need to become leaders in the tech industry.
                </p>
                <p>
                    With a team of experienced faculty and cutting-edge research facilities, we offer a wide range of 
                    programs, from undergraduate to graduate degrees, all focused on the latest trends and technologies 
                    in computing, software development, artificial intelligence, and cybersecurity.
                </p>
            </div>

            <!-- Mission Statement -->
            <div class="section" id="mission">
                <h3>Our Mission</h3>
                <p>
                    Our mission is to create a learning environment that inspires students to excel in their academic 
                    and professional careers. We aim to:
                </p>
                <ul>
                    <li>Promote critical thinking and problem-solving skills.</li>
                    <li>Encourage innovative research and practical applications of technology.</li>
                    <li>Foster collaboration with industry leaders to provide students with hands-on experience.</li>
                    <li>Support diversity and inclusion in the technology field.</li>
                </ul>
            </div>

            <!-- Key Faculty Members -->
            <div class="section" id="faculty">
                <h3>Key Faculty Members</h3>
                <div class="faculty-grid">
                    <?php foreach ($staffMembers as $staff): ?>
                    <div class="faculty-card">
                        <h4><?php echo htmlspecialchars($staff['name']); ?></h4>
                        <p><?php echo htmlspecialchars($staff['position']); ?></p>
                        <p>Email: <?php echo htmlspecialchars($staff['email']); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- News Updates -->
            <div class="section" id="news-updates">
                <h3>Latest News Updates</h3>
                <ul>
                    <?php foreach ($newsUpdates as $news): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($news['title']); ?></strong>
                        <p><?php echo htmlspecialchars($news['content']); ?></p>
                        <small>Posted on: <?php echo htmlspecialchars($news['created_at']); ?></small>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Team Information -->
            <div class="section" id="team">
                <h3>Meet Our Team</h3>
                <p>
                    This system is managed and maintained by a dedicated team of developers and administrative staff
                    committed to providing the best user experience.
                </p>
                <div class="team-grid">
                    <div class="team-card">
                        <img src="../media/bussiness-man.png" alt="nishansha">
                        <h4>Nishansha Sachith</h4>
                        <p>Lead Developer</p>
                    </div>
                    <div class="team-card">
                        <img src="../media/bussiness-man.png" alt="Mark Admin">
                        <h4>Mark Evans</h4>
                        <p>System Administrator</p>
                    </div>
                    <div class="team-card">
                        <img src="../media/bussiness-man.png" alt="Lisa Designer">
                        <h4>Lisa Kim</h4>
                        <p>UI/UX Designer</p>
                    </div>
                    <div class="team-card">
                        <img src="../media/girl.png" alt="nishansha">
                        <h4>Girl 1</h4>
                        <p>Lead Developer</p>
                    </div>
                    <div class="team-card">
                        <img src="../media/bussiness-man.png" alt="Mark Admin">
                        <h4>edx</h4>
                        <p>System Administrator</p>
                    </div>
                    <div class="team-card">
                        <img src="../media/girl.png" alt="Lisa Designer">
                        <h4>Girl 2</h4>
                        <p>UI/UX Designer</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Computer Science Department. All rights reserved.</p>
    </footer>
</body>
</html>
