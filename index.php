<?php 
include "config/db.php"; 
$view = $_GET['view'] ?? 'degrees'; // Default view
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>School Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; background: #f0f2f5; }
        .sidebar { width: 250px; background: #343a40; color: white; padding-top: 2rem; }
        .sidebar a { color: rgba(255,255,255,0.8); text-decoration: none; padding: 10px 20px; display: block; }
        .sidebar a:hover, .sidebar a.active { background: #495057; color: white; }
        .content-area { flex-grow: 1; padding: 2rem; }
        .section-card { padding: 1.5rem; border-radius: 10px; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="sidebar">
    <h4 class="text-center mb-4">Admin Panel</h4>
    <a href="?view=degrees" class="<?= $view == 'degrees' ? 'active' : '' ?>">Degrees</a>
    <a href="?view=students" class="<?= $view == 'students' ? 'active' : '' ?>">Students</a>
    <a href="?view=teachers" class="<?= $view == 'teachers' ? 'active' : '' ?>">Teachers</a>
    <a href="?view=courses" class="<?= $view == 'courses' ? 'active' : '' ?>">Courses</a>
    <a href="?view=classes" class="<?= $view == 'classes' ? 'active' : '' ?>">Classes</a>
    <a href="?view=student_classes" class="<?= $view == 'student_classes' ? 'active' : '' ?>">Enrollments</a>
</div>

<div class="content-area">
    <div class="section-card">
        
        <?php if ($view == 'degrees'): ?>
            <h2>Degrees</h2>
            <form method="POST" class="d-flex gap-2 mb-3">
                <input type="text" name="degree_desc" class="form-control" placeholder="Degree Description" required>
                <button class="btn btn-success" name="add_degree">Add</button>
            </form>
            <?php
            if (isset($_POST['add_degree'])) {
                $desc = $conn->real_escape_string($_POST['degree_desc']);
                $conn->query("INSERT INTO degree (degree_desc) VALUES ('$desc')");
            }
            $data = $conn->query("SELECT * FROM degree");
            ?>
            <table class="table table-striped">
                <thead><tr><th>ID</th><th>Description</th><th>Action</th></tr></thead>
                <tbody>
                    <?php while($row = $data->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['degree_id'] ?></td>
                        <td><?= htmlspecialchars($row['degree_desc']) ?></td>
                        <td>
                            <a href="degree/edit.php?id=<?= $row['degree_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="degree/delete.php?id=<?= $row['degree_id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php elseif ($view == 'students'): ?>
            <h2>Students</h2>
            <?php
            $studentMessage = '';
            if (isset($_POST['add_student'])) {
                $fname = trim($_POST['student_fname']);
                $lname = trim($_POST['student_lname']);
                $degreeId = intval($_POST['student_degree_id']);
                
                // Validate: names should only contain letters, spaces, hyphens, apostrophes
                if (!preg_match("/^[a-zA-Z '-]+$/", $fname)) {
                    $studentMessage = "<div class='alert alert-danger'>First name can only contain letters, spaces, hyphens, and apostrophes.</div>";
                } elseif (!preg_match("/^[a-zA-Z '-]+$/", $lname)) {
                    $studentMessage = "<div class='alert alert-danger'>Last name can only contain letters, spaces, hyphens, and apostrophes.</div>";
                } else {
                    // Check for duplicate
                    $checkDupe = $conn->query("SELECT student_id FROM student WHERE student_fname='" . $conn->real_escape_string($fname) . "' AND student_lname='" . $conn->real_escape_string($lname) . "'");
                    if ($checkDupe->num_rows > 0) {
                        $studentMessage = "<div class='alert alert-warning'>A student with this name already exists.</div>";
                    } else {
                        $fname = $conn->real_escape_string($fname);
                        $lname = $conn->real_escape_string($lname);
                        $conn->query("INSERT INTO student (student_fname, student_lname, degree_id) VALUES ('$fname', '$lname', $degreeId)");
                        $studentMessage = "<div class='alert alert-success'>Student added successfully!</div>";
                    }
                }
            }
            ?>
            <form method="POST" class="d-flex gap-2 mb-3">
                <input type="text" name="student_fname" class="form-control" placeholder="First Name" required>
                <input type="text" name="student_lname" class="form-control" placeholder="Last Name" required>
                <select name="student_degree_id" class="form-select" required>
                    <option value="">Select Degree</option>
                    <?php
                    $degreeOpts = $conn->query("SELECT degree_id, degree_desc FROM degree ORDER BY degree_desc");
                    while ($d = $degreeOpts->fetch_assoc()) {
                        echo "<option value='{$d['degree_id']}'>{$d['degree_desc']}</option>";
                    }
                    ?>
                </select>
                <button class="btn btn-success" name="add_student">Add</button>
            </form>
            <?= $studentMessage ?>
            <?php
            $students = $conn->query("SELECT s.*, d.degree_desc FROM student s LEFT JOIN degree d ON s.degree_id = d.degree_id");
            ?>
            <table class="table table-striped">
                <thead><tr><th>ID</th><th>Name</th><th>Degree</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php while($row = $students->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['student_id'] ?></td>
                        <td><?= htmlspecialchars($row['student_fname'] . ' ' . $row['student_lname']) ?></td>
                        <td><?= htmlspecialchars($row['degree_desc'] ?? 'N/A') ?></td>
                        <td>
                            <a href="student/edit.php?id=<?= $row['student_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="student/delete.php?id=<?= $row['student_id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php elseif ($view == 'teachers'): ?>
            <h2>Teachers</h2>
            <?php
            $teacherMessage = '';
            if (isset($_POST['add_teacher'])) {
                $fname = trim($_POST['teacher_fname']);
                $lname = trim($_POST['teacher_lname']);
                
                // Validate: names should only contain letters, spaces, hyphens, apostrophes
                if (!preg_match("/^[a-zA-Z '-]+$/", $fname)) {
                    $teacherMessage = "<div class='alert alert-danger'>First name can only contain letters, spaces, hyphens, and apostrophes.</div>";
                } elseif (!preg_match("/^[a-zA-Z '-]+$/", $lname)) {
                    $teacherMessage = "<div class='alert alert-danger'>Last name can only contain letters, spaces, hyphens, and apostrophes.</div>";
                } else {
                    // Check for duplicate
                    $checkDupe = $conn->query("SELECT teacher_id FROM teacher WHERE teacher_fname='" . $conn->real_escape_string($fname) . "' AND teacher_lname='" . $conn->real_escape_string($lname) . "'");
                    if ($checkDupe->num_rows > 0) {
                        $teacherMessage = "<div class='alert alert-warning'>A teacher with this name already exists.</div>";
                    } else {
                        $fname = $conn->real_escape_string($fname);
                        $lname = $conn->real_escape_string($lname);
                        $conn->query("INSERT INTO teacher (teacher_fname, teacher_lname) VALUES ('$fname','$lname')");
                        $teacherMessage = "<div class='alert alert-success'>Teacher added successfully!</div>";
                    }
                }
            }
            ?>
            <form method="POST" class="d-flex gap-2 mb-3">
                <input type="text" name="teacher_fname" class="form-control" placeholder="First Name" required>
                <input type="text" name="teacher_lname" class="form-control" placeholder="Last Name" required>
                <button class="btn btn-success" name="add_teacher">Add</button>
            </form>
            <?= $teacherMessage ?>
            <?php
            $teachers = $conn->query("SELECT t.teacher_id, t.teacher_fname, t.teacher_lname
                                      FROM teacher t
                                      ORDER BY t.teacher_id");
            ?>
            <table class="table table-striped">
                <thead><tr><th>ID</th><th>Teacher</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php while($row = $teachers->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['teacher_id'] ?></td>
                        <td><?= htmlspecialchars($row['teacher_fname'] . ' ' . $row['teacher_lname']) ?></td>
                        <td>
                            <a href="teacher/edit.php?id=<?= $row['teacher_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="teacher/delete.php?id=<?= $row['teacher_id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php elseif ($view == 'courses'): ?>
            <h2>Courses</h2>
            <form method="POST" class="d-flex gap-2 mb-3">
                <input type="text" name="course_desc" class="form-control" placeholder="Course Description" required>
                <select name="course_teacher_id" class="form-select" required>
                    <option value="">Select Teacher</option>
                    <?php
                    $teacherOpts = $conn->query("SELECT teacher_id, teacher_fname, teacher_lname FROM teacher ORDER BY teacher_fname");
                    while ($t = $teacherOpts->fetch_assoc()) {
                        echo "<option value='{$t['teacher_id']}'>{$t['teacher_fname']} {$t['teacher_lname']}</option>";
                    }
                    ?>
                </select>
                <button class="btn btn-success" name="add_course">Add</button>
            </form>
            <?php
            if (isset($_POST['add_course'])) {
                $desc = $conn->real_escape_string($_POST['course_desc']);
                $teacherId = intval($_POST['course_teacher_id']);
                $conn->query("INSERT INTO course (course_desc, teacher_id) VALUES ('$desc', $teacherId)");
            }
            $courses = $conn->query("SELECT c.course_id, c.course_desc
                                     FROM course c
                                     ORDER BY c.course_id");
            ?>
            <table class="table table-striped">
                <thead><tr><th>ID</th><th>Course</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php while($row = $courses->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['course_id'] ?></td>
                        <td><?= htmlspecialchars($row['course_desc']) ?></td>
                        <td>
                            <a href="course/edit.php?id=<?= $row['course_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="course/delete.php?id=<?= $row['course_id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php elseif ($view == 'classes'): ?>
            <h2>Classes</h2>
            <form method="POST" class="d-flex gap-2 mb-3">
                <input type="text" name="class_desc" class="form-control" placeholder="Class Description" required>
                <select name="class_course_id" class="form-select" required>
                    <option value="">Select Course</option>
                    <?php
                    $courseOpts = $conn->query("SELECT course_id, course_desc FROM course ORDER BY course_desc");
                    while ($c = $courseOpts->fetch_assoc()) {
                        echo "<option value='{$c['course_id']}'>{$c['course_desc']}</option>";
                    }
                    ?>
                </select>
                <select name="class_teacher_id" class="form-select" required>
                    <option value="">Select Teacher</option>
                    <?php
                    $teacherOpts2 = $conn->query("SELECT teacher_id, teacher_fname, teacher_lname FROM teacher ORDER BY teacher_fname");
                    while ($t = $teacherOpts2->fetch_assoc()) {
                        echo "<option value='{$t['teacher_id']}'>{$t['teacher_fname']} {$t['teacher_lname']}</option>";
                    }
                    ?>
                </select>
                <button class="btn btn-success" name="add_class">Add</button>
            </form>
            <?php
            if (isset($_POST['add_class'])) {
                $classDesc = $conn->real_escape_string($_POST['class_desc']);
                $courseId = intval($_POST['class_course_id']);
                $teacherId = intval($_POST['class_teacher_id']);
                $conn->query("INSERT INTO class (class_desc, course_id, teacher_id) VALUES ('$classDesc', $courseId, $teacherId)");
            }
            $classes = $conn->query("SELECT c.class_id, c.class_desc, cr.course_desc, t.teacher_fname, t.teacher_lname
                                     FROM class c
                                     LEFT JOIN course cr ON cr.course_id = c.course_id
                                     LEFT JOIN teacher t ON t.teacher_id = c.teacher_id
                                     ORDER BY c.class_id");
            ?>
            <table class="table table-striped">
                <thead><tr><th>ID</th><th>Description</th><th>Course</th><th>Teacher</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php while($row = $classes->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['class_id'] ?></td>
                        <td><?= htmlspecialchars($row['class_desc']) ?></td>
                        <td><?= htmlspecialchars($row['course_desc']) ?></td>
                        <td><?= htmlspecialchars($row['teacher_fname'] . ' ' . $row['teacher_lname']) ?></td>
                        <td>
                            <a href="class/edit.php?id=<?= $row['class_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="class/delete.php?id=<?= $row['class_id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php elseif ($view == 'student_classes'): ?>
            <h2>Student Enrollments</h2>
            <form method="POST" class="d-flex gap-2 mb-3">
                <select name="sc_class_id" class="form-select" required>
                    <option value="">Select Class</option>
                    <?php
                    $classOpts = $conn->query("SELECT class_id, class_desc FROM class ORDER BY class_desc");
                    while ($c = $classOpts->fetch_assoc()) {
                        echo "<option value='{$c['class_id']}'>{$c['class_desc']}</option>";
                    }
                    ?>
                </select>
                <select name="sc_teacher_id" class="form-select" required>
                    <option value="">Select Teacher</option>
                    <?php
                    $teacherOpts3 = $conn->query("SELECT teacher_id, teacher_fname, teacher_lname FROM teacher ORDER BY teacher_fname");
                    while ($t = $teacherOpts3->fetch_assoc()) {
                        echo "<option value='{$t['teacher_id']}'>{$t['teacher_fname']} {$t['teacher_lname']}</option>";
                    }
                    ?>
                </select>
                <select name="sc_student_id" class="form-select" required>
                    <option value="">Select Student</option>
                    <?php
                    $studentOpts = $conn->query("SELECT student_id, student_fname, student_lname FROM student ORDER BY student_fname");
                    while ($s = $studentOpts->fetch_assoc()) {
                        echo "<option value='{$s['student_id']}'>{$s['student_fname']} {$s['student_lname']}</option>";
                    }
                    ?>
                </select>
                <button class="btn btn-success" name="add_student_class">Add</button>
            </form>
            <?php
            if (isset($_POST['add_student_class'])) {
                $classId = intval($_POST['sc_class_id']);
                $teacherId = intval($_POST['sc_teacher_id']);
                $studentId = intval($_POST['sc_student_id']);
                $conn->query("INSERT INTO student_class (class_id, teacher_id, student_id) VALUES ($classId, $teacherId, $studentId)");
            }
            $studentClasses = $conn->query("SELECT sc.student_class_id, c.class_desc, t.teacher_fname, t.teacher_lname, s.student_fname, s.student_lname
                                            FROM student_class sc
                                            LEFT JOIN class c ON c.class_id = sc.class_id
                                            LEFT JOIN teacher t ON t.teacher_id = sc.teacher_id
                                            LEFT JOIN student s ON s.student_id = sc.student_id
                                            ORDER BY sc.student_class_id");
            
            // Debug output
            if (!$studentClasses) {
                echo "<div class='alert alert-danger'>Query error: " . htmlspecialchars($conn->error) . "</div>";
            }
            ?>
            <table class="table table-striped">
                <thead><tr><th>ID</th><th>Class</th><th>Teacher</th><th>Student</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php 
                    if ($studentClasses && $studentClasses->num_rows > 0) {
                        while($row = $studentClasses->fetch_assoc()): 
                    ?>
                    <tr>
                        <td><?= $row['student_class_id'] ?></td>
                        <td><?= htmlspecialchars($row['class_desc'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars(($row['teacher_fname'] ?? '') . ' ' . ($row['teacher_lname'] ?? '')) ?></td>
                        <td><?= htmlspecialchars(($row['student_fname'] ?? '') . ' ' . ($row['student_lname'] ?? '')) ?></td>
                        <td>
                            <a href="student_class/edit.php?id=<?= $row['student_class_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="student_class/delete.php?id=<?= $row['student_class_id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                    <?php 
                        endwhile;
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>No enrollments found. Add some data first.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

        <?php endif; ?>

    </div>
</div>

</body>
</html>