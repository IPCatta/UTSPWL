<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang — UTSPWL</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Tambah Barang</h2>
        <?php if (!empty($error)): ?>
            <p style="color:red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <form action="index.php?module=barang&act=store" method="POST" enctype="multipart/form-data">
            <label>Nama:</label>
            <input type="text" name="nama" required>

            <label>Kategori:</label>
            <input type="text" name="kategori" required>

            <label>Stok:</label>
            <input type="number" name="stok" min="0" required>

            <label>Harga:</label>
            <input type="number" name="harga" step="1" min="0" max="99999999" required>

            <label>Foto (JPG/PNG, maks 1 MB):</label>
            <input type="file" name="gambar" accept="image/jpeg,image/png" required>

            <br>
            <button type="submit" class="btn">Simpan</button>
            <a href="index.php?module=barang" class="btn btn-danger" style="margin-left:10px;">Batal</a>
        </form>
    </div>
</body>
</html>
