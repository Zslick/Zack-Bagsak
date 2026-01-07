<?php include "../config/db.php"; ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Teachers · School</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="../assets/script.js" defer></script>
</head>
<body>
<header class="site-header"><div class="container-app"><div style="display:flex;justify-content:space-between;align-items:center"><div class="brand">School Management</div><div class="site-nav"><a href="../index.php">Home</a></div></div></div></header>
<main class="container-app">
    <div class="card">
        <div class="top-actions">
            <h2>Manage Teachers</h2>
        </div>

        <form method="POST" style="margin-bottom:14px">
            <input type="text" name="fname" placeholder="First Name" required>
            <input type="text" name="lname" placeholder="Last Name" required>
            <div style="display:flex;gap:8px;margin-top:8px">
                <button class="btn" name="save">Save</button>
                <a class="btn ghost" href="../index.php">Cancel</a>
            </div>
        </form>

        <?php
        // handle save
        if (isset($_POST['save'])) {
            $fname = $conn->real_escape_string($_POST['fname']);
            $lname = $conn->real_escape_string($_POST['lname']);
            $result = $conn->query("INSERT INTO teacher (teacher_fname, teacher_lname) VALUES ('$fname', '$lname')");
                if ($result) {
                    echo "<div style='margin-top:12px;color:green'>Teacher saved.</div>";
                } else {
                    echo "<div style='margin-top:12px;color:red'>Error: " . htmlspecialchars($conn->error) . "</div>";
                }
        }

        // fetch teachers
        $sql = "SELECT t.teacher_id, t.teacher_fname, t.teacher_lname
                FROM teacher t
                ORDER BY t.teacher_id";
        $result = $conn->query($sql);
        ?>

        <table>
            <tr><th>ID</th><th>Teacher</th><th>Action</th></tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['teacher_id']) ?></td>
                    <td><?= htmlspecialchars($row['teacher_fname']) ?> <?= htmlspecialchars($row['teacher_lname']) ?></td>
                    <td class="actions">
                        <a href="edit.php?id=<?= $row['teacher_id'] ?>">Edit</a>
                        <a data-confirm="Delete this teacher?" href="delete.php?id=<?= $row['teacher_id'] ?>" style="color:#d33">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</main>
</body>
</html>
