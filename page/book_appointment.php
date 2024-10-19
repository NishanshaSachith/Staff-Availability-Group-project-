<?php
// Database connection configuration
$host = 'localhost'; // Database host
$db = 'staff_availability_system'; // Use the correct database name
$user = 'root'; // Database username
$pass = ''; // Database password
$charset = 'utf8mb4'; // Character set for database connection

// Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// PDO options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Error handling
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch mode
    PDO::ATTR_EMULATE_PREPARES   => false, // Disable emulation of prepared statements
];

// Attempt to establish a connection to the database
try {
    $pdo = new PDO($dsn, $user, $pass, $options); // Create a PDO instance
} catch (PDOException $e) {
    // Handle connection errors
    die("Connection failed: " . $e->getMessage());
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $staff_member = $_POST['staff_member'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    // Prepare and execute the SQL statement
    try {
        $stmt = $pdo->prepare("INSERT INTO appointments (staff_id, appointment_date, status) VALUES ((SELECT id FROM staff WHERE name = ?), ?, 'Pending')");
        $stmt->execute([$staff_member, $appointment_date]);

        // Respond with a success message
        echo json_encode([
            'message' => "Your appointment with $staff_member on $appointment_date at $appointment_time has been booked!"
        ]);
    } catch (PDOException $e) {
        // Handle any SQL errors
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Availability System</title>
    <link rel="stylesheet" href="../style/navbar.css">
    <link rel="stylesheet" href="../style/styles.css"> <!-- Your CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax//libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl17CVkqkXNQ/ZH/XLlvwZoJyj7Yy7tcenmp01ypASozpmT/E0iptmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>
<body>
    <h1>Welcome to the Staff Availability System</h1>

    <form method="POST" id="appointmentForm">
        <label for="staff_member">Select Staff Member:</label>
        <select name="staff_member" required>
            <?php
            // Fetch staff members from the database
            $staffStmt = $pdo->query("SELECT name FROM staff");
            while ($row = $staffStmt->fetch()) {
                echo "<option value='" . htmlspecialchars($row['name']) . "'>" . htmlspecialchars($row['name']) . "</option>";
            }
            ?>
        </select>

        <label for="appointment_date">Appointment Date:</label>
        <input type="date" name="appointment_date" required>

        <label for="appointment_time">Appointment Time:</label>
        <input type="time" name="appointment_time" required>

        <button type="submit">Book Appointment</button>
    </form>

    <div id="response"></div>

    <script>
        const form = document.getElementById('appointmentForm');
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            const formData = new FormData(form);
            fetch('Home.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('response').innerText = data.message || data.error;
            })
            .catch(error => {
                document.getElementById('response').innerText = 'Error: ' + error;
            });
        });
    </script>
</body>
</html>
