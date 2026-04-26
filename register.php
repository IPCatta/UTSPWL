<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $passw = (string)($_POST['passw'] ?? '');

    if ($name === '' || $email === '' || $passw === '') {
        $error = 'Username, email, dan password wajib diisi.';
    } else {
        // cek username atau email sudah terdaftar
        $stmt = $conn->prepare('SELECT id FROM users WHERE name = ? OR email = ? LIMIT 1');
        $stmt->bind_param('ss', $name, $email);
        $stmt->execute();
        $exists = $stmt->get_result();
        if ($exists && $exists->fetch_assoc()) {
            $error = 'Username atau Email sudah terdaftar. Silakan login.';
        } else {
            $hash = password_hash($passw, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('INSERT INTO users (name, email, passw) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $name, $email, $hash);
            if ($stmt->execute()) {
                header('Location: login.php');
                exit;
            }
            $error = 'Gagal register. Coba lagi.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
    <h2>Register Akun</h2>
    <?php if ($error !== ''): ?>
        <p style="color:red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <form method="POST">
        Username: <input type="text" name="name" required><br>
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="passw" required><br>
        <button type="submit">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="login.php">Login</a></p>
    </div>
</body>
</html>

