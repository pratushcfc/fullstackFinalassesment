<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include "../config/db.php";

// Function to calculate grade
function getGrade($marks) {
    if ($marks >= 80) return "A";
    if ($marks >= 40) return "B";
    return "F";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Record Management System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h2>Student Record Management System</h2>

<div class="container">

<input type="text" id="search" class="search-bar" placeholder="Search student by name">
<div style="clear:both"></div>

<div class="box">
<h3>Add Student</h3>
<form action="add.php" method="POST">

<input type="text" name="name" placeholder="Student Name" required>

<select name="class" required>
    <option value="">Select Class</option>
    <?php for ($i=1;$i<=10;$i++) echo "<option>$i</option>"; ?>
</select>

<input type="number" name="english" placeholder="English Marks" required>
<input type="number" name="math" placeholder="Math Marks" required>
<input type="number" name="science" placeholder="Science Marks" required>
<input type="number" name="nepali" placeholder="Nepali Marks" required>
<input type="number" name="social" placeholder="Social Studies Marks" required>

<input type="number" name="attendance" placeholder="Attendance %" required>

<button>Add Student</button>
</form>
</div>

<div class="box">
<h3>Student Records</h3>

<table>
<thead>
<tr>
<th>Name</th>
<th>Class</th>
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
<tbody id="result">

<?php
$res = $conn->query("SELECT * FROM students");

while ($row = $res->fetch_assoc()) {

$avg = (
$row['english'] +
$row['math'] +
$row['science'] +
$row['nepali'] +
$row['social']
) / 5;

$avgGrade = getGrade($avg);

if ($avg >= 80) $remark = "Excellent";
elseif ($avg >= 40) $remark = "Satisfactory";
else $remark = "Needs Improvement";

$attClass = ($row['attendance'] < 40) ? "low-attendance" : "";

echo "<tr>
<td>{$row['name']}</td>
<td>{$row['class']}</td>

<td class='grade-".getGrade($row['english'])."'>{$row['english']} (".getGrade($row['english']).")</td>
<td class='grade-".getGrade($row['math'])."'>{$row['math']} (".getGrade($row['math']).")</td>
<td class='grade-".getGrade($row['science'])."'>{$row['science']} (".getGrade($row['science']).")</td>
<td class='grade-".getGrade($row['nepali'])."'>{$row['nepali']} (".getGrade($row['nepali']).")</td>
<td class='grade-".getGrade($row['social'])."'>{$row['social']} (".getGrade($row['social']).")</td>

<td class='$attClass'>{$row['attendance']}%</td>
<td class='grade-$avgGrade'>".round($avg,2)."% ($avgGrade)</td>
<td>$remark</td>

<td>
<a href='edit.php?id={$row['id']}' class='action-btn'>Edit</a>
<a href='delete.php?id={$row['id']}' class='action-btn'
onclick='return confirm(\"Are you sure?\")'>Delete</a>
</td>
</tr>";
}
?>

</tbody>
</table>
</div>

</div>

<script src="../assets/js/script.js"></script>
</body>
</html>
