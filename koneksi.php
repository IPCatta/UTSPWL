<?php
// Database connection
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'penyimpanan';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die('Koneksi database gagal: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');

