<?php
/**
 * admin/setup.php
 * Halaman SEKALI PAKAI untuk membuat akun admin pertama, supaya tidak ada
 * password/hash default yang ikut ter-upload ke server. Begitu tabel
 * `admins` sudah punya minimal 1 baris, halaman ini otomatis mengunci diri
 * dan mengarahkan ke login.php.
 *
 * SARAN: hapus atau pindahkan file ini keluar dari folder publik setelah
 * admin pertama berhasil dibuat.
 */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

$base_url = '../';
$error    = '';
$success  = '';
$locked   = false;

$pdo = get_db();

if ($pdo) {
    try {
        $count = (int) $pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn();
        $locked = $count > 0;
    } catch (PDOException $e) {
        $error = 'Tabel `admins` belum ada. Import database/schema.sql terlebih dulu.';
    }
} else {
    $error = 'Belum bisa konek ke database. Cek konfigurasi di includes/db.php.';
}

if (!$locked && !$error && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } elseif (strlen($password) < 8) {
        $error = 'Password minimal 8 karakter.';
    } elseif ($password !== $confirm) {
        $error = 'Konfirmasi password tidak cocok.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO admins (username, password_hash, role) VALUES (?, ?, ?)');
        $stmt->execute([$username, $hash, 'super_admin']);
        $success = 'Akun admin berhasil dibuat. Silakan login.';
        $locked  = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Setup Admin Pertama — TJKT</title>
<script src="https://unpkg.com/@tailwindcss/browser@4"></script>
<style type="text/tailwindcss">
@theme {
  --font-sans: "Inter", ui-sans-serif, system-ui, sans-serif;
  --font-display: "Space Grotesk", ui-sans-serif, system-ui, sans-serif;
  --color-brand-primary: #3B82F6;
  --color-brand-secondary: #17A398;
}
</style>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-950 p-6 font-sans text-slate-300 antialiased">
<div class="w-full max-w-md rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur-xl sm:p-10">
    <div class="mb-6 flex items-center gap-2 font-display text-lg font-bold text-white">
        <svg class="h-8 w-8" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="6" cy="24" r="3" fill="#17A398"/><circle cx="24" cy="6" r="3" fill="#3B82F6"/>
            <circle cx="24" cy="24" r="3" fill="#3B82F6"/><path d="M6 24L24 6M6 24L24 24" stroke="#8FA3BF" stroke-width="1.4"/>
        </svg>
        TJKT Admin
    </div>

    <?php if ($locked): ?>
        <h1 class="font-display text-xl font-bold text-white">Setup sudah selesai</h1>
        <p class="mt-3 text-sm leading-relaxed text-slate-400"><?= $success ?: 'Akun admin sudah ada. Silakan login di bawah, dan sebaiknya hapus file admin/setup.php dari server.' ?></p>
        <a href="login.php" class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-brand-primary px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-500">Ke Halaman Login</a>
    <?php elseif ($error): ?>
        <h1 class="font-display text-xl font-bold text-white">Belum bisa setup</h1>
        <p class="mt-3 rounded-2xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-300"><?= htmlspecialchars($error) ?></p>
    <?php else: ?>
        <h1 class="font-display text-xl font-bold text-white">Buat Akun Admin Pertama</h1>
        <p class="mt-2 text-sm text-slate-400">Halaman ini hanya bisa dipakai sekali, selama tabel <code>admins</code> masih kosong.</p>
        <form method="post" class="mt-6 flex flex-col gap-4">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-300">Username</label>
                <input type="text" name="username" required class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-slate-500 outline-none focus:border-brand-primary" placeholder="admin_tjkt">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-300">Password</label>
                <input type="password" name="password" required minlength="8" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-slate-500 outline-none focus:border-brand-primary" placeholder="Minimal 8 karakter">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-300">Konfirmasi Password</label>
                <input type="password" name="confirm" required minlength="8" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-slate-500 outline-none focus:border-brand-primary" placeholder="Ulangi password">
            </div>
            <button type="submit" class="mt-2 rounded-full bg-brand-primary px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-500">Buat Akun</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
