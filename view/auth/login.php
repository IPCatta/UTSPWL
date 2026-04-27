<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — UTSPWL</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if ($error !== ''): ?>
            <p style="color:red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <form method="POST" action="index.php?module=auth&act=login">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="passw" required>

            <label style="display:flex; align-items:center; margin-bottom:15px; font-weight:normal; cursor:pointer;">
                <input type="checkbox" name="remember" style="width:auto; margin-right:8px; margin-bottom:0;">
                Remember Me
            </label>
            <button type="submit" class="btn">Masuk</button>
        </form>
        <p>Belum punya akun? <a href="index.php?module=auth&act=register">Register</a></p>
    </div>
</body>
</html>
