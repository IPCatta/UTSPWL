<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — UTSPWL</title>
    <link rel="stylesheet" href="css/style.css?v=1.1">
</head>
<body>
    <div style="width:100%; display:flex; flex-direction:column; align-items:center; margin-top:20px;">
        <h2 class="login-header">Create your account</h2>
        <div class="container login-container" style="margin-top:0;">
            <?php if ($error !== ''): ?>
                <p style="color:#ff7b72; font-size:14px; margin-bottom:15px;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
            <form method="POST" action="index.php?module=auth&act=register">
                <label>Username:</label>
                <input type="text" name="name" required>

                <label>Email address:</label>
                <input type="email" name="email" required>

                <label>Password:</label>
                <input type="password" name="passw" required>

                <button type="submit" class="btn" style="width:100%; margin-top:10px;">Sign up</button>
            </form>
        </div>
        <div style="margin-top:20px; padding:15px 20px; border:1px solid #30363d; border-radius:6px; max-width:340px; width:100%; text-align:center; font-size:14px; background-color:transparent;">
            Already have an account? <a href="index.php?module=auth&act=login">Sign in →</a>
        </div>
    </div>
</body>
</html>
