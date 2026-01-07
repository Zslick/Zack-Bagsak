<?php
include "../config/db.php";
$id = intval($_GET['id'] ?? 0);
if ($id) {
	// Delete dependent records first
	$conn->query("DELETE FROM student_class WHERE class_id IN (SELECT class_id FROM class WHERE course_id=$id)");
	$conn->query("DELETE FROM class WHERE course_id=$id");
	$conn->query("DELETE FROM course WHERE course_id=$id");
}
header('Location: index.php');
exit;
