<?php
include "../config/db.php";

$id = intval($_GET['id'] ?? 0);
$data = $conn->query("SELECT * FROM course WHERE course_id=$id")->fetch_assoc();
if (!$data) { header('Location: add.php'); exit; }

if (isset($_POST['update'])) {
		$desc = $conn->real_escape_string($_POST['desc']);
		$teacher_id = intval($_POST['teacher_id']);
		$result = $conn->query("UPDATE course SET course_desc='$desc', teacher_id=$teacher_id WHERE course_id=$id");
		if ($result) {
			header('Location: add.php'); exit;
		} else {
			$error = htmlspecialchars($conn->error);
		}
}
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Edit Course</title>
	<link rel="stylesheet" href="../assets/style.css">
	<script src="../assets/script.js" defer></script>
</head>
<body>
<header class="site-header"><div class="container-app"><div style="display:flex;justify-content:space-between;align-items:center"><div class="brand">School Management</div><div class="site-nav"><a href="add.php">Back</a></div></div></div></header>
<main class="container-app">
	<div class="card">
		<h2>Edit Course</h2>
		<?php if (isset($error)): ?>
			<div style="margin-bottom:12px;color:red"><?= $error ?></div>
		<?php endif; ?>
		<form method="POST">
			<input type="text" name="desc" value="<?= htmlspecialchars($data['course_desc']) ?>" required>
			<select name="teacher_id" required>
				<option value="">Select Teacher</option>
				<?php
				$teachers = $conn->query("SELECT teacher_id, teacher_fname, teacher_lname FROM teacher ORDER BY teacher_fname, teacher_lname");
				while ($t = $teachers->fetch_assoc()) {
						$sel = $t['teacher_id'] == $data['teacher_id'] ? 'selected' : '';
						echo "<option value='".htmlspecialchars($t['teacher_id'])."' $sel>".htmlspecialchars($t['teacher_fname'])." ".htmlspecialchars($t['teacher_lname'])."</option>";
				}
				?>
			</select>
			<div style="display:flex;gap:8px;margin-top:8px">
				<button class="btn" name="update">Update</button>
				<a class="btn ghost" href="add.php">Cancel</a>
			</div>
		</form>
	</div>
</main>
</body>
</html>
