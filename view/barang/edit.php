<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang — UTSPWL</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Barang</h2>
        <?php if (!empty($error)): ?>
            <p style="color:red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <form action="index.php?module=barang&act=update&id=<?= (int)$barang['id'] ?>"
              method="POST" enctype="multipart/form-data">
            <input type="hidden" name="old_thumb" value="<?= htmlspecialchars($barang['foto'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

            <label>Nama:</label>
            <input type="text" name="nama"
                   value="<?= htmlspecialchars($barang['nama_produk'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>

            <label>Kategori:</label>
            <input type="text" name="kategori"
                   value="<?= htmlspecialchars($barang['kategori'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>

            <label>Stok:</label>
            <input type="number" name="stok" value="<?= (int)($barang['stok'] ?? 0) ?>" min="0" required>

            <label>Harga:</label>
            <input type="number" name="harga" step="1" min="0" max="99999999"
                   value="<?= (float)($barang['harga'] ?? 0) ?>" required>

            <?php if (!empty($barang['foto'])): ?>
            <label>Foto Saat Ini:</label>
            <img src="<?= htmlspecialchars($barang['foto'], ENT_QUOTES, 'UTF-8') ?>" width="100"><br><br>
            <?php endif; ?>

            <label>Ganti Foto (opsional, JPG/PNG, maks 1 MB):</label>
            <input type="file" name="gambar" accept="image/jpeg,image/png">

            <br>
            <button type="submit" class="btn">Update</button>
            <a href="index.php?module=barang" class="btn btn-danger" style="margin-left:10px;">Batal</a>
        </form>
    </div>
</body>
</html>
