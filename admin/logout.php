<?php
/**
 * admin/logout.php — hapus sesi login lalu kembali ke halaman login.
 */
require_once __DIR__ . '/../includes/auth.php';

logout_session();
header('Location: login.php');
exit;
