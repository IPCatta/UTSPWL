<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna — UTSPWL</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Pengguna</h2>
        <?php if (!empty($error)): ?>
            <p style="color:red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <form method="POST" action="index.php?module=user&act=update&id=<?= (int)$user['id'] ?>">
            <label>Nama:</label>
            <input type="text" name="name"
                   value="<?= htmlspecialchars($user['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>

            <label>Email:</label>
            <input type="email" name="email"
                   value="<?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>

            <label>Password <small>(Kosongkan jika tidak ingin mengubah)</small>:</label>
            <input type="password" name="passw" placeholder="Isi untuk mengganti password">

            <button type="submit" class="btn">Update</button>
            <a href="index.php?module=user" class="btn btn-danger" style="margin-left:10px;">Batal</a>
        </form>
    </div>
</body>
</html>
