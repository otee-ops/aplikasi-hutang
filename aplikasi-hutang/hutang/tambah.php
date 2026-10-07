<?php
require_once __DIR__ . '/../config/auth.php'; require_login();
$pageTitle='Tambah Hutang'; require __DIR__.'/../partials/header.php';
?>
<div class="row justify-content-center mt-3"><div class="col-lg-7"><div class="card app-card"><div class="card-body p-4"><h4>Tambah Hutang</h4><p class="text-muted">Masukkan data hutang dengan benar.</p>
<form action="simpan.php" method="post">
<input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>">
<div class="mb-3"><label class="form-label">Nama <span class="text-danger">*</span></label><input class="form-control form-control-lg" name="nama" required maxlength="100"></div>
<div class="mb-3"><label class="form-label">Tanggal Hutang <span class="text-danger">*</span></label><input type="date" class="form-control form-control-lg" name="tanggal_hutang" value="<?=date('Y-m-d')?>" required></div>
<div class="mb-3"><label class="form-label">Jumlah Hutang <span class="text-danger">*</span></label><input class="form-control form-control-lg money-input" name="jumlah_hutang" inputmode="numeric" placeholder="Contoh: 1.000.000" required><div class="form-text">Contoh: ketik 1000000, tampilan menjadi 1.000.000.</div></div>
<div class="mb-3"><label class="form-label">Keterangan</label><textarea class="form-control" name="keterangan" rows="3" maxlength="1000"></textarea></div>
<div class="d-flex gap-2"><a href="index.php" class="btn btn-light flex-fill">Batal</a><button class="btn btn-primary flex-fill"><i class="bi bi-save"></i> Simpan</button></div>
</form></div></div></div></div>
<?php require __DIR__.'/../partials/footer.php'; ?>
