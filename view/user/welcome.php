<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar User — UTSPWL</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Daftar User</h2>
        <div class="actions">
            <a href="index.php?module=user&act=create" class="btn">+ Tambah User</a>
            <a href="index.php?module=dashboard" class="btn btn-danger" style="margin-left:10px;">Kembali</a>
        </div>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = $data->fetch_assoc()): ?>
            <tr>
                <td><?= (int)$row['id'] ?></td>
                <td><?= htmlspecialchars($row['name'],  ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row['email'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                    <a href="index.php?module=user&act=edit&id=<?= (int)$row['id'] ?>" class="btn btn-small">Edit</a>
                    <form action="index.php?module=user&act=delete" method="POST"
                          style="display:inline"
                          onsubmit="return confirm('Yakin hapus user ini?')">
                        <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                        <button type="submit" class="btn btn-small btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
