<?php
class Database {
    private static $host = 'localhost';
    private static $user = 'root';
    private static $pass = '';
    private static $db   = 'penyimpanan';
    private static $instance = null;

    /**
     * Singleton — satu koneksi selama request berlangsung
     */
    public static function connect(): mysqli {
        if (self::$instance === null) {
            $conn = new mysqli(self::$host, self::$user, self::$pass, self::$db);
            if ($conn->connect_error) {
                die('Koneksi database gagal: ' . $conn->connect_error);
            }
            $conn->set_charset('utf8mb4');
            self::$instance = $conn;
        }
        return self::$instance;
    }
}
