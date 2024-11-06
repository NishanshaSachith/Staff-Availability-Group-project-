<?php
session_start();

// Database connection
$servername = "localhost";
$username = "csc210user";
$password = "CSC210!";
$database = "group6";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if `id` parameter is set
if (isset($_GET['id'])) {
    $schedule_id = $_GET['id'];

    // Prepared statement to delete schedule
    $deleteQuery = "DELETE FROM staff_availability WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $schedule_id);

    if ($stmt->execute()) {
        header("Location: scheduleManagement.php?message=Schedule+deleted+successfully");
        exit();
    } else {
        echo "Error deleting schedule: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
