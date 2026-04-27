<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang — UTSPWL</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Data Inventaris (Barang)</h2>
        <p>Halo, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User', ENT_QUOTES, 'UTF-8') ?>!</p>
        <div class="actions">
            <a href="index.php?module=barang&act=create" class="btn">+ Tambah Barang</a>
            <a href="index.php?module=dashboard" class="btn btn-danger" style="margin-left:10px;">Kembali</a>
        </div>

        <table>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Harga</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
            <?php $no = 1; ?>
            <?php if ($data): while ($d = $data->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($d['nama_produk'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($d['kategori']   ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= (int)($d['stok'] ?? 0) ?></td>
                <td>Rp <?= number_format((float)($d['harga'] ?? 0), 0, ',', '.') ?></td>
                <td>
                    <?php if (!empty($d['foto'])): ?>
                        <img src="<?= htmlspecialchars($d['foto'], ENT_QUOTES, 'UTF-8') ?>" width="80">
                    <?php endif; ?>
                </td>
                <td>
                    <a href="index.php?module=barang&act=edit&id=<?= (int)$d['id'] ?>"
                       class="btn btn-small">Edit</a>
                    <form action="index.php?module=barang&act=delete" method="POST"
                          style="display:inline"
                          onsubmit="return confirm('Yakin hapus barang ini?')">
                        <input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
                        <button type="submit" class="btn btn-small btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; endif; ?>
        </table>
    </div>
</body>
</html>
