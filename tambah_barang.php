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




?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
    <h2>Tambah Barang</h2>
    <form action="proses_upload.php" method="POST" enctype="multipart/form-data">
        Nama: <input type="text" name="nama" required><br>
        Kategori: <input type="text" name="kategori" required><br>
        Stok: <input type="number" name="stok" required><br>
        Harga: <input type="number" name="harga" step="0.01" max="99999999" required><br>
        Foto: <input type="file" name="gambar" accept="image/jpeg,image/png" required><br><br>
        <button type="submit" class="btn">Simpan</button>
        <a href="dashboard.php" class="btn btn-danger" style="margin-left:10px;">Kembali</a>
    </form>
    </div>
</body>
</html>