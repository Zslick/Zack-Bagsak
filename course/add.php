<?php include "../config/db.php"; ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Courses · School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
    <script src="../assets/script.js" defer></script>
</head>
<body>
<header class="site-header"><div class="container-app"><div style="display:flex;justify-content:space-between;align-items:center"><div class="brand">School Management</div><div class="site-nav"><a href="../index.php">Home</a></div></div></div></header>
<main class="container-app">
    <div class="card">
        <div class="top-actions"><h2>Manage Courses</h2></div>

        <form method="POST" style="margin-bottom:14px">
            <input type="text" name="desc" placeholder="Course Description" required>
            <select name="teacher_id" required>
                <option value="">Select Teacher</option>
                <?php
                $teachers = $conn->query("SELECT t.teacher_id, t.teacher_fname, t.teacher_lname, GROUP_CONCAT(c.course_desc SEPARATOR ', ') as existing_courses 
                                         FROM teacher t 
                                         LEFT JOIN course c ON c.teacher_id = t.teacher_id 
                                         GROUP BY t.teacher_id 
                                         ORDER BY t.teacher_fname, t.teacher_lname");
                while ($t = $teachers->fetch_assoc()) {
                    $courses = $t['existing_courses'] ? ' (Teaching: ' . $t['existing_courses'] . ')' : ' (No courses yet)';
                    echo "<option value='".htmlspecialchars($t['teacher_id'])."'>".htmlspecialchars($t['teacher_fname'])." ".htmlspecialchars($t['teacher_lname']).$courses."</option>";
                }
                ?>
            </select>
            <div style="display:flex;gap:8px;margin-top:8px">
                <button class="btn" name="save">Save</button>
                <a class="btn ghost" href="../index.php">Cancel</a>
            </div>
        </form>

        <?php
        if (isset($_POST['save'])) {
            $desc = $conn->real_escape_string($_POST['desc']);
            $teacher_id = intval($_POST['teacher_id']);
            $result = $conn->query("INSERT INTO course (course_desc, teacher_id) VALUES ('$desc', $teacher_id)");
                if ($result) {
                    echo "<div style='margin-top:12px;color:green'>Course saved.</div>";
                } else {
                    echo "<div style='margin-top:12px;color:red'>Error: " . htmlspecialchars($conn->error) . "</div>";
                }
        }

        // list existing courses
        $res = $conn->query("SELECT c.course_id, c.course_desc FROM course c ORDER BY c.course_id");
        ?>

        <table>
            <tr><th>ID</th><th>Course</th><th>Action</th></tr>
            <?php while ($r = $res->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($r['course_id']) ?></td>
                    <td><?= htmlspecialchars($r['course_desc']) ?></td>
                    <td class="actions">
                        <a href="edit.php?id=<?= $r['course_id'] ?>">Edit</a>
                        <a data-confirm="Delete this course?" href="delete.php?id=<?= $r['course_id'] ?>" style="color:#d33">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</main>
</body>
</html>
