<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_login();

$totalData = (int)$pdo->query("SELECT COUNT(*) FROM hutang")->fetchColumn();
$totalHutang = (float)$pdo->query("SELECT COALESCE(SUM(jumlah_hutang),0) FROM hutang")->fetchColumn();
$totalBayar = (float)$pdo->query("SELECT COALESCE(SUM(jumlah_bayar),0) FROM pembayaran")->fetchColumn();
$totalSisa = max(0, $totalHutang - $totalBayar);
$lunas = (int)$pdo->query("SELECT COUNT(*) FROM hutang h WHERE h.jumlah_hutang <= (SELECT COALESCE(SUM(p.jumlah_bayar),0) FROM pembayaran p WHERE p.hutang_id=h.id)")->fetchColumn();
$belum = max(0, $totalData - $lunas);
$terbaru = $pdo->query("SELECT h.*, COALESCE((SELECT SUM(p.jumlah_bayar) FROM pembayaran p WHERE p.hutang_id=h.id),0) dibayar FROM hutang h ORDER BY h.id DESC LIMIT 5")->fetchAll();

$pageTitle = 'Dashboard';
require __DIR__ . '/../partials/header.php';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3 mb-3">
<div><h2 class="mb-1">Dashboard</h2><p class="text-muted mb-0">Ringkasan pencatatan hutang.</p></div>
<a class="btn btn-primary" href="../hutang/tambah.php"><i class="bi bi-plus-lg"></i> Tambah Hutang</a>
</div>
<div class="row g-3">
<?php
$cards = [
 ['Total Data', $totalData, 'bi-people', 'text-primary', false],
 ['Total Hutang', rupiah($totalHutang), 'bi-cash-stack', 'text-warning', true],
 ['Sudah Dibayar', rupiah($totalBayar), 'bi-check2-circle', 'text-success', true],
 ['Sisa Hutang', rupiah($totalSisa), 'bi-wallet2', 'text-danger', true],
 ['Belum Lunas', $belum, 'bi-hourglass-split', 'text-secondary', false],
 ['Lunas', $lunas, 'bi-patch-check', 'text-success', false],
];
foreach ($cards as $c): ?>
<div class="col-6 col-lg-4"><div class="stat-card h-100"><div class="icon <?=$c[3]?>"><i class="bi <?=$c[2]?>"></i></div><div><div class="small text-muted"><?=$c[0]?></div><div class="stat-value <?=$c[4]?'money':''?>"><?=e((string)$c[1])?></div></div></div></div>
<?php endforeach; ?>
</div>
<div class="card app-card mt-4">
<div class="card-body">
<div class="d-flex justify-content-between align-items-center mb-3"><h5 class="mb-0">Hutang Terbaru</h5><a href="../hutang/" class="btn btn-sm btn-outline-primary">Lihat Semua</a></div>
<?php if (!$terbaru): ?><div class="empty-state"><i class="bi bi-inbox"></i><p>Belum ada data hutang.</p></div>
<?php else: ?><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Nama</th><th>Tanggal</th><th>Hutang</th><th>Sisa</th><th>Status</th><th></th></tr></thead><tbody>
<?php foreach ($terbaru as $r): $sisa=max(0,(float)$r['jumlah_hutang']-(float)$r['dibayar']); ?>
<tr><td class="fw-semibold"><?=e($r['nama'])?></td><td><?=e(date('d-m-Y',strtotime($r['tanggal_hutang'])))?></td><td><?=e(rupiah($r['jumlah_hutang']))?></td><td><?=e(rupiah($sisa))?></td><td><span class="badge <?=$sisa<=0?'text-bg-success':'text-bg-warning'?>"><?=$sisa<=0?'LUNAS':'BELUM LUNAS'?></span></td><td><a class="btn btn-sm btn-outline-primary" href="../hutang/detail.php?id=<?=$r['id']?>">Detail</a></td></tr>
<?php endforeach; ?></tbody></table></div><?php endif; ?>
</div></div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
