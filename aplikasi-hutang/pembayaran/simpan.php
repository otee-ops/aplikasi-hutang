<?php
require_once __DIR__ . '/../config/database.php'; require_once __DIR__ . '/../config/auth.php'; require_login(); verify_csrf($_POST['csrf_token']??null);
$hid=(int)($_POST['hutang_id']??0);$tanggal=$_POST['tanggal_bayar']??'';$jumlah=uang_input_to_number($_POST['jumlah_bayar']??'');$ket=trim($_POST['keterangan']??'');
$st=$pdo->prepare("SELECT jumlah_hutang FROM hutang WHERE id=?");$st->execute([$hid]);$hutang=$st->fetchColumn();
$st=$pdo->prepare("SELECT COALESCE(SUM(jumlah_bayar),0) FROM pembayaran WHERE hutang_id=?");$st->execute([$hid]);$dibayar=(float)$st->fetchColumn();$sisa=(float)$hutang-$dibayar;
if(!$hutang || $jumlah<=0 || $jumlah>$sisa){flash('danger','Jumlah pembayaran tidak boleh lebih besar dari sisa hutang ('.rupiah(max(0,$sisa)).').');header("Location:tambah.php?hutang_id=$hid");exit;}
$pdo->beginTransaction();
try{
 $st=$pdo->prepare("INSERT INTO pembayaran(hutang_id,tanggal_bayar,jumlah_bayar,keterangan) VALUES(?,?,?,?)");$st->execute([$hid,$tanggal,$jumlah,$ket]);
 $newSisa=$sisa-$jumlah;$st=$pdo->prepare("UPDATE hutang SET status=? WHERE id=?");$st->execute([$newSisa<=0?'LUNAS':'BELUM LUNAS',$hid]);
 $pdo->commit();
 flash('success',$newSisa<=0?'Pembayaran berhasil dicatat. Hutang telah lunas.':'Pembayaran berhasil dicatat.');
 header("Location:../hutang/detail.php?id=$hid");exit;
}catch(Throwable $e){$pdo->rollBack();flash('danger','Pembayaran gagal disimpan.');header("Location:tambah.php?hutang_id=$hid");exit;}
