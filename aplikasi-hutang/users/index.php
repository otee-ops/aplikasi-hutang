<?php
require_once __DIR__ . '/../config/database.php'; require_once __DIR__ . '/../config/auth.php'; require_login();
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf($_POST['csrf_token']??null);
 $nama=trim($_POST['nama']??'');$username=trim($_POST['username']??'');$password=(string)($_POST['password']??'');
 if($nama===''||$username===''||strlen($password)<6){flash('danger','Nama, username, dan password minimal 6 karakter wajib diisi.');header('Location:index.php');exit;}
 try{$st=$pdo->prepare("UPDATE users SET nama=?,username=?,password=? WHERE id=?");$st->execute([$nama,$username,password_hash($password,PASSWORD_DEFAULT),$_SESSION['user_id']]);$_SESSION['nama']=$nama;$_SESSION['username']=$username;flash('success','Pengaturan admin berhasil diperbarui.');}catch(PDOException $e){flash('danger','Username sudah digunakan atau data tidak valid.');}
 header('Location:index.php');exit;
}
$st=$pdo->prepare("SELECT username,nama FROM users WHERE id=?");$st->execute([$_SESSION['user_id']]);$u=$st->fetch();
$pageTitle='Pengaturan Admin';require __DIR__.'/../partials/header.php';
?>
<div class="row justify-content-center mt-3"><div class="col-lg-7"><div class="card app-card"><div class="card-body p-4"><h4>Pengaturan Admin</h4><p class="text-muted">Ubah identitas dan password akun Anda.</p>
<form method="post"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>">
<div class="mb-3"><label class="form-label">Nama</label><input class="form-control" name="nama" value="<?=e($u['nama'])?>" required></div>
<div class="mb-3"><label class="form-label">Username</label><input class="form-control" name="username" value="<?=e($u['username'])?>" required></div>
<div class="mb-3"><label class="form-label">Password Baru</label><input class="form-control" type="password" name="password" minlength="6" required><div class="form-text">Gunakan minimal 6 karakter.</div></div>
<button class="btn btn-primary w-100">Simpan Pengaturan</button>
</form></div></div></div></div>
<?php require __DIR__.'/../partials/footer.php'; ?>
