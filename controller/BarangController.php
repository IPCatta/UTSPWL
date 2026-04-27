<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/Barang.php';

class BarangController {

    private const TIMEOUT = 300;

    // ─── Helpers ────────────────────────────────────────────────────────────

    private function requireAuth(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?module=auth&act=login');
            exit;
        }
        if (isset($_SESSION['last_activity']) &&
            (time() - $_SESSION['last_activity'] > self::TIMEOUT)) {
            session_unset();
            session_destroy();
            header('Location: index.php?module=auth&act=login');
            exit;
        }
        $_SESSION['last_activity'] = time();
    }

    // ─── Daftar Barang ──────────────────────────────────────────────────────

    public function index(): void {
        $this->requireAuth();
        $model = new Barang(Database::connect());
        $data  = $model->getAll();
        include __DIR__ . '/../view/barang/index.php';
    }

    // ─── Form Tambah Barang ─────────────────────────────────────────────────

    public function create(): void {
        $this->requireAuth();
        $error = '';
        include __DIR__ . '/../view/barang/create.php';
    }

    // ─── Simpan Barang Baru ─────────────────────────────────────────────────

    public function store(): void {
        $this->requireAuth();
        $nama     = trim($_POST['nama']     ?? '');
        $kategori = trim($_POST['kategori'] ?? '');
        $stok     = (int)($_POST['stok']    ?? 0);
        $harga    = (float)($_POST['harga'] ?? 0);
        $file     = $_FILES['gambar'] ?? null;

        if ($nama === '' || $kategori === '' || $file === null) {
            $error = 'Semua field wajib diisi.';
            include __DIR__ . '/../view/barang/create.php';
            return;
        }

        $model  = new Barang(Database::connect());
        $result = $model->insert($nama, $kategori, $stok, $harga, $file);

        if ($result !== '') {
            $error = $result;
            include __DIR__ . '/../view/barang/create.php';
            return;
        }

        header('Location: index.php?module=barang');
        exit;
    }

    // ─── Form Edit Barang ───────────────────────────────────────────────────

    public function edit(int $id): void {
        $this->requireAuth();
        $model  = new Barang(Database::connect());
        $barang = $model->getById($id);
        if (!$barang) {
            header('Location: index.php?module=barang');
            exit;
        }
        $error = '';
        include __DIR__ . '/../view/barang/edit.php';
    }

    // ─── Update Barang ──────────────────────────────────────────────────────

    public function update(int $id): void {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?module=barang');
            exit;
        }

        $nama     = trim($_POST['nama']     ?? '');
        $kategori = trim($_POST['kategori'] ?? '');
        $stok     = (int)($_POST['stok']    ?? 0);
        $harga    = (float)($_POST['harga'] ?? 0);
        $oldThumb = (string)($_POST['old_thumb'] ?? '');
        $file     = $_FILES['gambar'] ?? null;

        if ($nama === '' || $id <= 0) {
            header('Location: index.php?module=barang');
            exit;
        }

        $model  = new Barang(Database::connect());
        $result = $model->update($id, $nama, $kategori, $stok, $harga, $file, $oldThumb);

        if ($result !== '') {
            $barang = $model->getById($id);
            $error  = $result;
            include __DIR__ . '/../view/barang/edit.php';
            return;
        }

        header('Location: index.php?module=barang');
        exit;
    }

    // ─── Hapus Barang ───────────────────────────────────────────────────────

    public function delete(): void {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?module=barang');
            exit;
        }
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $model = new Barang(Database::connect());
            $model->delete($id);
        }
        header('Location: index.php?module=barang');
        exit;
    }
}
