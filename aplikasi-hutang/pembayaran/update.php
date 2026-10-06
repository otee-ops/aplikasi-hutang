<?php
require_once __DIR__ . '/../config/database.php'; require_once __DIR__ . '/../config/auth.php'; require_login();verify_csrf($_POST['csrf_token']??null);
$id=(int)($_POST['id']??0);$tanggal=$_POST['tanggal_bayar']??'';$jumlah=uang_input_to_number($_POST['jumlah_bayar']??'');$ket=trim($_POST['keterangan']??'');
$st=$pdo->prepare("SELECT p.hutang_id,h.jumlah_hutang FROM pembayaran p JOIN hutang h ON h.id=p.hutang_id WHERE p.id=?");$st->execute([$id]);$row=$st->fetch();
if(!$row){flash('danger','Pembayaran tidak ditemukan.');header('Location:../hutang/');exit;}
$st=$pdo->prepare("SELECT COALESCE(SUM(jumlah_bayar),0) FROM pembayaran WHERE hutang_id=? AND id<>?");$st->execute([$row['hutang_id'],$id]);$other=(float)$st->fetchColumn();$max=(float)$row['jumlah_hutang']-$other;
if($jumlah<=0||$jumlah>$max){flash('danger','Jumlah pembayaran tidak valid. Maksimal '.rupiah($max).'.');header("Location:edit.php?id=$id");exit;}
$st=$pdo->prepare("UPDATE pembayaran SET tanggal_bayar=?,jumlah_bayar=?,keterangan=? WHERE id=?");$st->execute([$tanggal,$jumlah,$ket,$id]);
$st=$pdo->prepare("SELECT COALESCE(SUM(jumlah_bayar),0) FROM pembayaran WHERE hutang_id=?");$st->execute([$row['hutang_id']]);$sum=(float)$st->fetchColumn();
$st=$pdo->prepare("UPDATE hutang SET status=? WHERE id=?");$st->execute([$sum >= (float)$row['jumlah_hutang']?'LUNAS':'BELUM LUNAS',$row['hutang_id']]);
flash('success','Pembayaran berhasil diperbarui.');header("Location:../hutang/detail.php?id=".$row['hutang_id']);exit;
