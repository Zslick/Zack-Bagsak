<?php
include "../config/db.php";
$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
	// Delete dependent student_class records first
	$conn->query("DELETE FROM student_class WHERE class_id=$id");
	// Now delete the class
	$conn->query("DELETE FROM class WHERE class_id=$id");
}
header('Location: index.php');
exit;