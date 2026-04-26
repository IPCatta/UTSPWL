
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
error_reporting(E_ALL);
ini_set('display_errors', 1);

$nama     = htmlspecialchars($_POST['nama']);
$kategori = htmlspecialchars($_POST['kategori']);
$stok     = (int)$_POST['stok'];
$harga    = (float)$_POST['harga'];

// =============================
// FOLDER
// =============================
$dir_original = "uploads/original/";
$dir_thumb    = "uploads/thumbs/";

if (!is_dir($dir_original)) mkdir($dir_original, 0777, true);
if (!is_dir($dir_thumb)) mkdir($dir_thumb, 0777, true);

// =============================
// VALIDASI UPLOAD
// =============================
if ($_FILES['gambar']['error'] !== 0) {
    die("Error upload: " . $_FILES['gambar']['error']);
}

if ($_FILES['gambar']['size'] > 1000000) {
    die("File terlalu besar (max 1MB)");
}

// =============================
// NAMA FILE
// =============================
$filename = time() . "_" . basename($_FILES['gambar']['name']);
$path_original = $dir_original . $filename;

// =============================
// UPLOAD ORIGINAL
// =============================
if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $path_original)) {
    die("Gagal upload file!");
}

// =============================
// DETEKSI MIME
// =============================
$mime = mime_content_type($path_original);
if (!in_array($mime, ['image/jpeg', 'image/png'], true)) {
    unlink($path_original);
    die("Format tidak didukung!");
}

// =============================
// BUAT THUMBNAIL (100x100)
// =============================
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

// =============================
// SIMPAN DATABASE
// =============================
$stmt = $conn->prepare("INSERT INTO products(nama_produk,kategori,stok,harga,foto) VALUES(?,?,?,?,?)");
$stmt->bind_param("ssids",
    $nama,
    $kategori,
    $stok,
    $harga,
    $path_thumb
);
$stmt->execute();

header("Location: dashboard.php");
exit;
