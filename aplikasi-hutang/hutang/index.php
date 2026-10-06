<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_login();

$q = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? '';
$where=[]; $params=[];
if ($q !== '') { $where[]='(h.nama LIKE ? OR h.keterangan LIKE ?)'; $params[]="%$q%"; $params[]="%$q%"; }
if ($status === 'lunas') $where[]="h.jumlah_hutang <= COALESCE((SELECT SUM(p.jumlah_bayar) FROM pembayaran p WHERE p.hutang_id=h.id),0)";
if ($status === 'belum') $where[]="h.jumlah_hutang > COALESCE((SELECT SUM(p.jumlah_bayar) FROM pembayaran p WHERE p.hutang_id=h.id),0)";
$sql="SELECT h.*, COALESCE((SELECT SUM(p.jumlah_bayar) FROM pembayaran p WHERE p.hutang_id=h.id),0) dibayar FROM hutang h";
if ($where) $sql .= " WHERE ".implode(' AND ',$where);
$sql .= " ORDER BY h.id DESC";
$stmt=$pdo->prepare($sql); $stmt->execute($params); $rows=$stmt->fetchAll();

$pageTitle='Data Hutang'; require __DIR__.'/../partials/header.php';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3 mb-3"><div><h2 class="mb-1">Data Hutang</h2><p class="text-muted mb-0"><?=count($rows)?> data ditemukan.</p></div><a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Hutang</a></div>
<form class="card app-card p-3 mb-3" method="get"><div class="row g-2"><div class="col-12 col-md-6"><div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span><input name="q" value="<?=e($q)?>" class="form-control" placeholder="Cari nama atau keterangan..."></div></div><div class="col-8 col-md-3"><select name="status" class="form-select"><option value="">Semua Status</option><option value="belum" <?=$status==='belum'?'selected':''?>>Belum Lunas</option><option value="lunas" <?=$status==='lunas'?'selected':''?>>Lunas</option></select></div><div class="col-4 col-md-3"><button class="btn btn-outline-primary w-100">Filter</button></div></div></form>
<div class="d-md-none">
<?php foreach($rows as $r): $sisa=max(0,(float)$r['jumlah_hutang']-(float)$r['dibayar']); ?>
<div class="card app-card mb-3"><div class="card-body"><div class="d-flex justify-content-between"><div><h5 class="mb-1"><?=e($r['nama'])?></h5><small class="text-muted"><?=e(date('d-m-Y',strtotime($r['tanggal_hutang'])))?></small></div><span class="badge <?=$sisa<=0?'text-bg-success':'text-bg-warning'?>"><?=$sisa<=0?'LUNAS':'BELUM LUNAS'?></span></div><div class="mini-grid mt-3"><div><small>Hutang</small><strong><?=e(rupiah($r['jumlah_hutang']))?></strong></div><div><small>Dibayar</small><strong><?=e(rupiah($r['dibayar']))?></strong></div><div><small>Sisa</small><strong><?=e(rupiah($sisa))?></strong></div></div><div class="d-flex gap-2 mt-3"><a class="btn btn-outline-primary flex-fill" href="detail.php?id=<?=$r['id']?>">Detail</a><a class="btn btn-primary flex-fill" href="../pembayaran/tambah.php?hutang_id=<?=$r['id']?>">Bayar</a></div></div></div>
<?php endforeach; if(!$rows): ?><div class="empty-state"><i class="bi bi-search"></i><p>Data tidak ditemukan.</p></div><?php endif; ?>
</div>
<div class="card app-card d-none d-md-block"><div class="card-body table-responsive"><table class="table align-middle"><thead><tr><th>No</th><th>Nama</th><th>Tanggal</th><th>Hutang</th><th>Dibayar</th><th>Sisa</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($rows as $i=>$r): $sisa=max(0,(float)$r['jumlah_hutang']-(float)$r['dibayar']); ?>
<tr><td><?=$i+1?></td><td class="fw-semibold"><?=e($r['nama'])?></td><td><?=e(date('d-m-Y',strtotime($r['tanggal_hutang'])))?></td><td><?=e(rupiah($r['jumlah_hutang']))?></td><td><?=e(rupiah($r['dibayar']))?></td><td><?=e(rupiah($sisa))?></td><td><span class="badge <?=$sisa<=0?'text-bg-success':'text-bg-warning'?>"><?=$sisa<=0?'LUNAS':'BELUM LUNAS'?></span></td><td><div class="btn-group btn-group-sm"><a class="btn btn-outline-primary" href="detail.php?id=<?=$r['id']?>">Detail</a><a class="btn btn-primary" href="../pembayaran/tambah.php?hutang_id=<?=$r['id']?>">Bayar</a><a class="btn btn-outline-secondary" href="edit.php?id=<?=$r['id']?>">Edit</a></div></td></tr>
<?php endforeach; if(!$rows): ?><tr><td colspan="8" class="text-center py-4">Data tidak ditemukan.</td></tr><?php endif; ?></tbody></table></div></div>
<?php require __DIR__.'/../partials/footer.php'; ?>
