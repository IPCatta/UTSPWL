<?php
class Database {
    private static $host = 'localhost';
    private static $user = 'root';
    private static $pass = '';
    private static $db = 'penyimpanan';

    public static function connect() {
        $conn = new mysqli(self::$host, self::$user, self::$pass, self::$db);
        
        if ($conn->connect_error) {
            die('Koneksi database gagal: ' . $conn->connect_error);
        }
        
        $conn->set_charset('utf8mb4');
        return $conn;
    }
}
?>
