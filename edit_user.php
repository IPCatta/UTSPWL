<?php
session_start();

$timeout = 300; // waktu idle

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// cek apakah session sudah lewat waktu dari awal login
if (isset($_SESSION["login_time"]) && (time() - $_SESSION["login_time"] > $timeout)) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

include 'koneksi.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $passw = $_POST['passw'];
    
    // Hash password if changed, else keep old
    if ($passw !== $user['passw'] && !empty($passw)) {
        $passw = password_hash($passw, PASSWORD_DEFAULT);
    }
    
    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, passw=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $email, $passw, $id);
    $stmt->execute();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Pengguna</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Pengguna</h2>
        <form method="POST">
            <label>Nama:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
            
            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
            
            <label>Password (Kosongkan jika tidak ingin mengubah):</label>
            <input type="password" name="passw" value="<?= htmlspecialchars($user['passw'] ?? '') ?>" required>
            
            <button type="submit" class="btn">Update</button>
            <a href="index.php" class="btn btn-danger" style="margin-left:10px;">Batal</a>
        </form>
    </div>
</body>
</html>