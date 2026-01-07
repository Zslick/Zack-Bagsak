<?php
include "../config/db.php";
$id = intval($_GET['id'] ?? 0);
if ($id) {
	// Delete student_class enrollments first
	$conn->query("DELETE FROM student_class WHERE student_id=$id");
	// Now delete the student
	$conn->query("DELETE FROM student WHERE student_id=$id");
}
header("Location: index.php");
exit;
