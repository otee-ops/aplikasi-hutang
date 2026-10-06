<?php
require_once __DIR__ . '/../config/database.php'; require_once __DIR__ . '/../config/auth.php'; require_login();
$hid=(int)($_GET['hutang_id']??0);$st=$pdo->prepare("SELECT h.*,COALESCE((SELECT SUM(p.jumlah_bayar) FROM pembayaran p WHERE p.hutang_id=h.id),0) dibayar FROM hutang h WHERE h.id=?");$st->execute([$hid]);$h=$st->fetch();
if(!$h){flash('danger','Data hutang tidak ditemukan.');header('Location:../hutang/');exit;}
$sisa=max(0,(float)$h['jumlah_hutang']-(float)$h['dibayar']);
$pageTitle='Tambah Pembayaran';require __DIR__.'/../partials/header.php';
?>
<div class="row justify-content-center mt-3"><div class="col-lg-7"><div class="card app-card"><div class="card-body p-4"><a href="../hutang/detail.php?id=<?=$hid?>" class="text-decoration-none">← Kembali</a><h4 class="mt-2">Tambah Pembayaran</h4><div class="summary-box mb-3"><div><small>Nama</small><strong><?=e($h['nama'])?></strong></div><div><small>Sisa hutang</small><strong class="text-danger"><?=e(rupiah($sisa))?></strong></div></div>
<?php if($sisa<=0): ?><div class="alert alert-success">Hutang ini sudah lunas.</div><?php else: ?>
<form action="simpan.php" method="post"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="hutang_id" value="<?=$hid?>">
<div class="mb-3"><label class="form-label">Tanggal Pembayaran</label><input type="date" class="form-control form-control-lg" name="tanggal_bayar" value="<?=date('Y-m-d')?>" required></div>
<div class="mb-3"><label class="form-label">Jumlah Pembayaran</label><input class="form-control form-control-lg money-input" name="jumlah_bayar" inputmode="numeric" placeholder="Contoh: 500.000" required></div>
<div class="mb-3"><label class="form-label">Keterangan</label><textarea class="form-control" name="keterangan" rows="3"></textarea></div>
<button class="btn btn-primary btn-lg w-100"><i class="bi bi-check2-circle"></i> Simpan Pembayaran</button>
</form><?php endif; ?></div></div></div></div>
<?php require __DIR__.'/../partials/footer.php'; ?>
