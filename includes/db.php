<?php
/**
 * includes/db.php
 * Koneksi database (PDO/MySQL) — SESUAIKAN 4 konstanta di bawah dengan
 * environment hosting Anda (biasanya dari cPanel/hosting sekolah).
 *
 * Import dulu database/schema.sql ke MySQL sebelum halaman dinamis bisa
 * menampilkan data asli. Selama belum di-setup / kredensial masih salah,
 * get_db() akan mengembalikan null dan setiap halaman otomatis jatuh ke
 * data dummy (lihat fallback array di masing-masing pages/*.php) — jadi
 * website TETAP tampil meski database belum siap.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'tjkt_db');
define('DB_USER', 'root');
define('DB_PASS', '');

/**
 * Ambil koneksi PDO (singleton). Return null kalau koneksi gagal.
 */
function get_db(): ?PDO
{
    static $pdo = null;
    static $attempted = false;

    if ($attempted) {
        return $pdo;
    }
    $attempted = true;

    try {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
    } catch (PDOException $e) {
        // Sengaja tidak menghentikan halaman (die/exit) — biar publik tetap
        // bisa lihat website dengan data dummy walau DB belum tersambung.
        $pdo = null;
    }

    return $pdo;
}
