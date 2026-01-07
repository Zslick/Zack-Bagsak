<?php
include "../config/db.php";

$id = intval($_GET['id'] ?? 0);
$data = $conn->query("SELECT * FROM degree WHERE degree_id=$id")->fetch_assoc();

if (!$data) {
    header('Location: index.php');
    exit;
}

if (isset($_POST['update'])) {
    $desc = $conn->real_escape_string($_POST['degree_desc']);
    $result = $conn->query("UPDATE degree SET degree_desc='$desc' WHERE degree_id=$id");
    if ($result) {
        header("Location: index.php");
        exit;
    } else {
        $error = htmlspecialchars($conn->error);
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Edit Degree</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<header class="site-header">
    <div class="container-app">
        <div style="display:flex;justify-content:space-between;align-items:center">
            <div class="brand">School Management</div>
            <div class="site-nav"><a href="index.php">Back</a></div>
        </div>
    </div>
</header>
<main class="container-app">
    <div class="card">
        <h2>Edit Degree</h2>
        <?php if (isset($error)): ?>
            <div style="margin-bottom:12px;color:red"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="degree_desc" class="form-control" value="<?= htmlspecialchars($data['degree_desc']) ?>" required>
            <div style="display:flex;gap:8px;margin-top:12px">
                <button class="btn btn-primary" name="update">Update</button>
                <a class="btn btn-secondary" href="index.php">Cancel</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>
