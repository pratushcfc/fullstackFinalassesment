<?php
include "../config/db.php";

function getGrade($marks) {
    if ($marks >= 80) return "A";
    if ($marks >= 40) return "B";
    return "F";
}

$q = "%" . $_GET['q'] . "%";
$stmt = $conn->prepare("SELECT * FROM students WHERE name LIKE ?");
$stmt->bind_param("s", $q);
$stmt->execute();
$res = $stmt->get_result();

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
