<?php
// Start session to store login information
session_start();

// Function to generate CSRF token
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Function to verify CSRF token
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Check if form is submitted
if ($_POST) {

    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $error = "Security token validation failed. Please try again.";
    } else {
        // Store username from form
        $username = $_POST['username'];

        // Store password from form
        $password = $_POST['password'];

    // Check if password length is at least 4 characters
    if (strlen($password) < 4) {

        // Show error if too short
        $error = "Password must be at least 4 characters long.";

    } else {

        // Variable to check if password contains a number
        $hasNumber = false;

        // Loop through each character of password
        for ($i = 0; $i < strlen($password); $i++) {

            // If current character is numeric
            if (is_numeric($password[$i])) {
                $hasNumber = true;
                break; // Stop loop once number is found
            }
        }

        // If password does not contain any number
        if (!$hasNumber) {

            $error = "Password must contain at least one number.";

        } else {

            // Check if username and password match admin credentials
            if ($username === "admin" && $password === "admin123") {

                // Store session variable
                $_SESSION['admin'] = true;

                // Redirect to main page
                header("Location: index.php");

                // Stop execution
                exit;

            } else {

                // If credentials are incorrect
                $error = "Invalid username or password";
            }
        }
    }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Page title -->
    <title>Admin Login - Student Record Management System</title>

    <!-- Link CSS file -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="login-body">

<!-- Login container -->
<div class="login-container">
    <div class="login-box">
        <!-- Heading -->
        <h1>Student Record Management System</h1>
        <h2>Admin Login</h2>

        <?php
        // Show error message if exists
        if (isset($error)) {
            echo "<div class='error-message'>$error</div>";
        }
        ?>

        <!-- Login form -->
        <form method="POST">

            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

            <!-- Username input -->
            <input type="text" name="username" placeholder="Username" required>

            <!-- Password input -->
            <input type="password" name="password" placeholder="Password" required>

            <!-- Login button -->
            <button type="submit" class="login-btn">Login</button>

        </form>
    </div>
</div>

</body>
</html>
