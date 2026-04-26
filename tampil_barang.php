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





$q = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Barang</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
    <h2>Data Inventaris (Barang)</h2>
    <p>Halo, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User', ENT_QUOTES, 'UTF-8') ?>!</p>
    <div class="actions">
        <a href="tambah_barang.php" class="btn">+ Tambah Barang</a>
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

        <?php
        $no = 1;
        if ($q) {
            while ($d = $q->fetch_assoc()) {
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($d['nama_produk'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($d['kategori'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= (int)($d['stok'] ?? 0) ?></td>
                <td>Rp <?= number_format((float)($d['harga'] ?? 0), 0, ',', '.') ?></td>
                <td>
                    <?php if (!empty($d['foto'])): ?>
                        <img src="<?= htmlspecialchars($d['foto'], ENT_QUOTES, 'UTF-8') ?>" width="80">
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit_barang.php?id=<?= (int)$d['id'] ?>" class="btn btn-small">EDIT</a>
                    <form action="hapus.php" method="POST" style="display:inline" onsubmit="return confirm('Yakin hapus?')">
                        <input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
                        <input type="hidden" name="thumb" value="<?= htmlspecialchars($d['foto'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        <button class="btn btn-small btn-danger" type="submit">DELETE</button>
                    </form>
                </td>
            </tr>
        <?php
            }
        }
        ?>
    </table>

    <div style="margin-top:20px;">
        <a href="dashboard.php" class="btn">Kembali ke Dashboard</a>
        <a href="logout.php" class="btn btn-danger" style="margin-left: 10px;">Logout</a>
    </div>
    </div>
</body>
</html>