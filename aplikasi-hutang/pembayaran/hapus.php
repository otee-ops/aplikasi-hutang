<?php
require_once __DIR__ . '/../config/database.php'; require_once __DIR__ . '/../config/auth.php'; require_login();
$id=(int)($_GET['id']??0);$hid=(int)($_GET['hutang_id']??0);
if($_SERVER['REQUEST_METHOD']!=='POST'){ 
    // GET is intentionally rejected to avoid accidental deletion from links.
    flash('danger','Penghapusan harus dikonfirmasi melalui formulir.'); header("Location:../hutang/detail.php?id=$hid"); exit;
}
verify_csrf($_POST['csrf_token']??null);
$st=$pdo->prepare("DELETE FROM pembayaran WHERE id=?");$st->execute([$id]);
$st=$pdo->prepare("SELECT jumlah_hutang,COALESCE((SELECT SUM(jumlah_bayar) FROM pembayaran p WHERE p.hutang_id=h.id),0) dibayar FROM hutang h WHERE h.id=?");$st->execute([$hid]);$h=$st->fetch();
if($h){$st=$pdo->prepare("UPDATE hutang SET status=? WHERE id=?");$st->execute([(float)$h['dibayar'] >= (float)$h['jumlah_hutang']?'LUNAS':'BELUM LUNAS',$hid]);}
flash('success','Pembayaran berhasil dihapus.');header("Location:../hutang/detail.php?id=$hid");exit;
