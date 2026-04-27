<?php
/**
 * index.php — Router utama aplikasi UTSPWL
 *
 * URL pattern: index.php?module=<modul>&act=<aksi>&id=<id>
 *
 * Module:
 *   auth    → AuthController   (login, register, logout)
 *   user    → UserController   (index, create, store, edit, update, delete)
 *   barang  → BarangController (index, create, store, edit, update, delete)
 *   dashboard (default)        → view/dashboard/index.php
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$module = $_GET['module'] ?? 'dashboard';
$act    = $_GET['act']    ?? 'index';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : null;

// ─── Routing ────────────────────────────────────────────────────────────────

switch ($module) {

    // ── Auth ────────────────────────────────────────────────────────────────
    case 'auth':
        require_once __DIR__ . '/controller/AuthController.php';
        $c = new AuthController();
        switch ($act) {
            case 'login':
                ($_SERVER['REQUEST_METHOD'] === 'POST') ? $c->login() : $c->loginForm();
                break;
            case 'register':
                ($_SERVER['REQUEST_METHOD'] === 'POST') ? $c->register() : $c->registerForm();
                break;
            case 'logout':
                $c->logout();
                break;
            default:
                $c->loginForm();
        }
        break;

    // ── User ────────────────────────────────────────────────────────────────
    case 'user':
        require_once __DIR__ . '/controller/UserController.php';
        $c = new UserController();
        switch ($act) {
            case 'create': $c->create();          break;
            case 'store':  $c->store();           break;
            case 'edit':   $c->edit($id ?? 0);   break;
            case 'update': $c->update($id ?? 0); break;
            case 'delete': $c->delete(); break;
            default:       $c->index();
        }
        break;

    // ── Barang ──────────────────────────────────────────────────────────────
    case 'barang':
        require_once __DIR__ . '/controller/BarangController.php';
        $c = new BarangController();
        switch ($act) {
            case 'create': $c->create();          break;
            case 'store':  $c->store();           break;
            case 'edit':   $c->edit($id ?? 0);   break;
            case 'update': $c->update($id ?? 0); break;
            case 'delete': $c->delete();          break;
            default:       $c->index();
        }
        break;

    // ── Dashboard (default) ─────────────────────────────────────────────────
    default:
        // Wajib login
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?module=auth&act=login');
            exit;
        }
        $timeout = 300;
        if (isset($_SESSION['last_activity']) &&
            (time() - $_SESSION['last_activity'] > $timeout)) {
            session_unset();
            session_destroy();
            header('Location: index.php?module=auth&act=login');
            exit;
        }
        $_SESSION['last_activity'] = time();

        // Ambil data ringkasan untuk dashboard
        require_once __DIR__ . '/config/database.php';
        require_once __DIR__ . '/model/Barang.php';
        require_once __DIR__ . '/model/User.php';
        $db = Database::connect();
        $barangModel = new Barang($db);
        $userModel   = new User($db);
        $summary    = $barangModel->getSummary();
        $totalUsers = $userModel->getCount();

        include __DIR__ . '/view/dashboard/dashboard.php';
        break;
}