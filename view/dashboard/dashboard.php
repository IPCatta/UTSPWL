<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — UTSPWL</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?v=1.1">
    <style>
        /* ── Reset & Base ─────────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Noto Sans", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji";
            background-color: #f6f8fa;
            min-height: 100vh;
            color: #24292f;
            padding: 0;
            overflow-x: hidden;
        }

        /* animated bg blobs (adjusted for light theme) */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.1;
            z-index: 0;
            pointer-events: none;
        }
        body::before {
            width: 600px; height: 600px;
            background: #0969da;
            top: -150px; left: -100px;
            animation: floatBlob 18s ease-in-out infinite alternate;
        }
        body::after {
            width: 500px; height: 500px;
            background: #2da44e;
            bottom: -100px; right: -80px;
            animation: floatBlob 22s ease-in-out infinite alternate-reverse;
        }

        @keyframes floatBlob {
            0%   { transform: translate(0, 0) scale(1); }
            100% { transform: translate(60px, 40px) scale(1.15); }
        }

        /* ── Dashboard Wrapper ────────────────────────────────────────── */
        .dashboard-wrapper {
            position: relative;
            z-index: 1;
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 24px;
        }

        /* ── Header ───────────────────────────────────────────────────── */
        .dash-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        .dash-header h1 {
            font-size: 28px;
            font-weight: 600;
            color: #24292f;
        }
        .user-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #ffffff;
            border: 1px solid #d0d7de;
            border-radius: 50px;
            padding: 8px 18px 8px 10px;
        }
        .user-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: #0969da;
            display: flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: 14px; color: #ffffff;
        }
        .user-badge span { font-size: 14px; font-weight: 500; color: #57606a; }
        .user-badge strong { color: #24292f; }

        .dash-subtitle {
            color: #57606a;
            font-size: 15px;
            margin-bottom: 36px;
        }

        /* ── Stats Grid ───────────────────────────────────────────────── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            position: relative;
            background: #ffffff;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            padding: 24px;
            overflow: hidden;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .stat-card:hover {
            transform: translateY(-2px);
            border-color: #0969da;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 3px;
        }
        .stat-card:nth-child(1)::before { background: #0969da; }
        .stat-card:nth-child(2)::before { background: #2da44e; }
        .stat-card:nth-child(3)::before { background: #bf8700; }
        .stat-card:nth-child(4)::before { background: #8250df; }

        .stat-icon {
            width: 44px; height: 44px;
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            margin-bottom: 16px;
        }
        .stat-card:nth-child(1) .stat-icon { background: rgba(9,105,218,0.1); }
        .stat-card:nth-child(2) .stat-icon { background: rgba(45,164,78,0.1); }
        .stat-card:nth-child(3) .stat-icon { background: rgba(191,135,0,0.1); }
        .stat-card:nth-child(4) .stat-icon { background: rgba(130,80,223,0.1); }

        .stat-label {
            font-size: 13px;
            font-weight: 500;
            color: #57606a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .stat-value {
            font-size: 26px;
            font-weight: 600;
            color: #24292f;
            line-height: 1;
        }

        /* ── Section Title ────────────────────────────────────────────── */
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #24292f;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-title::before {
            content: '';
            width: 4px; height: 20px;
            background: #0969da;
            border-radius: 4px;
        }

        /* ── Menu Grid ────────────────────────────────────────────────── */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .menu-card {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 14px;
            padding: 24px;
            background: #ffffff;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            text-decoration: none;
            color: #24292f;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }
        .menu-card::after {
            content: '→';
            position: absolute;
            top: 24px; right: 24px;
            font-size: 18px;
            color: #57606a;
            transition: all 0.2s ease;
        }
        .menu-card:hover {
            transform: translateY(-2px);
            border-color: #0969da;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            text-decoration: none;
        }
        .menu-card:hover::after {
            color: #0969da;
            transform: translateX(4px);
        }

        .menu-icon {
            width: 48px; height: 48px;
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            transition: transform 0.2s ease;
        }
        .menu-card:hover .menu-icon {
            transform: scale(1.05);
        }
        .menu-card:nth-child(1) .menu-icon { background: rgba(9,105,218,0.1); }
        .menu-card:nth-child(2) .menu-icon { background: rgba(45,164,78,0.1); }
        .menu-card:nth-child(3) .menu-icon { background: rgba(191,135,0,0.1); }

        .menu-label {
            font-weight: 600;
            font-size: 15px;
        }
        .menu-desc {
            font-size: 13px;
            color: #57606a;
            line-height: 1.4;
        }

        /* ── Logout Card ──────────────────────────────────────────────── */
        .menu-card.logout-card {
            border-color: #d0d7de;
        }
        .menu-card.logout-card:hover {
            border-color: #cf222e;
        }
        .menu-card.logout-card .menu-icon {
            background: rgba(207,34,46,0.1);
        }

        /* ── Footer ───────────────────────────────────────────────────── */
        .dash-footer {
            margin-top: 48px;
            text-align: center;
            font-size: 13px;
            color: #57606a;
        }

        /* ── Counter Animation ────────────────────────────────────────── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .stat-card, .menu-card {
            animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) backwards;
        }
        .stat-card:nth-child(1) { animation-delay: 0.05s; }
        .stat-card:nth-child(2) { animation-delay: 0.1s; }
        .stat-card:nth-child(3) { animation-delay: 0.15s; }
        .stat-card:nth-child(4) { animation-delay: 0.2s; }
        .menu-card:nth-child(1) { animation-delay: 0.25s; }
        .menu-card:nth-child(2) { animation-delay: 0.3s; }
        .menu-card:nth-child(3) { animation-delay: 0.35s; }
        .menu-card:nth-child(4) { animation-delay: 0.4s; }

        /* ── Responsive ───────────────────────────────────────────────── */
        @media (max-width: 600px) {
            .dashboard-wrapper { padding: 24px 16px; }
            .dash-header { flex-direction: column; align-items: flex-start; gap: 12px; }
            .dash-header h1 { font-size: 24px; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .stat-value { font-size: 22px; }
            .menu-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Header -->
        <div class="dash-header">
            <h1>📊 Dashboard</h1>
            <div class="user-badge">
                <div class="user-avatar">
                    <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                </div>
                <span>Halo, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'User', ENT_QUOTES, 'UTF-8') ?></strong></span>
            </div>
        </div>
        <p class="dash-subtitle">Selamat datang kembali! Berikut ringkasan data terkini.</p>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card" id="stat-items">
                <div class="stat-icon">📦</div>
                <div class="stat-label">Total Barang</div>
                <div class="stat-value" data-target="<?= (int)($summary['total_items'] ?? 0) ?>">0</div>
            </div>
            <div class="stat-card" id="stat-stock">
                <div class="stat-icon">🏷️</div>
                <div class="stat-label">Total Stok</div>
                <div class="stat-value" data-target="<?= (int)($summary['total_stok'] ?? 0) ?>">0</div>
            </div>
            <div class="stat-card" id="stat-value">
                <div class="stat-icon">💰</div>
                <div class="stat-label">Total Harga</div>
                <div class="stat-value" data-target="<?= (int)($summary['total_harga'] ?? 0) ?>" data-prefix="Rp ">Rp 0</div>
            </div>
            <div class="stat-card" id="stat-users">
                <div class="stat-icon">👥</div>
                <div class="stat-label">Total User</div>
                <div class="stat-value" data-target="<?= (int)($totalUsers ?? 0) ?>">0</div>
            </div>
        </div>

        <!-- Quick Menu (Horizontal Layout) -->
        <div class="section-title">Menu Utama (Horizontal)</div>
        <div class="menu-grid" style="display: flex; flex-direction: row; gap: 16px; overflow-x: auto; padding-bottom: 15px;">
            <a href="index.php?module=barang" class="menu-card" style="flex: 1; min-width: 220px; display: flex; flex-direction: column; align-items: flex-start; gap: 10px;">
                <div class="menu-icon" style="background: rgba(9,105,218,0.1); color: #0969da;">📦</div>
                <div class="menu-label" style="font-weight: 600; font-size: 15px;">Manajemen Barang</div>
                <div class="menu-desc" style="font-size: 13px; color: #57606a;">Kelola produk, stok, dan harga</div>
            </a>
            <a href="index.php?module=user" class="menu-card" style="flex: 1; min-width: 220px; display: flex; flex-direction: column; align-items: flex-start; gap: 10px;">
                <div class="menu-icon" style="background: rgba(45,164,78,0.1); color: #2da44e;">👤</div>
                <div class="menu-label" style="font-weight: 600; font-size: 15px;">Manajemen User</div>
                <div class="menu-desc" style="font-size: 13px; color: #57606a;">Kelola akun pengguna</div>
            </a>
            <a href="index.php?module=user&act=create" class="menu-card" style="flex: 1; min-width: 220px; display: flex; flex-direction: column; align-items: flex-start; gap: 10px;">
                <div class="menu-icon" style="background: rgba(191,135,0,0.1); color: #bf8700;">➕</div>
                <div class="menu-label" style="font-weight: 600; font-size: 15px;">Tambah Pengguna</div>
                <div class="menu-desc" style="font-size: 13px; color: #57606a;">Buat akun baru</div>
            </a>
            <a href="index.php?module=auth&act=logout" class="menu-card logout-card" style="flex: 1; min-width: 220px; display: flex; flex-direction: column; align-items: flex-start; gap: 10px;">
                <div class="menu-icon" style="background: rgba(207,34,46,0.1); color: #cf222e;">🚪</div>
                <div class="menu-label" style="font-weight: 600; font-size: 15px;">Logout</div>
                <div class="menu-desc" style="font-size: 13px; color: #57606a;">Keluar dari aplikasi</div>
            </a>
        </div>

        <div class="dash-footer">
            &copy; <?= date('Y') ?> UTSPWL &mdash; Sistem Manajemen Inventaris
        </div>
    </div>

    <script>
        // Animated counter for stat values
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.stat-value[data-target]').forEach(el => {
                const target = parseInt(el.dataset.target, 10);
                const prefix = el.dataset.prefix || '';
                const duration = 1200;
                const start = performance.now();

                function update(now) {
                    const elapsed = now - start;
                    const progress = Math.min(elapsed / duration, 1);
                    // ease-out cubic
                    const ease = 1 - Math.pow(1 - progress, 3);
                    const current = Math.round(target * ease);
                    el.textContent = prefix + current.toLocaleString('id-ID');
                    if (progress < 1) requestAnimationFrame(update);
                }
                requestAnimationFrame(update);
            });
        });
    </script>
</body>
</html>
