<?php
class User {
    public $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        return $this->conn->query("SELECT * FROM users");
    }

    public function getById($id) {
        $result = $this->conn->query("SELECT * FROM users WHERE id = $id");
        return $result->fetch_assoc();
    }

    public function insert($nama) {
        $nama = $this->conn->real_escape_string($nama);
        return $this->conn->query("INSERT INTO users (name) VALUES ('$nama')");
    }

    public function update($id, $nama) {
        $id = (int)$id;
        $nama = $this->conn->real_escape_string($nama);
        return $this->conn->query("UPDATE users SET name = '$nama' WHERE id = $id");
    }

    public function delete($id) {
        $id = (int)$id;
        return $this->conn->query("DELETE FROM users WHERE id = $id");
    }
}
?>
