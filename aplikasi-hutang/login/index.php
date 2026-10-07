<?php
require_once __DIR__ . '/../config/auth.php';
if (!empty($_SESSION['user_id'])) { header('Location: ../dashboard/'); exit; }
$flash = get_flash();
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login - Catat Hutang</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="login-page">
<div class="login-card">
  <div class="brand-icon"><i class="bi bi-wallet2"></i></div>
  <h1>Catat Hutang</h1>
  <p class="text-muted">Masuk untuk mengelola catatan hutang.</p>
  <?php if ($flash): ?><div class="alert alert-<?=e($flash['type'])?>"><?=e($flash['message'])?></div><?php endif; ?>
  <form action="proses.php" method="post">
    <input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>">
    <label class="form-label">Username</label>
    <input class="form-control form-control-lg mb-3" name="username" required autocomplete="username">
    <label class="form-label">Password</label>
    <div class="input-group mb-4">
      <input class="form-control form-control-lg" id="password" type="password" name="password" required autocomplete="current-password">
      <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()"><i class="bi bi-eye"></i></button>
    </div>
    <button class="btn btn-primary btn-lg w-100"><i class="bi bi-box-arrow-in-right"></i> Masuk</button>
  </form>
</div>
<script>
function togglePassword(){const p=document.getElementById('password');p.type=p.type==='password'?'text':'password';}
</script>
</body>
</html>
