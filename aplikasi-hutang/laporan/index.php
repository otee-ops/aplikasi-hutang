<?php
require_once __DIR__ . '/../config/database.php'; require_once __DIR__ . '/../config/auth.php'; require_login();
$periode=$_GET['periode']??'semua';$where='';$params=[];
if($periode==='hari'){$where='WHERE h.tanggal_hutang=CURDATE()';}
elseif($periode==='bulan'){$where='WHERE YEAR(h.tanggal_hutang)=YEAR(CURDATE()) AND MONTH(h.tanggal_hutang)=MONTH(CURDATE())';}
elseif($periode==='tahun'){$where='WHERE YEAR(h.tanggal_hutang)=YEAR(CURDATE())';}
$sql="SELECT h.*,COALESCE((SELECT SUM(p.jumlah_bayar) FROM pembayaran p WHERE p.hutang_id=h.id),0) dibayar FROM hutang h $where ORDER BY h.tanggal_hutang DESC,h.id DESC";
$rows=$pdo->query($sql)->fetchAll();
$totalHutang=array_sum(array_column($rows,'jumlah_hutang'));$totalBayar=array_sum(array_column($rows,'dibayar'));$totalSisa=max(0,$totalHutang-$totalBayar);$lunas=0;
foreach($rows as $r)if((float)$r['dibayar']>=(float)$r['jumlah_hutang'])$lunas++;
$pageTitle='Laporan';require __DIR__.'/../partials/header.php';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3 mb-3"><div><h2>Laporan</h2><p class="text-muted mb-0">Ringkasan sesuai periode.</p></div><button onclick="window.print()" class="btn btn-outline-primary"><i class="bi bi-printer"></i> Cetak</button></div>
<form class="card app-card p-3 mb-3 no-print"><div class="row g-2"><div class="col-8"><select name="periode" class="form-select"><option value="semua" <?=$periode==='semua'?'selected':''?>>Semua</option><option value="hari" <?=$periode==='hari'?'selected':''?>>Hari ini</option><option value="bulan" <?=$periode==='bulan'?'selected':''?>>Bulan ini</option><option value="tahun" <?=$periode==='tahun'?'selected':''?>>Tahun ini</option></select></div><div class="col-4"><button class="btn btn-primary w-100">Terapkan</button></div></div></form>
<div class="row g-3 mb-4">
<div class="col-6 col-lg-3"><div class="stat-card"><div><small>Total Hutang</small><div class="stat-value"><?=e(rupiah($totalHutang))?></div></div></div></div>
<div class="col-6 col-lg-3"><div class="stat-card"><div><small>Total Dibayar</small><div class="stat-value text-success"><?=e(rupiah($totalBayar))?></div></div></div></div>
<div class="col-6 col-lg-3"><div class="stat-card"><div><small>Total Sisa</small><div class="stat-value text-danger"><?=e(rupiah($totalSisa))?></div></div></div></div>
<div class="col-6 col-lg-3"><div class="stat-card"><div><small>Lunas / Belum</small><div class="stat-value"><?=$lunas?> / <?=count($rows)-$lunas?></div></div></div></div>
</div>
<div class="card app-card"><div class="card-body table-responsive"><table class="table align-middle"><thead><tr><th>No</th><th>Nama</th><th>Tanggal</th><th>Hutang</th><th>Dibayar</th><th>Sisa</th><th>Status</th></tr></thead><tbody>
<?php foreach($rows as $i=>$r):$sisa=max(0,(float)$r['jumlah_hutang']-(float)$r['dibayar']);?><tr><td><?=$i+1?></td><td><?=e($r['nama'])?></td><td><?=e(date('d-m-Y',strtotime($r['tanggal_hutang'])))?></td><td><?=e(rupiah($r['jumlah_hutang']))?></td><td><?=e(rupiah($r['dibayar']))?></td><td><?=e(rupiah($sisa))?></td><td><?=$sisa<=0?'<span class="badge text-bg-success">LUNAS</span>':'<span class="badge text-bg-warning">BELUM LUNAS</span>'?></td></tr><?php endforeach; if(!$rows):?><tr><td colspan="7" class="text-center py-4">Tidak ada data pada periode ini.</td></tr><?php endif;?></tbody></table></div></div>
<?php require __DIR__.'/../partials/footer.php'; ?>
