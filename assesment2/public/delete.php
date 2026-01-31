<?php
include "../config/db.php";

$stmt = $conn->prepare("DELETE FROM students WHERE id=?");
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();

header("Location: index.php");
 