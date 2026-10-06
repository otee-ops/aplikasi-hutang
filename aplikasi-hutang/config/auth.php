<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function require_login(): void {
    if (empty($_SESSION['user_id'])) {
        header('Location: /aplikasi-hutang/login/');
        exit;
    }
}

function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function rupiah(float|int|string $value): string {
    return 'Rp ' . number_format((float)$value, 0, ',', '.');
}

function uang_input_to_number(?string $value): float {
    $value = preg_replace('/[^\d]/', '', (string)$value);
    return $value === '' ? 0 : (float)$value;
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(?string $token): void {
    if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(419);
        exit('Token keamanan tidak valid. Silakan ulangi.');
    }
}

function flash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array {
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

function base_url(string $path = ''): string {
    $script = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $script = preg_replace('#/(dashboard|hutang|pembayaran|laporan|users|login)$#', '', $script);
    $script = rtrim($script, '/');
    return $script . '/' . ltrim($path, '/');
}
