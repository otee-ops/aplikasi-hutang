<?php
require_once __DIR__ . '/config/auth.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard/');
} else {
    header('Location: login/');
}
exit;
