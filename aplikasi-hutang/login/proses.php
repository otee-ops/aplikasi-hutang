<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

verify_csrf($_POST['csrf_token'] ?? null);
$username = trim($_POST['username'] ?? '');
$password = (string)($_POST['password'] ?? '');

$stmt = $pdo->prepare("SELECT id, username, password, nama FROM users WHERE username = ? LIMIT 1");
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    flash('danger', 'Username atau password salah.');
    header('Location: index.php');
    exit;
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['nama'] = $user['nama'];
header('Location: ../dashboard/');
exit;
