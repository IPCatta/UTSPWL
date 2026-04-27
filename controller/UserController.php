<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/User.php';

class UserController {

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

    // ─── Daftar User ────────────────────────────────────────────────────────

    public function index(): void {
        $this->requireAuth();
        $model = new User(Database::connect());
        $data  = $model->getAll();
        include __DIR__ . '/../view/user/welcome.php';
    }

    // ─── Form Tambah User ───────────────────────────────────────────────────

    public function create(): void {
        $this->requireAuth();
        $error = '';
        include __DIR__ . '/../view/user/create.php';
    }

    // ─── Simpan User Baru ───────────────────────────────────────────────────

    public function store(): void {
        $this->requireAuth();
        $error = '';
        $name  = trim($_POST['name']  ?? '');
        $email = trim($_POST['email'] ?? '');
        $passw = (string)($_POST['passw'] ?? '');

        if ($name === '' || $email === '' || $passw === '') {
            $error = 'Semua field wajib diisi.';
            include __DIR__ . '/../view/user/create.php';
            return;
        }

        $model = new User(Database::connect());
        if ($model->insert($name, $email, $passw)) {
            header('Location: index.php?module=user');
            exit;
        }
        $error = 'Gagal menyimpan data. Coba lagi.';
        include __DIR__ . '/../view/user/create.php';
    }

    // ─── Form Edit User ─────────────────────────────────────────────────────

    public function edit(int $id): void {
        $this->requireAuth();
        $model = new User(Database::connect());
        $user  = $model->getById($id);
        if (!$user) {
            header('Location: index.php?module=user');
            exit;
        }
        $error = '';
        include __DIR__ . '/../view/user/edit.php';
    }

    // ─── Update User ────────────────────────────────────────────────────────

    public function update(int $id): void {
        $this->requireAuth();
        $model = new User(Database::connect());
        $user  = $model->getById($id);
        if (!$user) {
            header('Location: index.php?module=user');
            exit;
        }

        $name  = trim($_POST['name']  ?? '');
        $email = trim($_POST['email'] ?? '');
        $passw = (string)($_POST['passw'] ?? '');

        if ($name === '' || $email === '') {
            $error = 'Nama dan email wajib diisi.';
            include __DIR__ . '/../view/user/edit.php';
            return;
        }

        // Jika password dikosongkan, gunakan hash lama
        $finalPassw = ($passw === '') ? $user['passw'] : $passw;
        $model->update($id, $name, $email, $finalPassw, $user['passw']);
        header('Location: index.php?module=user');
        exit;
    }

    // ─── Hapus User ─────────────────────────────────────────────────────────

    public function delete(): void {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?module=user');
            exit;
        }
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $model = new User(Database::connect());
            $model->delete($id);
        }
        header('Location: index.php?module=user');
        exit;
    }
}
