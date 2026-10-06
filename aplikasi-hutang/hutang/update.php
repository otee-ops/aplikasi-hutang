<?php
require_once __DIR__ . '/../config/database.php'; require_once __DIR__ . '/../config/auth.php'; require_login(); verify_csrf($_POST['csrf_token']??null);
$id=(int)($_POST['id']??0); $nama=trim($_POST['nama']??''); $tanggal=$_POST['tanggal_hutang']??''; $jumlah=uang_input_to_number($_POST['jumlah_hutang']??''); $ket=trim($_POST['keterangan']??'');
$st=$pdo->prepare("SELECT COALESCE(SUM(jumlah_bayar),0) FROM pembayaran WHERE hutang_id=?");$st->execute([$id]);$dibayar=(float)$st->fetchColumn();
if($jumlah<=0 || $jumlah<$dibayar){flash('danger','Jumlah hutang tidak boleh lebih kecil dari total pembayaran yang sudah dicatat ('.rupiah($dibayar).').');header("Location:edit.php?id=$id");exit;}
$st=$pdo->prepare("UPDATE hutang SET nama=?,tanggal_hutang=?,jumlah_hutang=?,keterangan=?,status=? WHERE id=?");$status=$jumlah<=$dibayar?'LUNAS':'BELUM LUNAS';$st->execute([$nama,$tanggal,$jumlah,$ket,$status,$id]);
flash('success','Data hutang berhasil diperbarui.');header("Location:detail.php?id=$id");exit;
