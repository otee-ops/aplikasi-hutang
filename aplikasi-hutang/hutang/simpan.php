<?php
require_once __DIR__ . '/../config/database.php'; require_once __DIR__ . '/../config/auth.php'; require_login();
verify_csrf($_POST['csrf_token'] ?? null);
$nama=trim($_POST['nama']??''); $tanggal=$_POST['tanggal_hutang']??''; $jumlah=uang_input_to_number($_POST['jumlah_hutang']??''); $ket=trim($_POST['keterangan']??'');
if($nama==='' || $tanggal==='' || $jumlah<=0){ flash('danger','Nama, tanggal, dan jumlah hutang wajib diisi dengan benar.'); header('Location:tambah.php'); exit; }
$stmt=$pdo->prepare("INSERT INTO hutang(nama,tanggal_hutang,jumlah_hutang,keterangan,status) VALUES(?,?,?,?, 'BELUM LUNAS')");
$stmt->execute([$nama,$tanggal,$jumlah,$ket]);
flash('success','Data hutang berhasil disimpan.'); header('Location:index.php'); exit;
