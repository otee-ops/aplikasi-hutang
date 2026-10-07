<?php
require_once __DIR__ . '/../config/database.php'; require_once __DIR__ . '/../config/auth.php'; require_login();
$id=(int)($_GET['id']??0); $stmt=$pdo->prepare("SELECT * FROM hutang WHERE id=?"); $stmt->execute([$id]); $h=$stmt->fetch();
if(!$h){flash('danger','Data tidak ditemukan.');header('Location:index.php');exit;}
$pageTitle='Edit Hutang'; require __DIR__.'/../partials/header.php';
?>
<div class="row justify-content-center mt-3"><div class="col-lg-7"><div class="card app-card"><div class="card-body p-4"><h4>Edit Hutang</h4>
<form action="update.php" method="post"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="id" value="<?=$id?>">
<div class="mb-3"><label class="form-label">Nama</label><input class="form-control form-control-lg" name="nama" value="<?=e($h['nama'])?>" required></div>
<div class="mb-3"><label class="form-label">Tanggal Hutang</label><input type="date" class="form-control form-control-lg" name="tanggal_hutang" value="<?=e($h['tanggal_hutang'])?>" required></div>
<div class="mb-3"><label class="form-label">Jumlah Hutang</label><input class="form-control form-control-lg money-input" name="jumlah_hutang" value="<?=e(number_format((float)$h['jumlah_hutang'],0,'','.'))?>" inputmode="numeric" required></div>
<div class="mb-3"><label class="form-label">Keterangan</label><textarea class="form-control" name="keterangan" rows="3"><?=e($h['keterangan'])?></textarea></div>
<div class="d-flex gap-2"><a href="detail.php?id=<?=$id?>" class="btn btn-light flex-fill">Batal</a><button class="btn btn-primary flex-fill">Simpan Perubahan</button></div>
</form></div></div></div></div>
<?php require __DIR__.'/../partials/footer.php'; ?>
