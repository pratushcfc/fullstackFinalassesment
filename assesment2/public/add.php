<?php
include "../config/db.php";

$stmt = $conn->prepare(
"INSERT INTO students (name,class,english,math,science,nepali,social,attendance)
VALUES (?,?,?,?,?,?,?,?)"
);

$stmt->bind_param(
"siiiiiii",
$_POST['name'],
$_POST['class'],
$_POST['english'],
$_POST['math'],
$_POST['science'],
$_POST['nepali'],
$_POST['social'],
$_POST['attendance']
);

$stmt->execute();
header("Location: index.php");
