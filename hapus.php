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

    $id = (int)($_POST['id'] ?? 0);
    $thumb = (string)($_POST['thumb'] ?? '');

    if ($thumb !== '' && file_exists($thumb)) {
        unlink($thumb);
    }

    // hapus original kalau ada file dengan nama yang sama di uploads/original/
    $original = '';
    if ($thumb !== '') {
        $base = basename($thumb);
        if (str_starts_with($base, 'thumb_')) {
            $base = substr($base, strlen('thumb_'));
        }
        $original = "uploads/original/" . $base;
    }
    if ($original !== '' && file_exists($original)) {
        unlink($original);
    }

    $conn->query("DELETE FROM products WHERE id=$id");
    header("Location: dashboard.php");
?>
