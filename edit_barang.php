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
$d = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Koreksi Barang</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Koreksi Barang</h2>
        <form action="update.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $d['id'] ?>">
        <input type="hidden" name="old_thumb" value="<?= $d['foto'] ?>">

        <label>Nama:</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($d['nama_produk']) ?>"><br>
        <label>Kategori:</label>
        <input type="text" name="kategori" value="<?= htmlspecialchars($d['kategori']) ?>"><br>
        <label>Stok:</label>
        <input type="number" name="stok" value="<?= $d['stok'] ?>"><br>
        <label>Harga:</label>
        <input type="number" name="harga" step="0.01" max="99999999" value="<?= $d['harga'] ?>"><br>

        <label>Foto Lama (thumbnail):</label>
        <img src="<?= htmlspecialchars($d['foto']) ?>" width="100"><br><br>

        <label>Ganti Foto (opsional):</label>
        <input type="file" name="gambar" accept="image/jpeg,image/png"><br><br>

        <button class="btn" type="submit">Update</button> 
        <a href="dashboard.php" class="btn btn-danger" style="margin-left:10px;">Batal</a>
        </form>
    </div>
</body>
</html>