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
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $passw = password_hash($_POST['passw'], PASSWORD_DEFAULT);

    // Menggunakan prepared statement untuk keamanan (mencegah SQL Injection)
    // Catatan: Jika kolom 'email' belum ada di database, ini akan error. 
    $stmt = $conn->prepare("INSERT INTO users (name, email, passw) VALUES (?, ?, ?)");
    
    if ($stmt) {
        $stmt->bind_param("sss", $name, $email, $passw);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $error_msg = "Gagal menyimpan data: " . $stmt->error;
        }
    } else {
        $error_msg = "Error pada query: " . $conn->error . " (Apakah kolom 'email' sudah dibuat di tabel users?)";
    }
}
?>
<!DOCTYPE html>
<head>
    <title>Tambah Pengguna</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
 <h2>Tambah Pengguna</h2>
 <?php if ($error_msg !== ""): ?>
    <p style="color: red;"><?= $error_msg ?></p>
 <?php endif; ?>
 <form method="POST">
 Nama: <input type="text" name="name" required><br>
 Email: <input type="email" name="email" required><br>
 Password: <input type="password" name="passw" required><br>
     <button class="btn" type="submit">Simpan</button>
     <a href="dashboard.php" class="btn btn-danger" style="margin-left:10px;">Batal</a>
 </form>
 </div>
</body>
</html>
