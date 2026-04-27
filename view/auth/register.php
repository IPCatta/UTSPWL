<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — UTSPWL</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Register Akun</h2>
        <?php if ($error !== ''): ?>
            <p style="color:red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <form method="POST" action="index.php?module=auth&act=register">
            <label>Username:</label>
            <input type="text" name="name" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Password:</label>
            <input type="password" name="passw" required>

            <button type="submit" class="btn">Daftar</button>
        </form>
        <p>Sudah punya akun? <a href="index.php?module=auth&act=login">Login</a></p>
    </div>
</body>
</html>
