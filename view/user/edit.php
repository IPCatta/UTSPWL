<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Edit User</h2>
        <form action="index.php?action=update&id=<?= $data['id'] ?>" method="POST">
            <label>Nama User:</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($data['name']) ?>" required>
            <button type="submit" class="btn">Update</button>
            <a href="index.php" class="btn btn-danger" style="margin-left:10px;">Batal</a>
        </form>
    </div>
</body>
</html>
