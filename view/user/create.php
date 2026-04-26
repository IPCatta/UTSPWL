<!DOCTYPE html>
<html>
<head>
    <title>Tambah User</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Tambah User</h2>
        <form action="index.php?action=store" method="POST">
            <label>Nama User:</label>
            <input type="text" name="nama" placeholder="Masukkan nama..." required>
            <button type="submit" class="btn">Simpan</button>
            <a href="index.php" class="btn btn-danger" style="margin-left:10px;">Batal</a>
        </form>
    </div>
</body>
</html>
