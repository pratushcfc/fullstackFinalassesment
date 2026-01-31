<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include "../config/db.php";

if ($_POST) {
$stmt = $conn->prepare(
"UPDATE students SET name=?,class=?,english=?,math=?,science=?,nepali=?,social=?,attendance=? WHERE id=?"
);
$stmt->bind_param(
"siiiiiiii",
$_POST['name'],
$_POST['class'],
$_POST['english'],
$_POST['math'],
$_POST['science'],
$_POST['nepali'],
$_POST['social'],
$_POST['attendance'],
$_POST['id']
);
$stmt->execute();
header("Location: index.php");
}

$data = $conn->query("SELECT * FROM students WHERE id=".$_GET['id'])->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Student</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="center-box">
<h2>Edit Student</h2>

<form method="POST">
<input type="hidden" name="id" value="<?= $data['id'] ?>">

<input type="text" name="name" value="<?= $data['name'] ?>" required>

<select name="class" required>
<?php for ($i=1;$i<=10;$i++) {
$sel = ($data['class']==$i)?"selected":"";
echo "<option $sel>$i</option>";
} ?>
</select>

<input type="number" name="english" value="<?= $data['english'] ?>" required>
<input type="number" name="math" value="<?= $data['math'] ?>" required>
<input type="number" name="science" value="<?= $data['science'] ?>" required>
<input type="number" name="nepali" value="<?= $data['nepali'] ?>" required>
<input type="number" name="social" value="<?= $data['social'] ?>" required>
<input type="number" name="attendance" value="<?= $data['attendance'] ?>" required>

<button>Update Student</button>
</form>
</div>

</body>
</html>
