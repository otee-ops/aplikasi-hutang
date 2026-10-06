CREATE DATABASE IF NOT EXISTS db_hutang CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_hutang;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE hutang (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    tanggal_hutang DATE NOT NULL,
    jumlah_hutang DECIMAL(15,2) NOT NULL DEFAULT 0,
    keterangan TEXT NULL,
    status ENUM('BELUM LUNAS','LUNAS') NOT NULL DEFAULT 'BELUM LUNAS',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nama (nama),
    INDEX idx_tanggal (tanggal_hutang),
    INDEX idx_status (status)
) ENGINE=InnoDB;

CREATE TABLE pembayaran (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hutang_id INT UNSIGNED NOT NULL,
    tanggal_bayar DATE NOT NULL,
    jumlah_bayar DECIMAL(15,2) NOT NULL DEFAULT 0,
    keterangan TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pembayaran_hutang
        FOREIGN KEY (hutang_id) REFERENCES hutang(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_hutang_id (hutang_id),
    INDEX idx_tanggal_bayar (tanggal_bayar)
) ENGINE=InnoDB;

-- Password admin awal: admin123
-- Akun admin dibuat oleh database/seed_admin.php setelah import.

