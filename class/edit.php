<?php
include "../config/db.php";

$id = intval($_GET['id'] ?? 0);
$data = $conn->query("SELECT * FROM class WHERE class_id=$id")->fetch_assoc();
if (!$data) { header('Location: index.php'); exit; }

$message = '';
if (isset($_POST['update'])) {
        $classDesc = $conn->real_escape_string($_POST['class_desc'] ?? '');
        $courseId = intval($_POST['course_id'] ?? 0);
        $teacherId = intval($_POST['teacher_id'] ?? 0);

        if ($classDesc === '' || $courseId === 0 || $teacherId === 0) {
            $message = "<div style='color:red;margin-bottom:12px'>Please fill all required fields.</div>";
        } else {
            $result = $conn->query("UPDATE class SET class_desc='$classDesc', course_id=$courseId, teacher_id=$teacherId WHERE class_id=$id");
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
    <title>Edit Class</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="../assets/script.js" defer></script>
</head>
<body>
<header class="site-header"><div class="container-app"><div style="display:flex;justify-content:space-between;align-items:center"><div class="brand">School Management</div><div class="site-nav"><a href="index.php">Back</a></div></div></div></header>
<main class="container-app">
    <div class="card">
        <h2>Edit Class</h2>
        <?= $message ?>
        <form method="POST">
            <input type="text" name="class_desc" value="<?= htmlspecialchars($data['class_desc']) ?>" placeholder="Class Description" required>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:8px">
                <select name="course_id" required>
                    <option value="">Select Course</option>
                    <?php
                        $courses = $conn->query("SELECT course_id, course_desc FROM course ORDER BY course_desc");
                    while ($c = $courses->fetch_assoc()) {
                            $sel = ($c['course_id'] == $data['course_id']) ? 'selected' : '';
                            echo "<option value='".htmlspecialchars($c['course_id'])."' $sel>".htmlspecialchars($c['course_desc'])."</option>";
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
