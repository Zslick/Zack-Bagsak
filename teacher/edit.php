<?php
include "../config/db.php";

$id = intval($_GET['id'] ?? 0);
$data = $conn->query("SELECT * FROM teacher WHERE teacher_id=$id")->fetch_assoc();
if (!$data) { header('Location: add.php'); exit; }

if (isset($_POST['update'])) {
		$fname = trim($_POST['fname']);
		$lname = trim($_POST['lname']);
		
		// Validate: names should only contain letters, spaces, hyphens, apostrophes
		if (!preg_match("/^[a-zA-Z '-]+$/", $fname)) {
			$error = "First name can only contain letters, spaces, hyphens, and apostrophes.";
		} elseif (!preg_match("/^[a-zA-Z '-]+$/", $lname)) {
			$error = "Last name can only contain letters, spaces, hyphens, and apostrophes.";
		} else {
			$fname = $conn->real_escape_string($fname);
			$lname = $conn->real_escape_string($lname);
			$result = $conn->query("UPDATE teacher SET teacher_fname='$fname', teacher_lname='$lname' WHERE teacher_id=$id");
			if ($result) {
				header('Location: add.php'); exit;
			} else {
				$error = htmlspecialchars($conn->error);
			}
		}
}
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Edit Teacher</title>
	<link rel="stylesheet" href="../assets/style.css">
	<script src="../assets/script.js" defer></script>
</head>
<body>
<header class="site-header"><div class="container-app"><div style="display:flex;justify-content:space-between;align-items:center"><div class="brand">School Management</div><div class="site-nav"><a href="add.php">Back</a></div></div></div></header>
<main class="container-app">
	<div class="card">
		<h2>Edit Teacher</h2>
		<?php if (isset($error)): ?>
			<div style="margin-bottom:12px;color:red"><?= $error ?></div>
		<?php endif; ?>
		<form method="POST">
			<input type="text" name="fname" value="<?= htmlspecialchars($data['teacher_fname']) ?>" required>
			<input type="text" name="lname" value="<?= htmlspecialchars($data['teacher_lname']) ?>" required>
			<div style="display:flex;gap:8px;margin-top:8px">
				<button class="btn" name="update">Update</button>
				<a class="btn ghost" href="add.php">Cancel</a>
			</div>
		</form>
	</div>
</main>
</body>
</html>
