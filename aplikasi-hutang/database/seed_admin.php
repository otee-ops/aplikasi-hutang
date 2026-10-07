<?php
// Jalankan sekali untuk membuat/memperbarui akun admin.
// Hapus file ini dari hosting setelah selesai.

require_once __DIR__ . '/../config/database.php';

$hash = password_hash('admin123', PASSWORD_DEFAULT);

$stmt = $pdo->prepare("
    INSERT INTO users (username, password, nama)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE
        password = VALUES(password),
        nama = VALUES(nama)
");

$stmt->execute([
    'admin',
    $hash,
    'Administrator'
]);

echo "Admin berhasil dibuat/diperbarui. Hapus file seed_admin.php setelah selesai.";
