<?php
class User {
    private mysqli $conn;

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    /** Hitung total user */
    public function getCount(): int {
        $row = $this->conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc();
        return (int)($row['total'] ?? 0);
    }

    /** Ambil semua user */
    public function getAll(): mysqli_result|false {
        return $this->conn->query("SELECT id, name, email FROM users ORDER BY id DESC");
    }

    /** Ambil user berdasarkan ID */
    public function getById(int $id): array|null {
        $stmt = $this->conn->prepare("SELECT id, name, email, passw FROM users WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    /** Ambil user berdasarkan username */
    public function getByUsername(string $name): array|null {
        $stmt = $this->conn->prepare("SELECT id, name, passw FROM users WHERE name = ? LIMIT 1");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    /** Cek apakah username/email sudah ada */
    public function existsByNameOrEmail(string $name, string $email): bool {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE name = ? OR email = ? LIMIT 1");
        $stmt->bind_param('ss', $name, $email);
        $stmt->execute();
        return (bool)$stmt->get_result()->fetch_assoc();
    }

    /** Tambah user baru */
    public function insert(string $name, string $email, string $passw): bool {
        $hash = password_hash($passw, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, passw) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $name, $email, $hash);
        return $stmt->execute();
    }

    /** Update user (password di-hash kalau berubah) */
    public function update(int $id, string $name, string $email, string $passw, string $oldHash): bool {
        // Hash password baru hanya jika berbeda dari hash lama
        if (!password_verify($passw, $oldHash) && $passw !== $oldHash) {
            $passw = password_hash($passw, PASSWORD_DEFAULT);
        } else {
            $passw = $oldHash; // tidak berubah
        }
        $stmt = $this->conn->prepare("UPDATE users SET name=?, email=?, passw=? WHERE id=?");
        $stmt->bind_param('sssi', $name, $email, $passw, $id);
        return $stmt->execute();
    }

    /** Hapus user */
    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
