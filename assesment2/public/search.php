<?php

// Include database connection file
include "../config/db.php";

// Function to calculate grade based on marks
function getGrade($marks) {

    // If marks are 80 or more, grade is A
    if ($marks >= 80) return "A";

    // If marks are 40 or more, grade is B
    if ($marks >= 40) return "B";

    // If marks are below 40, grade is F
    return "F";
}

// Get search value from URL (AJAX sends it using GET method)
$q = "%" . $_GET['q'] . "%";  
// % is used for LIKE query to match partial names

// Prepare SQL query to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM students WHERE name LIKE ?");

// Bind the search value to the query
$stmt->bind_param("s", $q);

// Execute the query
$stmt->execute();

// Get result from executed query
$res = $stmt->get_result();

// Loop through each matching student
while ($row = $res->fetch_assoc()) {

    // Calculate average of 5 subjects
    $avg = (
        $row['english'] +
        $row['math'] +
        $row['science'] +
        $row['nepali'] +
        $row['social']
    ) / 5;

    // Get grade for average
    $avgGrade = getGrade($avg);

    // Set remarks based on average
    if ($avg >= 80)
        $remark = "Excellent";
    elseif ($avg >= 40)
        $remark = "Satisfactory";
    else
        $remark = "Needs Improvement";

    // Check if attendance is below 40%
    $attClass = ($row['attendance'] < 40) ? "low-attendance" : "";

    // Print table row
    echo "<tr>";

    // Student name (with XSS protection)
    echo "<td>".htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8')."</td>";

    // Student class
    echo "<td>".htmlspecialchars($row['class'], ENT_QUOTES, 'UTF-8')."</td>";

    // English marks with grade
    echo "<td class='grade-".getGrade($row['english'])."'>
            {$row['english']} (".getGrade($row['english']).")
          </td>";

    // Math marks with grade
    echo "<td class='grade-".getGrade($row['math'])."'>
            {$row['math']} (".getGrade($row['math']).")
          </td>";

    // Science marks with grade
    echo "<td class='grade-".getGrade($row['science'])."'>
            {$row['science']} (".getGrade($row['science']).")
          </td>";

    // Nepali marks with grade
    echo "<td class='grade-".getGrade($row['nepali'])."'>
            {$row['nepali']} (".getGrade($row['nepali']).")
          </td>";

    // Social marks with grade
    echo "<td class='grade-".getGrade($row['social'])."'>
            {$row['social']} (".getGrade($row['social']).")
          </td>";

    // Attendance (with XSS protection)
    echo "<td class='$attClass'>".htmlspecialchars($row['attendance'], ENT_QUOTES, 'UTF-8')."%</td>";

    // Average with grade
    echo "<td class='grade-$avgGrade'>
            ".round($avg,2)."% ($avgGrade)
          </td>";

    // Remarks
    echo "<td>$remark</td>";

    // Edit and Delete buttons
    echo "<td>
            <a href='edit.php?id={$row['id']}' class='action-btn'>Edit</a>
            <a href='delete.php?id={$row['id']}' class='action-btn'
            onclick='return confirm(\"Are you sure?\")'>
            Delete</a>
          </td>";

    // Close table row
    echo "</tr>";
}
?>
