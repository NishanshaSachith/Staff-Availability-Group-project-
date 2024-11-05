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
    header("Location: AppointmentManagement.php");
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
    header("Location: AppointmentManagement.php");
    exit();
}

// Handle Delete Action
if (isset($_POST['delete_appointment'])) {
    $appointmentId = $_POST['appointment_id'];
    $sql = "DELETE FROM appointments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $appointmentId);
    $stmt->execute();
    header("Location: AppointmentManagement.php");
    exit();
}

// Fetch booking history
$bookingHistory = [];
$sql = "SELECT a.id, a.appointment_date, a.appointment_time, s.name AS staff_member, a.status 
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
$sql = "SELECT a.id, a.appointment_date, a.appointment_time, s.name AS staff_member
        FROM appointments a
        JOIN staff s ON a.staff_id = s.id WHERE a.status = 'Pending'";

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
                <a href="Homeadmin.php"><img src="../media/department-logo.png" alt="Department Logo" /></a>
            </div>
            <h1>Staff Availability System</h1>
            <nav>
                <ul class="nav-list" id="dropdown-content">
                    <li><a href="homeadmin.php">Home</a></li>
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
    <section id="appointment-management">
        <div class="container">
            <h2>Manage Appointments</h2>

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
                            <th>Action</th>
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
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="appointment_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" name="delete_appointment" class="delete">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Appointment Requests -->
            <div class="section" id="appointment-requests">
                <h3>Appointment Requests</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Staff Member</th>
                            <th>Requested By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="appointment-requests-list">
                        <?php foreach ($appointmentRequests as $request): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($request['appointment_date']); ?></td>
                                <td><?php echo htmlspecialchars($request['appointment_time']); ?></td>
                                <td><?php echo htmlspecialchars($request['staff_member']); ?></td>
                                <td></td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="appointment_id" value="<?php echo $request['id']; ?>">
                                        <button type="submit" name="confirm_appointment" class="confirm">Confirm</button>
                                    </form>
                                    <button type="button" class="reschedule" onclick="showRescheduleModal(<?php echo $request['id']; ?>)">Reschedule</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Reschedule Modal -->
            <div id="rescheduleModal" class="modal" style="display:none;">
                <div class="modal-content">
                    <span class="close" onclick="closeRescheduleModal()">&times;</span>
                    <h2>Reschedule Appointment</h2>
                    <form method="POST">
                        <input type="hidden" name="appointment_id" id="rescheduleAppointmentId">
                        <label for="new_date">New Date:</label>
                        <input type="date" name="new_date" required><br>
                        <label for="new_time">New Time:</label>
                        <input type="time" name="new_time" required><br>
                        <button type="submit" name="reschedule_appointment">Reschedule</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Computer Science Department. All rights reserved.</p>
    </footer>

    <script>
        function showRescheduleModal(appointmentId) {
            const modal = document.getElementById("rescheduleModal");
            const appointmentIdInput = document.getElementById("rescheduleAppointmentId");
            appointmentIdInput.value = appointmentId;
            modal.style.display = "block";
        }

        function closeRescheduleModal() {
            const modal = document.getElementById("rescheduleModal");
            modal.style.display = "none";
        }

        // Close the modal if the user clicks outside of it
        window.onclick = function(event) {
            const modal = document.getElementById("rescheduleModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
