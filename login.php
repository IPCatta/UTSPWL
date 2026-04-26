<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Auto-login via Remember Me cookie
if (isset($_COOKIE['remember_id'], $_COOKIE['remember_token'])) {
    $rem_id = (int)$_COOKIE['remember_id'];
    $rem_token = $_COOKIE['remember_token'];
    
    $stmt = $conn->prepare('SELECT id, name, passw FROM users WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $rem_id);
    $stmt->execute();
    $rem_user = $stmt->get_result()->fetch_assoc();
    
    if ($rem_user) {
        $valid_token = hash('sha256', $rem_user['id'] . $rem_user['passw'] . 'my_secret_key_123');
        if (hash_equals($valid_token, $rem_token)) {
            $_SESSION['user_id'] = (int)$rem_user['id'];
            $_SESSION['user_name'] = (string)$rem_user['name'];
            $_SESSION['last_activity'] = time();
            header('Location: dashboard.php');
            exit;
        }
    }
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $passw = (string)($_POST['passw'] ?? '');

    if ($username === '' || $passw === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $stmt = $conn->prepare('SELECT id, name, passw FROM users WHERE name = ? LIMIT 1');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result ? $result->fetch_assoc() : null;

        $ok = false;
        if ($user) {
            $ok = hash_equals((string)$user['passw'], $passw) || password_verify($passw, (string)$user['passw']);
        }

        if ($ok) {
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['user_name'] = (string)$user['name'];
            $_SESSION['last_activity'] = time();
            
            // Set Remember Me cookie if checked
            if (isset($_POST['remember'])) {
                $token = hash('sha256', $user['id'] . $user['passw'] . 'my_secret_key_123');
                setcookie('remember_id', $user['id'], time() + (86400 * 30), '/'); // 30 hari
                setcookie('remember_token', $token, time() + (86400 * 30), '/');
            }

            header('Location: dashboard.php');
            exit;
        }

        $error = 'Username atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
    <h2>Login</h2>
    <?php if ($error !== ''): ?>
        <p style="color:red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <form method="POST">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="passw" required><br>
        <label style="display: flex; align-items: center; margin-bottom: 15px; font-weight: normal; cursor: pointer;">
            <input type="checkbox" name="remember" style="width: auto; margin-right: 8px; margin-bottom: 0;"> Remember Me
        </label>
        <button type="submit" class="btn">Masuk</button>
    </form>
    <p>Belum punya akun? <a href="register.php">Register</a></p>
    </div>
</body>
</html>

