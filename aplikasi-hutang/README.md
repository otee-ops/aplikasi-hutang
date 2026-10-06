# Aplikasi Pencatatan Hutang

Aplikasi PHP 8+ + MySQL/MariaDB + Bootstrap 5 untuk pencatatan hutang dan pembayaran.

## Fitur
- Login admin
- Dashboard ringkasan
- CRUD hutang
- Riwayat pembayaran
- Perhitungan sisa otomatis
- Status lunas/belum lunas
- Format Rupiah dengan titik
- Pencarian dan filter
- Laporan dan cetak
- Responsive/mobile-first
- Siap dipindahkan ke hosting PHP + MySQL

## Instalasi XAMPP
1. Salin folder `aplikasi-hutang` ke `C:\xampp\htdocs\`.
2. Jalankan Apache dan MySQL.
3. Buka phpMyAdmin.
4. Import `database/database.sql`.
5. Pastikan `config/database.php` memakai:
   - host: localhost
   - database: db_hutang
   - user: root
   - password: kosong (default XAMPP)
6. Buka `http://localhost/aplikasi-hutang/`.
7. Login dengan:
   - username: `admin`
   - password: `admin123`
8. Segera ganti password pada menu Admin.

## Hosting
Upload isi folder ke document root/subdomain hosting PHP. Buat database MySQL, import `database/database.sql`, lalu sesuaikan `config/database.php`.

Untuk hosting yang memakai kredensial database berbeda, ubah `$dbHost`, `$dbName`, `$dbUser`, `$dbPass`.

Setelah online, akses melalui domain HTTPS. HP tidak perlu terhubung ke laptop dan laptop tidak perlu menyala.

## Catatan keamanan
- Jangan membagikan file `config/database.php`.
- Ganti password admin.
- Aktifkan HTTPS pada hosting.
- Backup database secara berkala.
