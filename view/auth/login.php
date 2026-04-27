<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — UTSPWL</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div style="width:100%; display:flex; flex-direction:column; align-items:center; margin-top:20px;">
        <h2 class="login-header">Sign in to Inventaris</h2>
        <div class="container login-container" style="margin-top:0;">
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
        </div>
        <div style="margin-top:20px; padding:15px 20px; border:1px solid #30363d; border-radius:6px; max-width:340px; width:100%; text-align:center; font-size:14px; background-color:transparent;">
            New to Inventaris? <a href="index.php?module=auth&act=register">Create an account</a>
        </div>
    </div>
</body>
</html>
