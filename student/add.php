<?php include "../config/db.php"; ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Add Student</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="../assets/script.js" defer></script>
</head>
<body>
<header class="site-header">
    <div class="container-app"><div style="display:flex;justify-content:space-between;align-items:center"><div class="brand">School Management</div><div class="site-nav"><a href="index.php">Back to list</a></div></div></div>
</header>

<main class="container-app">
    <div class="card">
        <h2>Add Student</h2>

        <?php
        $degrees = $conn->query("SELECT degree_id, degree_desc FROM degree ORDER BY degree_desc");
        $message = '';
        if (isset($_POST['save'])) {
                $fname = $conn->real_escape_string($_POST['fname']);
                $lname = $conn->real_escape_string($_POST['lname']);
                $degreeId = intval($_POST['degree_id'] ?? 0);

                if ($fname === '' || $lname === '' || $degreeId === 0) {
                        $message = "<div style='color:red;margin-bottom:12px'>Please fill all fields including degree.</div>";
                } else {
                        $result = $conn->query("INSERT INTO student (student_fname, student_lname, degree_id) VALUES ('$fname', '$lname', $degreeId)");
                        if ($result) {
                            header("Location: index.php");
                            exit;
                        } else {
                            $message = "<div style='color:red;margin-bottom:12px'>Error: " . htmlspecialchars($conn->error) . "</div>";
                        }
                }
        }
        ?>

        <?= $message ?>

        <form method="POST">
            <input type="text" name="fname" placeholder="First Name" required>
            <input type="text" name="lname" placeholder="Last Name" required>
            <select name="degree_id" required>
                <option value="">Select Degree</option>
                <?php while ($d = $degrees->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($d['degree_id']) ?>"><?= htmlspecialchars($d['degree_desc']) ?></option>
                <?php endwhile; ?>
            </select>
            <div style="display:flex;gap:8px">
                <button class="btn" name="save">Save</button>
                <a class="btn ghost" href="index.php">Cancel</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>
