<?php
require_once __DIR__ . '/../config/auth.php';
require_login();
$flash = get_flash();
$pageTitle = $pageTitle ?? 'Catat Hutang';
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=e($pageTitle)?> - Catat Hutang</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="<?=e(base_url('assets/css/style.css'))?>" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark app-nav sticky-top">
<div class="container-fluid">
<a class="navbar-brand fw-bold" href="<?=e(base_url('dashboard/'))?>"><i class="bi bi-wallet2"></i> Catat Hutang</a>
<div class="d-flex align-items-center gap-2 text-white">
<span class="d-none d-sm-inline"><?=e($_SESSION['nama'] ?? 'Admin')?></span>
<a class="btn btn-sm btn-light" href="<?=e(base_url('logout.php'))?>" onclick="return confirm('Keluar dari aplikasi?')"><i class="bi bi-box-arrow-right"></i></a>
</div>
</div>
</nav>
<div class="container-fluid app-container">
<?php if ($flash): ?><div class="alert alert-<?=e($flash['type'])?> alert-dismissible fade show mt-3"><?=e($flash['message'])?><button class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
