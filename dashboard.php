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
    <title>Dashboard Utama</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .menu-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 30px 20px;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #1e293b;
            font-weight: 600;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-color: #3b82f6;
            color: #3b82f6;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #3498db; padding-bottom: 10px; margin-bottom: 20px;">
            <h2 style="border: none; padding: 0; margin: 0;">Dashboard</h2>
            <span style="color: #64748b; font-size: 14px;">Halo, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'User', ENT_QUOTES, 'UTF-8') ?></strong>!</span>
        </div>
        
        <p style="color: #475569; margin-bottom: 30px;">Selamat datang di panel kontrol aplikasi. Silakan pilih menu di bawah ini untuk mengelola data Anda.</p>
        
        <div class="menu-grid">
            <a href="tampil_barang.php" class="menu-card">
                <span style="font-size: 32px; margin-bottom: 12px;">📦</span>
                Manajemen Barang
            </a>
            <a href="index.php" class="menu-card">
                <span style="font-size: 32px; margin-bottom: 12px;">👤</span>
                Manajemen User
            </a>
            <a href="create_user.php" class="menu-card">
                <span style="font-size: 32px; margin-bottom: 12px;">👤</span>
                Tambah Pengguna
            </a>
            <a href="logout.php" class="menu-card" style="border-color: #fca5a5; background-color: #fef2f2;">
                <span style="font-size: 32px; margin-bottom: 12px;">🚪</span>
                Logout Keluar
            </a>
        </div>
    </div>
</body>
</html>
