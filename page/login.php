<?php
session_start();

// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (isset($_SESSION['welcome_message'])) {
    // Make sure $role is set, probably from session or database
    if (isset($_SESSION['role'])) {
        $role = $_SESSION['role'];
    } else {
        $role = ''; // Handle cases where role is not set
    }

    if ($role === 'staff') {
        header("Location: Homeadmin.php");
    } else {
        header("Location: home1.php");
    }
    exit();
}

// Include database connection
include 'db.php'; // Ensure this path is correct based on your directory structure

// Handle Registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $name = trim($_POST['register-name']);
    $email = trim($_POST['register-email']);
    $password = password_hash($_POST['register-password'], PASSWORD_BCRYPT);
    $role = $_POST['register-role'];
    $position = isset($_POST['register-position']) ? $_POST['register-position'] : null;

    // Validate inputs
    if (empty($name) || empty($email) || empty($role)) {
        echo "<script>alert('Please fill in all required fields.');</script>";
    } else {
        // Check if the email already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            echo "<script>alert('Email already exists!');</script>";
        } else {
            // Prepare and execute registration query
            $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$name, $email, $password, $role])) {
                // Get the last inserted user ID
                $userId = $pdo->lastInsertId();

                // If user is staff, insert into staff table
                if ($role === 'staff' && $position) {
                    $stmt = $pdo->prepare("INSERT INTO staff (name, position, email) VALUES (?, ?, ?)");
                    $stmt->execute([$name, $position, $email]);
                }
                
                // If user is student, insert into student table
                if ($role === 'student') {
                    $stmt = $pdo->prepare("INSERT INTO students (name, email, user_id) VALUES (?, ?, ?)");
                    $stmt->execute([$name, $email, $userId]); // Insert the user ID
                }

                // Redirect to home page with a welcome message
                $_SESSION['welcome_message'] = "Welcome, $name! You have successfully registered.";
                header("Location: login.php");
                exit();
            } else {
                echo "<script>alert('Registration failed!');</script>";
                error_log("Registration failed for email: $email");
            }
        }
    }
}

// Handle Staff Login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['admin_login'])) {
    $email = trim($_POST['admin-email']);
    $password = $_POST['admin-password'];

    // Check if admin credentials are correct against the users table
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'staff'");
    $stmt->execute([$email]);
    $staff = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($staff && password_verify($password, $staff['password'])) {
        $_SESSION['admin'] = true; // Set a session variable for admin access
        header("Location: Homeadmin.php");
        exit();
    } else {
        echo "<script>alert('Invalid admin email or password!');</script>";
    }
}

// Handle User Login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['login-email']);
    $password = $_POST['login-password'];

    // Prepare and execute login query for students
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'student'");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Login successful
        $_SESSION['welcome_message'] = "Login successful! Welcome, " . htmlspecialchars($user['full_name']) . ".";
        header("Location: home1.php");
        exit();
    } else {
        echo "<script>alert('Invalid email or password!');</script>";
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Registration - Staff Availability System</title>
    <link rel="stylesheet" href="../style/navbar.css">
    <link rel="stylesheet" href="../style/login-style.css">
    <script defer src="login-script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl17CVkqkXNQ/ZH/XLlvwZoJyj7Yy7tcenmp01ypASozpmT/E0iptmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>
<body>
    <header class="header">
        <div class="navbar">
            <div class="logo">
            <a href="index.php"><img src="../media/department-logo.png" alt="Department Logo" /></a>
            </div>
            <h1>Staff Availability System</h1>
        </div>
    </header>

    <section class="auth-section">
        <div class="auth-container">
            <div class="auth-tabs">
                <button id="loginTab" class="tab active" onclick="showLogin()">Student Login</button>
                <button id="registerTab" class="tab" onclick="showRegister()">Register</button>
                <button id="adminLoginTab" class="tab" onclick="showAdminLogin()">Staff Login</button>
            </div>
            <div class="auth-forms">
                <!-- Registration Form -->
                <form id="registerForm" class="auth-form hidden" method="POST">
                    <h2>Registration</h2>
                    <div class="input-group">
                        <label for="register-name">Full Name</label>
                        <input type="text" name="register-name" id="register-name" required>
                    </div>
                    <div class="input-group">
                        <label for="register-email">Email</label>
                        <input type="email" name="register-email" id="register-email" required>
                    </div>
                    <div class="input-group">
                        <label for="register-password">Password</label>
                        <input type="password" name="register-password" id="register-password" required>
                    </div>
                    <div class="input-group">
                        <label for="register-role">Role</label>
                        <select name="register-role" id="register-role" required>
                            <option value="">Select One</option>
                            <option value="staff">Staff</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                    <div class="input-group" id="position-group" style="display:none;">
                        <label for="register-position">Position</label>
                        <select name="register-position" id="register-position">
                            <option value="Lecturer">Lecturer</option>
                            <option value="Academic Support Staff">Academic Support Staff</option>
                            <option value="Administrative Staff">Administrative Staff</option>
                        </select>
                    </div>
                    <button type="submit" name="register" class="auth-btn">Register</button>
                </form>

                <!-- Login Form -->
                <form id="loginForm" class="auth-form" method="POST">
                    <h2>Student Login</h2>
                    <div class="input-group">
                        <label for="login-email">Email</label>
                        <input type="email" name="login-email" id="login-email" required>
                    </div>
                    <div class="input-group">
                        <label for="login-password">Password</label>
                        <input type="password" name="login-password" id="login-password" required>
                    </div>
                    <button type="submit" name="login" class="auth-btn">Login</button>
                </form>

                <!-- Staff Login Form -->
                <form id="adminLoginForm" class="auth-form hidden" method="POST">
                    <h2>Staff Login</h2>
                    <div class="input-group">
                        <label for="admin-email">Email</label>
                        <input type="email" name="admin-email" id="admin-email" required>
                    </div>
                    <div class="input-group">
                        <label for="admin-password">Password</label>
                        <input type="password" name="admin-password" id="admin-password" required>
                    </div>
                    <button type="submit" name="admin_login" class="auth-btn">Staff Login</button>
                </form>
            </div>
        </div>
    </section>
    <footer>
        <p>&copy; 2024 Computer Science Department. All rights reserved.</p>
    </footer>

    <script>
        // Function to show the login form and hide others
        function showLogin() {
            document.getElementById('loginForm').classList.remove('hidden');
            document.getElementById('registerForm').classList.add('hidden');
            document.getElementById('adminLoginForm').classList.add('hidden');
            setActiveTab('loginTab');
        }

        // Function to show the registration form and hide others
        function showRegister() {
            document.getElementById('loginForm').classList.add('hidden');
            document.getElementById('registerForm').classList.remove('hidden');
            document.getElementById('adminLoginForm').classList.add('hidden');
            setActiveTab('registerTab');
        }

        // Function to show the staff login form and hide others
        function showAdminLogin() {
            document.getElementById('loginForm').classList.add('hidden');
            document.getElementById('registerForm').classList.add('hidden');
            document.getElementById('adminLoginForm').classList.remove('hidden');
            setActiveTab('adminLoginTab');
        }

        // Function to set the active tab styling
        function setActiveTab(tabId) {
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });
            document.getElementById(tabId).classList.add('active');
        }

        // Show/hide position selection based on role
        document.getElementById('register-role').addEventListener('change', function() {
            const positionGroup = document.getElementById('position-group');
            positionGroup.style.display = this.value === 'staff' ? 'block' : 'none';
        });
    </script>
</body>
</html>
