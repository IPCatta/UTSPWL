<!DOCTYPE html>
<html>
<head>
    <title>Daftar User</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Daftar User</h2>
        <div class="actions">
            <a href="index.php?action=create" class="btn">+ Tambah User</a>
            <a href="dashboard.php" class="btn btn-danger" style="margin-left:10px;">Kembali</a>
        </div>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Aksi</th>
            </tr>
            <?php while($row = $data->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td>
                    <a href="index.php?action=edit&id=<?= $row['id'] ?>" class="btn btn-small">Edit</a>
                    <a href="index.php?action=delete&id=<?= $row['id'] ?>" class="btn btn-small btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
