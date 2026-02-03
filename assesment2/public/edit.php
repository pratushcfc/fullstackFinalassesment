<?php
// Include database connection file
include("../config/db.php");

// Include header layout
include("../includes/header.php");

// Check if form is submitted (when Update button is clicked)
if($_POST){

    // Verify CSRF token
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

    // Prepare UPDATE query to modify student data
    $stmt = $conn->prepare(
        "UPDATE students 
        SET name=?, class=?, english=?, math=?, science=?, nepali=?, social=?, attendance=? 
        WHERE id=?"
    );

    // Bind form values to query parameters
    // s = string
    // i = integer
    $stmt->bind_param(
        "siiiiiiii",
        $_POST['name'],        // Student name
        $_POST['class'],       // Student class
        $_POST['english'],     // English marks
        $_POST['math'],        // Math marks
        $_POST['science'],     // Science marks
        $_POST['nepali'],      // Nepali marks
        $_POST['social'],      // Social marks
        $_POST['attendance'],  // Attendance
        $_POST['id']           // Student ID (for WHERE condition)
    );

    // Execute the update query
    $stmt->execute();

    // Redirect back to main page after update
    header("Location: index.php");

    // Stop further execution
    exit();
}

// Get student data using ID from URL with prepared statement (prevents SQL injection)
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
} else {
    header("Location: index.php");
    exit();
}
?>

<!-- Page title -->
<h3>Edit Student Information</h3>

<!-- Edit form -->
<form method="POST">

<!-- CSRF Token -->
<input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

<!-- Hidden field to store student ID -->
<input type="hidden" name="id" value="<?= htmlspecialchars($data['id'], ENT_QUOTES, 'UTF-8') ?>">

<!-- Name field (only letters and spaces) with XSS protection -->
<label>Name</label>
<input type="text" name="name" value="<?= htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8') ?>" pattern="[A-Za-z\s]+" title="Name should only contain letters and spaces" maxlength="100" required>

<!-- Class dropdown -->
<label>Class</label>
<select name="class" required>

<?php
// Generate grade options from 1 to 10
for($i=1;$i<=10;$i++){

    // If current grade matches student's grade, select it
    $sel = ($data['class']==$i) ? "selected" : "";

    // Print option
    echo "<option value='$i' $sel>Grade $i</option>";
}
?>

</select>

<!-- English marks (0-100) -->
<label>English</label>
<input type="number" name="english" value="<?= $data['english'] ?>" min="0" max="100" required>

<!-- Math marks (0-100) -->
<label>Math</label>
<input type="number" name="math" value="<?= htmlspecialchars($data['math'], ENT_QUOTES, 'UTF-8') ?>" min="0" max="100" required>

<!-- Science marks (0-100) -->
<label>Science</label>
<input type="number" name="science" value="<?= htmlspecialchars($data['science'], ENT_QUOTES, 'UTF-8') ?>" min="0" max="100" required>

<!-- Nepali marks (0-100) -->
<label>Nepali</label>
<input type="number" name="nepali" value="<?= htmlspecialchars($data['nepali'], ENT_QUOTES, 'UTF-8') ?>" min="0" max="100" required>

<!-- Social marks (0-100) -->
<label>Social</label>
<input type="number" name="social" value="<?= htmlspecialchars($data['social'], ENT_QUOTES, 'UTF-8') ?>" min="0" max="100" required>

<!-- Attendance (0-100) -->
<label>Attendance</label>
<input type="number" name="attendance" value="<?= htmlspecialchars($data['attendance'], ENT_QUOTES, 'UTF-8') ?>" min="0" max="100" required>

<!-- Submit button -->
<button type="submit">Update Student Record</button>

</form>

<?php
// Include footer layout
include("../includes/footer.php");
?>
