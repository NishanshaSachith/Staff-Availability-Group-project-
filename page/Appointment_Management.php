<?php
session_start();
// Handle Sign Out
if (isset($_POST['sign_out'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root"; // Change this to your DB username
$password = ""; // Change this to your DB password
$dbname = "group6";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Confirm Action
if (isset($_POST['confirm_appointment'])) {
    $appointmentId = $_POST['appointment_id'];
    $sql = "UPDATE appointments SET status = 'Confirmed' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $appointmentId);
    $stmt->execute();
    header("Location: Appointment_Management.php");
    exit();
}

// Handle Reschedule Action
if (isset($_POST['reschedule_appointment'])) {
    $appointmentId = $_POST['appointment_id'];
    $newDate = $_POST['new_date'];
    $newTime = $_POST['new_time'];
    $sql = "UPDATE appointments SET appointment_date = ?, appointment_time = ?, status = 'Rescheduled' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $newDate, $newTime, $appointmentId);
    $stmt->execute();

    // Redirect to refresh the page and reflect changes
    header("Location: Appointment_Management.php");
    exit();
}

// Fetch booking history
$bookingHistory = [];
$sql = "SELECT a.appointment_date, a.appointment_time AS appointment_time, s.name AS staff_member, a.status 
        FROM appointments a 
        JOIN staff s ON a.staff_id = s.id"; 
        
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookingHistory[] = $row;
    }
}

// Fetch appointment requests
$appointmentRequests = [];
$sql = "SELECT a.id, a.appointment_date, a.appointment_time AS appointment_time
        FROM appointments a 
        JOIN users u ON a.student_id = u.id";

$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointmentRequests[] = $row;
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Management</title>
    <link rel="stylesheet" href="../style/navbar.css">
    <link rel="stylesheet" href="../style/stylesAppointment_Management.css">
    <script defer src="../jscript/scriptAppointment_Management.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl17CVkqkXNQ/ZH/XLlvwZoJyj7Yy7tcenmp01ypASozpmT/E0iptmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>
<body>
    <header class="header" id="header">
        <div class="navbar">
            <div class="logo">
            <a href="home1.php"><img src="../media/department-logo.png" alt="Department Logo" /></a>
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
    <section id="appointment-management">
        <div class="container">
            <h2>Appointments Details</h2>

            <!-- Appointment Booking History -->
            <div class="section" id="booking-history">
                <h3>Booking History</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Staff Member</th>
                            <th>Requested By</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="booking-history-list">
                        <?php foreach ($bookingHistory as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['appointment_date']); ?></td>
                                <td><?php echo htmlspecialchars($item['appointment_time']); ?></td>
                                <td><?php echo htmlspecialchars($item['staff_member']); ?></td>
                                <td></td>
                                <td><?php echo htmlspecialchars($item['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        
            <!-- Notifications -->
            <!-- <div class="section" id="notifications"> -->
                <!-- <h3>Notifications</h3> -->
                <!-- <ul id="notification-list"> -->
                    <!-- Notifications will be dynamically loaded here -->
                <!-- </ul>/ -->
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Computer Science Department. All rights reserved.</p>
    </footer>
</body>
</html>
