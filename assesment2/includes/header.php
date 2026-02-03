<?php
// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Record Management System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<!-- HEADER -->
<div class="header">
    <h2>Student Record Management System</h2>

    <?php if(isset($_SESSION['admin'])): ?>
        <a href="logout.php" class="logout-btn">Logout</a>
    <?php endif; ?>
</div>
