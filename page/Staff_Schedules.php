<?php
session_start();

// Database connection setup
$host = 'localhost';
$db = 'staff_availability_system';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

if (isset($_POST['sign_out'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Fetch schedule data
$scheduleQuery = "SELECT s.name, sa.start_time, sa.end_time FROM staff_availability sa JOIN staff s ON sa.staff_id = s.id";
$scheduleResult = $pdo->query($scheduleQuery);

// Handle form submission for booking appointments
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestedBy = $_POST['requested_by'];
    $staffMember = $_POST['staff_member'];
    $appointmentDate = $_POST['appointment_date'];
    $appointmentTime = $_POST['appointment_time'];
    $email = $_SESSION['login-email'];

    // Debug: Check if appointment time is being received
    echo "Time submitted: " . htmlspecialchars($appointmentTime) . "<br>";

    // Find the staff ID based on the name
    $staffQuery = $pdo->prepare("SELECT id FROM staff WHERE name = ?");
    $staffQuery->execute([$staffMember]);
    $staffId = $staffQuery->fetchColumn();

    // Find the student ID based on the session email
    $studentQuery = $pdo->prepare("SELECT id FROM users WHERE full_name = ?");
    $studentQuery->execute([$requestedBy]);
    $studentID = $studentQuery->fetchColumn();

    // Insert the appointment into the database
    $insertQuery = $pdo->prepare("INSERT INTO appointments (staff_id, student_id, appointment_date, appointment_time) VALUES (?, ?, ?, ?)");
    if (!$insertQuery->execute([$staffId, $studentID, $appointmentDate, $appointmentTime])) {
        // Debug: Show SQL errors
        print_r($insertQuery->errorInfo());
    } else {
        $bookingMessage = "Appointment booked successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Schedules</title>
    <link rel="stylesheet" href="../style/navbar.css">
    <link rel="stylesheet" href="../style/stylesStaff_Schedules1.css">
    <script defer src="../jscript/scriptStaff_Schedules.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl17CVkqkXNQ/ZH/XLlvwZoJyj7Yy7tcenmp01ypASozpmT/E0iptmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        .booking-message {
            margin-top: 10px;
            padding: 10px;
            background-color: #e0f7fa;
            color: #00796b;
            border: 1px solid #00796b;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <center>
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
                <form method="POST" style="display: inline;">
                    <button type="submit" name="sign_out" class="login-btn">Sign Out</button>
                </form>
            </div>
        </header>

        <section class="staff-schedules">
            <h2>Staff Availability Calendar (Today)</h2>
            <div class="calendar">
                <div class="calendar-body">
                    <?php if ($scheduleResult->rowCount() > 0): ?>
                        <table class="schedule-table" id="tal">
                            <tr>
                                <th>Staff</th>
                                <th>Available From</th>
                                <th>Available To</th>
                            </tr>
                            <?php while ($row = $scheduleResult->fetch()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['start_time']); ?></td>
                                    <td><?php echo htmlspecialchars($row['end_time']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </table>
                    <?php else: ?>
                        <p>No schedules available.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="booking-system">
                <h3>Book an Appointment</h3>
                <form id="bookingForm" action="" method="POST">
                    <label for="requestedBy">Requested By:</label>
                    <input type="text" id="requestedBy" name="requested_by" required placeholder="Your Name">

                    <label for="staffSelect">Select Staff Member:</label>
                    <select id="staffSelect" name="staff_member" required>
                        <?php
                        try {
                            $stmt = $pdo->query("SELECT name FROM staff");
                            while ($row = $stmt->fetch()) {
                                echo "<option value=\"" . htmlspecialchars($row['name']) . "\">" . htmlspecialchars($row['name']) . "</option>";
                            }
                        } catch (PDOException $e) {
                            echo "<option value=\"\">Unable to load staff members</option>";
                        }
                        ?>
                    </select>

                    <label for="dateSelect">Select Date:</label>
                    <input type="date" id="dateSelect" name="appointment_date" required>

                    <label for="timeSelect">Select Time:</label>
                    <input type="time" id="timeSelect" name="appointment_time" required>

                    <button type="submit" class="book-btn">Book Appointment</button>
                </form>
                <?php if (isset($bookingMessage)): ?>
                    <p class="booking-message"><?php echo htmlspecialchars($bookingMessage); ?></p>
                <?php endif; ?>
            </div>
        </section>
    </center>
    <footer>
        <p>&copy; 2024 Computer Science Department. All rights reserved.</p>
    </footer>
</body>
</html>
