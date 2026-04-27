<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/User.php';

class AuthController {

    private const SECRET    = 'my_secret_key_123';
    private const TIMEOUT   = 300; // detik idle session

    // ─── Helpers ────────────────────────────────────────────────────────────

    private function startSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }

    private function checkTimeout(): void {
        if (isset($_SESSION['last_activity']) &&
            (time() - $_SESSION['last_activity'] > self::TIMEOUT)) {
            session_unset();
            session_destroy();
            header('Location: index.php?module=auth&act=login');
            exit;
        }
        $_SESSION['last_activity'] = time();
    }

    private function makeToken(array $user): string {
        return hash('sha256', $user['id'] . $user['passw'] . self::SECRET);
    }

    // ─── Form Login ─────────────────────────────────────────────────────────

    public function loginForm(): void {
        $this->startSession();
        if ($this->isLoggedIn()) {
            header('Location: index.php?module=dashboard');
            exit;
        }

        // Auto-login via Remember Me cookie
        if (isset($_COOKIE['remember_id'], $_COOKIE['remember_token'])) {
            $conn   = Database::connect();
            $model  = new User($conn);
            $rem    = $model->getById((int)$_COOKIE['remember_id']);
            if ($rem && hash_equals($this->makeToken($rem), $_COOKIE['remember_token'])) {
                $_SESSION['user_id']       = (int)$rem['id'];
                $_SESSION['user_name']     = (string)$rem['name'];
                $_SESSION['last_activity'] = time();
                header('Location: index.php?module=dashboard');
                exit;
            }
        }

        $error = '';
        include __DIR__ . '/../view/auth/login.php';
    }

    // ─── Proses Login ───────────────────────────────────────────────────────

    public function login(): void {
        $this->startSession();
        if ($this->isLoggedIn()) {
            header('Location: index.php?module=dashboard');
            exit;
        }

        $error    = '';
        $username = trim($_POST['username'] ?? '');
        $passw    = (string)($_POST['passw'] ?? '');

        if ($username === '' || $passw === '') {
            $error = 'Username dan password wajib diisi.';
        } else {
            $model = new User(Database::connect());
            $user  = $model->getByUsername($username);
            $ok    = false;
            if ($user) {
                $ok = hash_equals((string)$user['passw'], $passw)
                   || password_verify($passw, (string)$user['passw']);
            }
            if ($ok) {
                $_SESSION['user_id']       = (int)$user['id'];
                $_SESSION['user_name']     = (string)$user['name'];
                $_SESSION['last_activity'] = time();

                if (!empty($_POST['remember'])) {
                    $token = $this->makeToken($user);
                    setcookie('remember_id',    $user['id'], time() + 86400 * 30, '/');
                    setcookie('remember_token', $token,      time() + 86400 * 30, '/');
                }

                header('Location: index.php?module=dashboard');
                exit;
            }
            $error = 'Username atau password salah.';
        }

        include __DIR__ . '/../view/auth/login.php';
    }

    // ─── Form Register ──────────────────────────────────────────────────────

    public function registerForm(): void {
        $this->startSession();
        if ($this->isLoggedIn()) {
            header('Location: index.php?module=dashboard');
            exit;
        }
        $error = '';
        include __DIR__ . '/../view/auth/register.php';
    }

    // ─── Proses Register ────────────────────────────────────────────────────

    public function register(): void {
        $this->startSession();
        if ($this->isLoggedIn()) {
            header('Location: index.php?module=dashboard');
            exit;
        }

        $error = '';
        $name  = trim($_POST['name']  ?? '');
        $email = trim($_POST['email'] ?? '');
        $passw = (string)($_POST['passw'] ?? '');

        if ($name === '' || $email === '' || $passw === '') {
            $error = 'Semua field wajib diisi.';
        } else {
            $model = new User(Database::connect());
            if ($model->existsByNameOrEmail($name, $email)) {
                $error = 'Username atau Email sudah terdaftar. Silakan login.';
            } else {
                if ($model->insert($name, $email, $passw)) {
                    header('Location: index.php?module=auth&act=login');
                    exit;
                }
                $error = 'Gagal register. Coba lagi.';
            }
        }

        include __DIR__ . '/../view/auth/register.php';
    }

    // ─── Logout ─────────────────────────────────────────────────────────────

    public function logout(): void {
        $this->startSession();
        session_unset();
        session_destroy();
        setcookie('remember_id',    '', time() - 3600, '/');
        setcookie('remember_token', '', time() - 3600, '/');
        header('Location: index.php?module=auth&act=login');
        exit;
    }
}
