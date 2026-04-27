<?php
class Barang {
    private mysqli $conn;

    private string $dirOriginal = 'uploads/original/';
    private string $dirThumb    = 'uploads/thumbs/';

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    /** Ambil semua barang */
    public function getAll(): mysqli_result|false {
        return $this->conn->query("SELECT * FROM products ORDER BY id DESC");
    }

    /** Ambil satu barang berdasarkan ID */
    public function getById(int $id): array|null {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    /**
     * Simpan barang baru beserta upload foto.
     * Mengembalikan string error atau '' jika sukses.
     */
    public function insert(string $nama, string $kategori, int $stok, float $harga, array $file): string {
        $thumbPath = $this->handleUpload($file);
        if (!str_starts_with($thumbPath, 'uploads/')) {
            return $thumbPath; // berisi pesan error
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO products (nama_produk, kategori, stok, harga, foto) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('ssids', $nama, $kategori, $stok, $harga, $thumbPath);
        $stmt->execute();
        return '';
    }

    /**
     * Update barang. $file bisa null jika foto tidak diganti.
     * Mengembalikan string error atau '' jika sukses.
     */
    public function update(int $id, string $nama, string $kategori, int $stok, float $harga, ?array $file, string $oldThumb): string {
        $newThumb = null;

        if ($file !== null && $file['error'] !== UPLOAD_ERR_NO_FILE) {
            $result = $this->handleUpload($file);
            if (!str_starts_with($result, 'uploads/')) {
                return $result; // error
            }
            $newThumb = $result;
        }

        if ($newThumb !== null) {
            $stmt = $this->conn->prepare(
                "UPDATE products SET nama_produk=?, kategori=?, stok=?, harga=?, foto=? WHERE id=?"
            );
            $stmt->bind_param('ssidsi', $nama, $kategori, $stok, $harga, $newThumb, $id);
            $stmt->execute();

            // Hapus foto lama
            $this->deleteFile($oldThumb);
        } else {
            $stmt = $this->conn->prepare(
                "UPDATE products SET nama_produk=?, kategori=?, stok=?, harga=? WHERE id=?"
            );
            $stmt->bind_param('ssidi', $nama, $kategori, $stok, $harga, $id);
            $stmt->execute();
        }

        return '';
    }

    /** Hapus barang beserta file fotonya */
    public function delete(int $id): bool {
        $barang = $this->getById($id);
        if ($barang && !empty($barang['foto'])) {
            $this->deleteFile($barang['foto']);
        }
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id=?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // ─── Private Helpers ────────────────────────────────────────────────────

    /** Upload foto, buat thumbnail 100x100. Return path thumb atau pesan error. */
    private function handleUpload(array $file): string {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return 'Error upload: ' . $file['error'];
        }
        if ($file['size'] > 1_000_000) {
            return 'File terlalu besar (maks 1 MB).';
        }

        if (!is_dir($this->dirOriginal)) mkdir($this->dirOriginal, 0777, true);
        if (!is_dir($this->dirThumb))    mkdir($this->dirThumb,    0777, true);

        $filename    = time() . '_' . basename($file['name']);
        $pathOriginal = $this->dirOriginal . $filename;

        if (!move_uploaded_file($file['tmp_name'], $pathOriginal)) {
            return 'Gagal memindahkan file upload.';
        }

        $mime = mime_content_type($pathOriginal);
        if (!in_array($mime, ['image/jpeg', 'image/png'], true)) {
            unlink($pathOriginal);
            return 'Format tidak didukung (hanya JPG/PNG).';
        }

        $src = match($mime) {
            'image/jpeg' => imagecreatefromjpeg($pathOriginal),
            'image/png'  => imagecreatefrompng($pathOriginal),
            default      => null,
        };

        if (!$src) {
            unlink($pathOriginal);
            return 'Gambar tidak valid.';
        }

        $w = imagesx($src);
        $h = imagesy($src);
        $tmp = imagecreatetruecolor(100, 100);
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, 100, 100, $w, $h);

        $pathThumb = $this->dirThumb . 'thumb_' . $filename;
        imagejpeg($tmp, $pathThumb, 80);
        imagedestroy($src);
        imagedestroy($tmp);

        return $pathThumb;
    }

    /** Hapus file thumbnail dan original jika ada */
    private function deleteFile(string $thumb): void {
        if ($thumb !== '' && file_exists($thumb)) {
            unlink($thumb);
        }
        $base = basename($thumb);
        if (str_starts_with($base, 'thumb_')) {
            $base = substr($base, strlen('thumb_'));
        }
        $original = $this->dirOriginal . $base;
        if ($original !== $this->dirOriginal && file_exists($original)) {
            unlink($original);
        }
    }
}
