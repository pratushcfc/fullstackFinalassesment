<?php

// Include database connection file
include("../config/db.php");

// Include header for CSRF functions
include("../includes/header.php");

// Check if form is submitted and verify CSRF token
if ($_POST) {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        die("Security token validation failed. Please try again.");
    }

    // Server-side validation
    
    // Validate student name (only letters and spaces)
    if (!preg_match("/^[A-Za-z\s]+$/", $_POST['name'])) {
        die("Error: Student name should only contain letters and spaces.");
    }

    // Validate marks are not more than 100
    if ($_POST['english'] > 100 || $_POST['english'] < 0) {
        die("Error: English marks must be between 0 and 100.");
    }
    if ($_POST['math'] > 100 || $_POST['math'] < 0) {
        die("Error: Math marks must be between 0 and 100.");
    }
    if ($_POST['science'] > 100 || $_POST['science'] < 0) {
        die("Error: Science marks must be between 0 and 100.");
    }
    if ($_POST['nepali'] > 100 || $_POST['nepali'] < 0) {
        die("Error: Nepali marks must be between 0 and 100.");
    }
    if ($_POST['social'] > 100 || $_POST['social'] < 0) {
        die("Error: Social marks must be between 0 and 100.");
    }

    // Validate attendance is not more than 100
    if ($_POST['attendance'] > 100 || $_POST['attendance'] < 0) {
        die("Error: Attendance must be between 0 and 100.");
    }
}

// Prepare INSERT query to add new student into database
$stmt = $conn->prepare(
    "INSERT INTO students 
    (name, class, english, math, science, nepali, social, attendance)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
);

// Bind form values to the query
// s = string (for name)
// i = integer (for numbers)
$stmt->bind_param(
    "siiiiiii",
    $_POST['name'],        // Student name
    $_POST['class'],       // Student grade/class
    $_POST['english'],     // English marks
    $_POST['math'],        // Math marks
    $_POST['science'],     // Science marks
    $_POST['nepali'],      // Nepali marks
    $_POST['social'],      // Social marks
    $_POST['attendance']   // Attendance percentage
);

// Execute the insert query
$stmt->execute();

// After inserting, redirect back to main page
header("Location: index.php");

// Stop further execution
exit();

?>
