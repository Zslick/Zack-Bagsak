<?php
include "../config/db.php";
$id = intval($_GET['id'] ?? 0);
if ($id) {
	// Delete dependent student records first (or set to NULL if preferred)
	$conn->query("UPDATE student SET degree_id=NULL WHERE degree_id=$id");
	$conn->query("DELETE FROM degree WHERE degree_id=$id");
}
header("Location: index.php");
exit;
