<?php
/**
 * admin/login.php
 * Form login admin. Verifikasi username + password terhadap tabel `admins`
 * (password_hash dibuat lewat admin/setup.php). Sukses login -> dashboard.php.
 */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $pdo = get_db();

    if (!$pdo) {
        $error = 'Tidak bisa konek ke database. Cek konfigurasi di includes/db.php.';
    } elseif ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        try {
            $stmt = $pdo->prepare('SELECT id, username, password_hash, role FROM admins WHERE username = ? LIMIT 1');
            $stmt->execute([$username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password_hash'])) {
                login_session($admin);
                header('Location: dashboard.php');
                exit;
            }
            $error = 'Username atau password salah.';
        } catch (PDOException $e) {
            $error = 'Tabel `admins` belum ada. Import database/schema.sql dan buat akun lewat setup.php.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin — TJKT</title>
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
    <h1 class="font-display text-xl font-bold text-white">Masuk ke Dashboard</h1>
    <p class="mt-2 text-sm text-slate-400">Khusus guru/pengelola Jurusan TJKT.</p>

    <?php if ($error): ?>
        <p class="mt-4 rounded-2xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-300"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" class="mt-6 flex flex-col gap-4">
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-300">Username</label>
            <input type="text" name="username" required autofocus class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-slate-500 outline-none focus:border-brand-primary" placeholder="Username admin">
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-300">Password</label>
            <input type="password" name="password" required class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-slate-500 outline-none focus:border-brand-primary" placeholder="••••••••">
        </div>
        <button type="submit" class="mt-2 rounded-full bg-brand-primary px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-500">Masuk</button>
    </form>

    <p class="mt-6 text-center text-xs text-slate-500">Belum punya akun admin? Jalankan <code>admin/setup.php</code> satu kali.</p>
    <a href="../index.php" class="mt-4 block text-center text-sm text-slate-400 hover:text-white">← Kembali ke website</a>
</div>
</body>
</html>
