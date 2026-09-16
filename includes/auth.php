<?php
/**
 * includes/auth.php
 * Helper session/login admin. Include file ini di setiap halaman admin
 * (setelah db.php), lalu panggil require_login() di baris paling atas
 * untuk halaman yang wajib login.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** Apakah ada admin yang sedang login? */
function is_logged_in(): bool
{
    return !empty($_SESSION['admin_id']);
}

/** Paksa redirect ke login.php kalau belum login. Panggil di atas halaman admin. */
function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

/** Data admin yang sedang login (null kalau belum login). */
function current_admin(): ?array
{
    if (!is_logged_in()) {
        return null;
    }
    return [
        'id'       => $_SESSION['admin_id'],
        'username' => $_SESSION['admin_username'] ?? '',
        'role'     => $_SESSION['admin_role'] ?? 'admin',
    ];
}

/** Simpan sesi login setelah kredensial tervalidasi di login.php. */
function login_session(array $admin): void
{
    session_regenerate_id(true);
    $_SESSION['admin_id']       = $admin['id'];
    $_SESSION['admin_username'] = $admin['username'];
    $_SESSION['admin_role']     = $admin['role'];
}

/** Hapus sesi login (dipakai logout.php). */
function logout_session(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}
