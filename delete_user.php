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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id > 0) {
    $conn->query("DELETE FROM users WHERE id=$id");
}

header('Location: dashboard.php');
exit;
