<?php
require_once __DIR__ . '/../config/database.php'; require_once __DIR__ . '/../config/auth.php'; require_login();
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location:index.php');exit;} verify_csrf($_POST['csrf_token']??null);
$id=(int)($_POST['id']??0); $st=$pdo->prepare("DELETE FROM hutang WHERE id=?");$st->execute([$id]);flash('success','Data hutang berhasil dihapus.');header('Location:index.php');exit;
