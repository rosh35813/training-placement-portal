<?php
// Simple CSRF helpers
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field()
{
    $t = htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8');
    return '<input type="hidden" name="__csrf" value="' . $t . '">';
}

function verify_csrf()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['__csrf'] ?? '';
        if (empty($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            return false;
        }
    }
    return true;
}

?>
