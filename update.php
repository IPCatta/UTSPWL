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

$id = (int)($_POST['id'] ?? 0);
$nama = trim($_POST['nama'] ?? '');
$kategori = trim($_POST['kategori'] ?? '');
$stok = (int)($_POST['stok'] ?? 0);
$harga = (float)($_POST['harga'] ?? 0);
$oldThumb = (string)($_POST['old_thumb'] ?? '');

if ($id <= 0 || $nama === '') {
    header('Location: dashboard.php');
    exit;
}

$newThumbPath = null;
if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
        die("Error upload: " . $_FILES['gambar']['error']);
    }
    if ($_FILES['gambar']['size'] > 1000000) {
        die("File terlalu besar (max 1MB)");
    }

    $dir_original = "uploads/original/";
    $dir_thumb    = "uploads/thumbs/";
    if (!is_dir($dir_original)) mkdir($dir_original, 0777, true);
    if (!is_dir($dir_thumb)) mkdir($dir_thumb, 0777, true);

    $filename = time() . "_" . basename($_FILES['gambar']['name']);
    $path_original = $dir_original . $filename;

    if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $path_original)) {
        die("Gagal upload file!");
    }

    $mime = mime_content_type($path_original);
    if (!in_array($mime, ['image/jpeg', 'image/png'], true)) {
        unlink($path_original);
        die("Format tidak didukung!");
    }

    switch ($mime) {
        case 'image/jpeg':
            $src = imagecreatefromjpeg($path_original);
            break;
        case 'image/png':
            $src = imagecreatefrompng($path_original);
            break;
        default:
            $src = null;
            break;
    }

    if (!$src) {
        unlink($path_original);
        die("Gambar tidak valid!");
    }

    $width  = imagesx($src);
    $height = imagesy($src);

    $tmp = imagecreatetruecolor(100, 100);
    imagecopyresampled($tmp, $src, 0, 0, 0, 0, 100, 100, $width, $height);

    $path_thumb = $dir_thumb . "thumb_" . $filename;
    imagejpeg($tmp, $path_thumb, 80);
    imagedestroy($src);
    imagedestroy($tmp);

    $newThumbPath = $path_thumb;
}

if ($newThumbPath !== null) {
    $stmt = $conn->prepare('UPDATE products SET nama_produk=?, kategori=?, stok=?, harga=?, foto=? WHERE id=?');
    $stmt->bind_param('ssidsi', $nama, $kategori, $stok, $harga, $newThumbPath, $id);
    $stmt->execute();

    if ($oldThumb !== '' && file_exists($oldThumb)) {
        unlink($oldThumb);
        $base = basename($oldThumb);
        if (str_starts_with($base, 'thumb_')) {
            $base = substr($base, strlen('thumb_'));
        }
        $oldOriginal = "uploads/original/" . $base;
        if (file_exists($oldOriginal)) {
            unlink($oldOriginal);
        }
    }
} else {
    $stmt = $conn->prepare('UPDATE products SET nama_produk=?, kategori=?, stok=?, harga=? WHERE id=?');
    $stmt->bind_param('ssidi', $nama, $kategori, $stok, $harga, $id);
    $stmt->execute();
}

header('Location: dashboard.php');
exit;

