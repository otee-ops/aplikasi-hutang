<?php
require_once __DIR__ . '/../config/database.php'; require_once __DIR__ . '/../config/auth.php'; require_login();
$id=(int)($_GET['id']??0);$st=$pdo->prepare("SELECT p.*,h.nama,h.jumlah_hutang FROM pembayaran p JOIN hutang h ON h.id=p.hutang_id WHERE p.id=?");$st->execute([$id]);$p=$st->fetch();
if(!$p){flash('danger','Pembayaran tidak ditemukan.');header('Location:../hutang/');exit;}
$st=$pdo->prepare("SELECT COALESCE(SUM(jumlah_bayar),0) FROM pembayaran WHERE hutang_id=? AND id<>?");$st->execute([$p['hutang_id'],$id]);$other=(float)$st->fetchColumn();$max=max(0,(float)$p['jumlah_hutang']-$other);
$pageTitle='Edit Pembayaran';require __DIR__.'/../partials/header.php';
?>
<div class="row justify-content-center mt-3"><div class="col-lg-7"><div class="card app-card"><div class="card-body p-4"><a href="../hutang/detail.php?id=<?=$p['hutang_id']?>" class="text-decoration-none">← Kembali</a><h4 class="mt-2">Edit Pembayaran</h4>
<form action="update.php" method="post"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="id" value="<?=$id?>">
<div class="mb-3"><label class="form-label">Tanggal Pembayaran</label><input type="date" class="form-control form-control-lg" name="tanggal_bayar" value="<?=e($p['tanggal_bayar'])?>" required></div>
<div class="mb-3"><label class="form-label">Jumlah Pembayaran</label><input class="form-control form-control-lg money-input" name="jumlah_bayar" value="<?=e(number_format((float)$p['jumlah_bayar'],0,'','.'))?>" required inputmode="numeric"><div class="form-text">Maksimum yang dapat dicatat: <?=e(rupiah($max))?></div></div>
<div class="mb-3"><label class="form-label">Keterangan</label><textarea class="form-control" name="keterangan" rows="3"><?=e($p['keterangan'])?></textarea></div>
<button class="btn btn-primary w-100">Simpan Perubahan</button></form>
</div></div></div></div>
<?php require __DIR__.'/../partials/footer.php'; ?>
