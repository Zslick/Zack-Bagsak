<?php
include "../config/db.php";
$id = intval($_GET['id'] ?? 0);
if ($id) {
	// Delete all dependent records in correct order
	$conn->query("DELETE FROM student_class WHERE teacher_id=$id");
	$conn->query("DELETE FROM class WHERE teacher_id=$id");
	$conn->query("DELETE FROM course WHERE teacher_id=$id");
	$conn->query("DELETE FROM teacher WHERE teacher_id=$id");
}
header('Location: index.php');
exit;
