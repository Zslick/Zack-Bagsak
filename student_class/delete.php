<?php
include "../config/db.php";
$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
        $conn->query("DELETE FROM student_class WHERE student_class_id=$id");
}
header('Location: index.php');
exit;
