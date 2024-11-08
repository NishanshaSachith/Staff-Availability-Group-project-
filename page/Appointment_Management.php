<?php
session_start();
include('database.php');
$n=$_SESSION['uniid'];
$query4 = "SELECT * FROM users WHERE email = '$n'";
   $status4 = mysqli_query($conn,$query4);
	$result4 = mysqli_fetch_assoc($status4);
    $user_name = $result4['full_name'];
    $_SESSION['uniname']=$user_name;
    
// Handle Sign Out
if (isset($_POST['sign_out'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

// Database connection
$servername = "localhost"; // Update if your server is different
$username = "csc210user"; // Replace with your database username
$password = "CSC210!"; // Replace with your database password
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
$n=$_SESSION['uniid'];
$query5= "SELECT * FROM users WHERE email = '$n'";
$status5 = mysqli_query($conn,$query5);
$result5 = mysqli_fetch_assoc($status5);
$user_id = $result5['id'];


$query1= "SELECT * FROM appointments WHERE student_id = '$user_id'";
$status1 = mysqli_query($conn,$query1);
//$result1 = mysqli_fetch_assoc($status1);

//fetch data
/*
$staff_id = $result1['staff_id'];
$appointment_date = $result1['appointment_date'];
$appintment_time = $result1['appointment_time'];
$status = $result1['status'];

//fetch staff name
$query2= "SELECT * FROM staff WHERE id = '$staff_id'";
$status2 = mysqli_query($conn,$query2);
$result2 = mysqli_fetch_assoc($status2);
$staff_name = $result2['name'];
$bookingHistory = array("appointment_date"=>$appointment_date,"appointment_time"=>$appintment_time,"staff_member"=>$staff_name,"status"=>$status);
*/
/*$sql = "SELECT a.appointment_date, a.appointment_time AS appointment_time, s.name AS staff_member, a.status 
        FROM appointments a 
        JOIN staff s ON a.staff_id = s.id"; 
        
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookingHistory[] = $row;
    }
}*/

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
            <a href="Home1.php"><img src="../media/department-logo.png" alt="Department Logo" /></a>
            </div>
            <h1>Staff Availability System</h1>
            <nav>
            <ul class="a" id="dropdown-content">
                    <li><a href="Home1.php">Home</a></li>
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
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="booking-history-list">
                        <?php while($result=mysqli_fetch_assoc($status1)): ?>
                            <tr>
                                
                                <td><?php echo htmlspecialchars($result['appointment_date']); ?></td>
                                <td><?php echo htmlspecialchars($result['appointment_time']); ?></td>
                                <td><?php echo htmlspecialchars($result['staff_name']); ?></td>
                                <td><?php echo htmlspecialchars($result['status']); ?></td>
                            </tr>
                        <?php endwhile; ?>
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
        <p>&copy; 2024 Department Of Computer Science. All rights reserved.</p>
    </footer>
</body>
</html>
