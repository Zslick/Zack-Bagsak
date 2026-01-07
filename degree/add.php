<?php include "../config/db.php"; ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Add Degree</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="../assets/script.js" defer></script>
</head>
<body>
<header class="site-header">
    <div class="container-app"><div style="display:flex;justify-content:space-between;align-items:center"><div class="brand">School Management</div><div class="site-nav"><a href="index.php">Back to list</a></div></div></div>
</header>

<main class="container-app">
    <div class="card">
        <h2>Add Degree</h2>

        <form method="POST">
            <input type="text" name="desc" placeholder="Degree Description" required>
            <div style="display:flex;gap:8px">
                <button class="btn" name="save">Save</button>
                <a class="btn ghost" href="index.php">Cancel</a>
            </div>
        </form>

        <?php
        if (isset($_POST['save'])) {
                $desc = $conn->real_escape_string($_POST['desc']);
                $result = $conn->query("INSERT INTO degree (degree_desc) VALUES ('$desc')");
                if ($result) {
                    echo "<div style='margin-top:12px;color:green'>Degree saved successfully.</div>";
                } else {
                    echo "<div style='margin-top:12px;color:red'>Error: " . htmlspecialchars($conn->error) . "</div>";
                }
        }
        ?>
    </div>
</main>
</body>
</html>
