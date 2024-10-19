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
    <title>Contact Us</title>
    <link rel="stylesheet" href="../style/navbar.css">
    <link rel="stylesheet" href="../style/stylesContact_Us_Page.css">
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
                    <li><a href="home1.php">Homepage</a></li>
                    <li><a href="Staff_Directory.php">Staff Directory</a></li>
                    <li><a href="Staff_Schedules.php">Staff Schedules</a></li>
                    <li><a href="Appointment_Management.php">Appointment Details</a></li>
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

    <section id="contact-us">
        <div class="container">
            <h2>Contact Us</h2>

            <div class="contact-info">
                <h3>General Contact Information</h3>
                <p>Email: <a href="dcs@univ.jfn.ac.lk">dcs@univ.jfn.ac.lk</a></p>
                <p>Phone: <a href="(0094) (0)21 221 8194">(0094) (0)21 221 8194</a></p>
                <p>Address: Head,<br>
                    Department of Computer Science,<br>
                    Faculty of Science,<br>
                    University of Jaffna,<br>
                    Jaffna,<br>
                    Sri Lanka.</p>
            </div>

            <div class="inquiry-form">
                <h3>Send Us an Inquiry</h3>
                <form id="inquiry-form" action="sendInquiry.php" method="POST">
                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="email">Your Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" required>

                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="5" required></textarea>

                    <button type="submit">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Computer Science Department. All rights reserved.</p>
    </footer>
</body>
</html>
