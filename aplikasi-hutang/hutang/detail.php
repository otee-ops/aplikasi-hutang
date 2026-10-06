<?php
require_once __DIR__ . '/../config/database.php'; require_once __DIR__ . '/../config/auth.php'; require_login();
$id=(int)($_GET['id']??0);
$stmt=$pdo->prepare("SELECT * FROM hutang WHERE id=?"); $stmt->execute([$id]); $h=$stmt->fetch();
if(!$h){ flash('danger','Data hutang tidak ditemukan.'); header('Location:index.php'); exit; }
$pstmt=$pdo->prepare("SELECT * FROM pembayaran WHERE hutang_id=? ORDER BY tanggal_bayar DESC,id DESC"); $pstmt->execute([$id]); $payments=$pstmt->fetchAll();
$totalBayar=(float)array_sum(array_column($payments,'jumlah_bayar')); $sisa=max(0,(float)$h['jumlah_hutang']-$totalBayar);
$pageTitle='Detail Hutang'; require __DIR__.'/../partials/header.php';
?>
<div class="d-flex justify-content-between align-items-center mt-3 mb-3"><div><a href="index.php" class="text-decoration-none">← Kembali</a><h2 class="mt-2 mb-0"><?=e($h['nama'])?></h2></div><div class="d-flex gap-2">
<a class="btn btn-outline-secondary" href="edit.php?id=<?=$id?>">Edit</a>
<a class="btn btn-primary" href="../pembayaran/tambah.php?hutang_id=<?=$id?>">+ Bayar</a>
<form method="post" action="hapus.php" onsubmit="return confirm('Yakin ingin menghapus data hutang ini beserta riwayat pembayarannya?')">
<input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="id" value="<?=$id?>">
<button class="btn btn-outline-danger">Hapus</button></form></div></div>
<div class="row g-3"><div class="col-md-7"><div class="card app-card h-100"><div class="card-body"><div class="detail-row"><span>Tanggal Hutang</span><strong><?=e(date('d-m-Y',strtotime($h['tanggal_hutang'])))?></strong></div><div class="detail-row"><span>Total Hutang</span><strong><?=e(rupiah($h['jumlah_hutang']))?></strong></div><div class="detail-row"><span>Total Dibayar</span><strong class="text-success"><?=e(rupiah($totalBayar))?></strong></div><div class="detail-row"><span>Sisa Hutang</span><strong class="text-danger"><?=e(rupiah($sisa))?></strong></div><div class="detail-row"><span>Status</span><span class="badge <?=$sisa<=0?'text-bg-success':'text-bg-warning'?>"><?=$sisa<=0?'LUNAS':'BELUM LUNAS'?></span></div><?php if($h['keterangan']): ?><hr><div><small class="text-muted">Keterangan</small><p class="mb-0"><?=nl2br(e($h['keterangan']))?></p></div><?php endif; ?></div></div></div>
<div class="col-md-5"><div class="card app-card h-100"><div class="card-body"><h5>Riwayat Pembayaran</h5><?php if(!$payments): ?><div class="empty-state py-4"><i class="bi bi-receipt"></i><p>Belum ada pembayaran.</p></div><?php else: foreach($payments as $p): ?><div class="payment-item"><div><strong><?=e(date('d-m-Y',strtotime($p['tanggal_bayar'])))?></strong><br><small class="text-muted"><?=e($p['keterangan'] ?: 'Tanpa keterangan')?></small></div><div class="text-end"><strong><?=e(rupiah($p['jumlah_bayar']))?></strong><div class="mt-1"><a href="../pembayaran/edit.php?id=<?=$p['id']?>" class="small">Edit</a> ·
<form class="d-inline" method="post" action="../pembayaran/hapus.php" onsubmit="return confirm('Hapus pembayaran ini?')">
<input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="id" value="<?=$p['id']?>"><input type="hidden" name="hutang_id" value="<?=$id?>">
<button class="btn btn-link btn-sm p-0 text-danger align-baseline">Hapus</button></form></div></div></div><?php endforeach; endif; ?></div></div></div></div>
<?php require __DIR__.'/../partials/footer.php'; ?>
