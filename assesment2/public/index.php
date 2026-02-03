<?php
// Start session to access login information
session_start();

// Temporary: enable error display for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    // Stop further execution
    exit;
}

// Include database connection file
include("../config/db.php");

// Include header file (top layout and navigation)
include("../includes/header.php");

// Function to calculate grade from marks
function getGrade($marks) {
    // If marks are 80 or more, return grade A
    if ($marks >= 80) return "A";

    // If marks are 40 or more, return grade B
    if ($marks >= 40) return "B";

    // If marks are below 40, return grade F
    return "F";
}
?>

<!-- Main container starts -->
<div class="container">

<!-- Box for Add Student form -->
<div class="box">

<!-- Title of form -->
<h3>Add Student</h3>

<!-- Form starts and sends data to add.php using POST -->
<form action="add.php" method="POST">

<!-- CSRF Token -->
<input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

<!-- Input for student name (only letters and spaces) -->
<input type="text" name="name" placeholder="Student Name" pattern="[A-Za-z\s]+" title="Name should only contain letters and spaces" maxlength="100" required>

<!-- Dropdown to select grade -->
<select name="class" required>

<!-- Default empty option -->
<option value="">Select Grade</option>

<?php
// Loop to create grade options from 1 to 10
for($i=1;$i<=10;$i++) 
    echo "<option>$i</option>";
?>

</select>

<!-- Input for English marks (0-100) -->
<input type="number" name="english" placeholder="English Marks" min="0" max="100" required>

<!-- Input for Math marks (0-100) -->
<input type="number" name="math" placeholder="Math Marks" min="0" max="100" required>

<!-- Input for Science marks (0-100) -->
<input type="number" name="science" placeholder="Science Marks" min="0" max="100" required>

<!-- Input for Nepali marks (0-100) -->
<input type="number" name="nepali" placeholder="Nepali Marks" min="0" max="100" required>

<!-- Input for Social marks (0-100) -->
<input type="number" name="social" placeholder="Social Marks" min="0" max="100" required>

<!-- Input for Attendance percentage (0-100) -->
<input type="number" name="attendance" placeholder="Attendance %" min="0" max="100" required>

<!-- Submit button -->
<button type="submit">Add Student</button>

</form>
<!-- Form ends -->

</div>
<!-- Add student box ends -->


<!-- Box for Student Records -->
<div class="box">

<!-- Table title -->
<h3>Student Records</h3>

<!-- Advanced Search Input -->
<input type="text" id="search" placeholder="Search student by name..." style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">

<!-- Table starts -->
<table>

<!-- Table header -->
<thead>
<tr>
<th>Name</th>
<th>Grade</th>
<th>English</th>
<th>Math</th>
<th>Science</th>
<th>Nepali</th>
<th>Social</th>
<th>Attendance</th>
<th>Average</th>
<th>Remarks</th>
<th>Action</th>
</tr>
</thead>

<!-- Table body (AJAX search replaces this part) -->
<tbody id="result">

<?php
// Get all students from database
$res = $conn->query("SELECT * FROM students");

// Loop through each student record
while($row = $res->fetch_assoc()) {

    // Calculate average marks of 5 subjects
    $avg = (
        $row['english'] +
        $row['math'] +
        $row['science'] +
        $row['nepali'] +
        $row['social']
    ) / 5;

    // Get grade for average
    $avgGrade = getGrade($avg);

    // Decide remarks based on average
    if($avg >= 80)
        $remark = "Excellent";
    elseif($avg >= 40)
        $remark = "Satisfactory";
    else
        $remark = "Needs Improvement";

    // If attendance is less than 40, apply red color class
    $attClass = ($row['attendance'] < 40) ? "low-attendance" : "";

    // Print table row
    echo "<tr>";

    // Print student name (with XSS protection)
    echo "<td>".htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8')."</td>";

    // Print student grade/class
    echo "<td>".htmlspecialchars($row['class'], ENT_QUOTES, 'UTF-8')."</td>";

    // Print English marks with grade
    echo "<td class='grade-".getGrade($row['english'])."'>
            {$row['english']} (".getGrade($row['english']).")
          </td>";

    // Print Math marks with grade
    echo "<td class='grade-".getGrade($row['math'])."'>
            {$row['math']} (".getGrade($row['math']).")
          </td>";

    // Print Science marks with grade
    echo "<td class='grade-".getGrade($row['science'])."'>
            {$row['science']} (".getGrade($row['science']).")
          </td>";

    // Print Nepali marks with grade
    echo "<td class='grade-".getGrade($row['nepali'])."'>
            {$row['nepali']} (".getGrade($row['nepali']).")
          </td>";

    // Print Social marks with grade
    echo "<td class='grade-".getGrade($row['social'])."'>
            {$row['social']} (".getGrade($row['social']).")
          </td>";

    // Print attendance (with XSS protection)
    echo "<td class='$attClass'>".htmlspecialchars($row['attendance'], ENT_QUOTES, 'UTF-8')."%</td>";

    // Print average percentage with grade
    echo "<td class='grade-$avgGrade'>
            ".round($avg,2)."% ($avgGrade)
          </td>";

    // Print remarks
    echo "<td>$remark</td>";

    // Print action buttons
    echo "<td>
            <a href='edit.php?id={$row['id']}' class='action-btn'>Edit</a>
            <a href='delete.php?id={$row['id']}' class='action-btn'
            onclick='return confirm(\"Are you sure you want to delete?\")'>
            Delete</a>
          </td>";

    // Close table row
    echo "</tr>";
}
?>

</tbody>
<!-- Table body ends -->

</table>
<!-- Table ends -->

</div>
<!-- Records box ends -->

</div>
<!-- Container ends -->

<?php
// Include footer file
include("../includes/footer.php");
?>
