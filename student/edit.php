<?php
include "../config/db.php";

$id = intval($_GET['id'] ?? 0);
$data = $conn->query("SELECT * FROM student WHERE student_id=$id")->fetch_assoc();
$degrees = $conn->query("SELECT degree_id, degree_desc FROM degree ORDER BY degree_desc");

if (!$data) {
        header('Location: index.php'); exit;
}

if (isset($_POST['update'])) {
        $fname = trim($_POST['fname']);
        $lname = trim($_POST['lname']);
        $degreeId = intval($_POST['degree_id'] ?? 0);
        
        // Validate: names should only contain letters, spaces, hyphens, apostrophes
        if (!preg_match("/^[a-zA-Z '-]+$/", $fname)) {
            $error = "First name can only contain letters, spaces, hyphens, and apostrophes.";
        } elseif (!preg_match("/^[a-zA-Z '-]+$/", $lname)) {
            $error = "Last name can only contain letters, spaces, hyphens, and apostrophes.";
        } else {
            $fname = $conn->real_escape_string($fname);
            $lname = $conn->real_escape_string($lname);
            $result = $conn->query("UPDATE student SET student_fname='$fname', student_lname='$lname', degree_id=$degreeId WHERE student_id=$id");
            if ($result) {
                header("Location: index.php");
                exit;
            } else {
                $error = htmlspecialchars($conn->error);
            }
        }
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Edit Student</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="../assets/script.js" defer></script>
</head>
<body>
<header class="site-header"><div class="container-app"><div style="display:flex;justify-content:space-between;align-items:center"><div class="brand">School Management</div><div class="site-nav"><a href="index.php">Back</a></div></div></div></header>
<main class="container-app">
    <div class="card">
        <h2>Edit Student</h2>
        <?php if (isset($error)): ?>
            <div style="margin-bottom:12px;color:red"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="fname" value="<?= htmlspecialchars($data['student_fname']) ?>" required>
            <input type="text" name="lname" value="<?= htmlspecialchars($data['student_lname']) ?>" required>
            <select name="degree_id" required>
                <option value="">Select Degree</option>
                <?php while ($d = $degrees->fetch_assoc()):
                        $sel = ($d['degree_id'] == $data['degree_id']) ? 'selected' : '';
                ?>
                    <option value="<?= htmlspecialchars($d['degree_id']) ?>" <?= $sel ?>><?= htmlspecialchars($d['degree_desc']) ?></option>
                <?php endwhile; ?>
            </select>
            <div style="display:flex;gap:8px">
                <button class="btn" name="update">Update</button>
                <a class="btn ghost" href="index.php">Cancel</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>
