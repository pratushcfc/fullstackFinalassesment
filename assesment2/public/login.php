<?php
session_start();

if ($_POST) {
    if ($_POST['username'] === "admin" && $_POST['password'] === "admin123") {
        $_SESSION['admin'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="center-box">
    <h2>Admin Login</h2>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button>Login</button>
    </form>
</div>

</body>
</html>
