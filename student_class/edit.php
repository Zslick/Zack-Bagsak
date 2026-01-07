<?php
include "../config/db.php";

$id = intval($_GET['id'] ?? 0);
$data = $conn->query("SELECT * FROM student_class WHERE student_class_id=$id")->fetch_assoc();
if (!$data) { header('Location: index.php'); exit; }

$message = '';
if (isset($_POST['update'])) {
        $classId = intval($_POST['class_id'] ?? 0);
        $teacherId = intval($_POST['teacher_id'] ?? 0);
        $studentId = intval($_POST['student_id'] ?? 0);

        if ($classId === 0 || $teacherId === 0 || $studentId === 0) {
                $message = "<div style='color:red;margin-bottom:12px'>Please select class, teacher, and student.</div>";
        } else {
                $result = $conn->query("UPDATE student_class SET class_id=$classId, teacher_id=$teacherId, student_id=$studentId WHERE student_class_id=$id");
                if ($result) {
                        header('Location: index.php'); exit;
                } else {
                        $message = "<div style='color:red;margin-bottom:12px'>Error: " . htmlspecialchars($conn->error) . "</div>";
                }
        }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Edit Student Class</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="../assets/script.js" defer></script>
</head>
<body>
<header class="site-header"><div class="container-app"><div style="display:flex;justify-content:space-between;align-items:center"><div class="brand">School Management</div><div class="site-nav"><a href="index.php">Back</a></div></div></div></header>
<main class="container-app">
    <div class="card">
        <h2>Edit Assignment</h2>
        <?= $message ?>
        <form method="POST" style="display:grid;gap:8px">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:8px">
                <select name="class_id" required>
                    <option value="">Select Class</option>
                    <?php
                    $classes = $conn->query("SELECT class_id, class_desc FROM class ORDER BY class_desc");
                    while ($c = $classes->fetch_assoc()) {
                            $sel = ($c['class_id'] == $data['class_id']) ? 'selected' : '';
                            echo "<option value='".htmlspecialchars($c['class_id'])."' $sel>".htmlspecialchars($c['class_desc'])."</option>";
                    }
                    ?>
                </select>
                <select name="teacher_id" required>
                    <option value="">Select Teacher</option>
                    <?php
                    $teachers = $conn->query("SELECT teacher_id, teacher_fname, teacher_lname FROM teacher ORDER BY teacher_fname, teacher_lname");
                    while ($t = $teachers->fetch_assoc()) {
                            $sel = ($t['teacher_id'] == $data['teacher_id']) ? 'selected' : '';
                            echo "<option value='".htmlspecialchars($t['teacher_id'])."' $sel>".htmlspecialchars($t['teacher_fname'])." ".htmlspecialchars($t['teacher_lname'])."</option>";
                    }
                    ?>
                </select>
                <select name="student_id" required>
                    <option value="">Select Student</option>
                    <?php
                    $students = $conn->query("SELECT student_id, student_fname, student_lname FROM student ORDER BY student_fname, student_lname");
                    while ($s = $students->fetch_assoc()) {
                            $sel = ($s['student_id'] == $data['student_id']) ? 'selected' : '';
                            echo "<option value='".htmlspecialchars($s['student_id'])."' $sel>".htmlspecialchars($s['student_fname'])." ".htmlspecialchars($s['student_lname'])."</option>";
                    }
                    ?>
                </select>
            </div>
            <div style="display:flex;gap:8px;margin-top:8px">
                <button class="btn" name="update">Update</button>
                <a class="btn ghost" href="index.php">Cancel</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>
