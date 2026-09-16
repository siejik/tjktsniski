<?php
/**
 * includes/admin-nav.php
 * Header + navigasi bersama untuk semua halaman admin (dashboard, kelola-*,
 * pengaturan). Set variabel berikut SEBELUM include file ini:
 *   $page_title   = judul tab browser (opsional, default 'Admin — TJKT')
 *   $admin        = current_admin() (wajib, dari includes/auth.php)
 *   $active_admin = salah satu: 'dashboard','kegiatan','galeri','flyer','profil','akun','pengaturan'
 *
 * File ini membuka <!DOCTYPE>, <head>, <body>, topbar, dan baris tab navigasi,
 * lalu membuka <main class="mx-auto max-w-7xl px-6 py-10 lg:px-8">.
 * Setiap halaman yang include ini WAJIB menutup dengan:
 *   </main>
 *   <script src="../assets/js/main.js"></script>
 *   </body></html>
 */

$page_title   = $page_title   ?? 'Admin — TJKT';
$active_admin = $active_admin ?? '';

function admin_tab_class(string $key, string $active): string
{
    return $key === $active
        ? 'shrink-0 rounded-full bg-brand-primary px-4 py-2 text-sm font-semibold text-white'
        : 'shrink-0 rounded-full px-4 py-2 text-sm font-medium text-slate-400 hover:bg-white/5 hover:text-white';
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($page_title) ?></title>
<script src="https://unpkg.com/@tailwindcss/browser@4"></script>
<style type="text/tailwindcss">
@theme {
  --font-sans: "Inter", ui-sans-serif, system-ui, sans-serif;
  --font-display: "Space Grotesk", ui-sans-serif, system-ui, sans-serif;
  --color-brand-primary: #3B82F6;
  --color-brand-secondary: #17A398;
  --color-brand-accent: #F2994A;
}
</style>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-slate-950 font-sans text-slate-300 antialiased">

<nav class="border-b border-white/10 bg-slate-950/70 backdrop-blur-xl">
    <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6 lg:px-8">
        <a href="dashboard.php" class="flex items-center gap-2 font-display text-lg font-bold text-white">
            <svg class="h-8 w-8" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="6" cy="24" r="3" fill="#17A398"/><circle cx="24" cy="6" r="3" fill="#3B82F6"/>
                <circle cx="24" cy="24" r="3" fill="#3B82F6"/><path d="M6 24L24 6M6 24L24 24" stroke="#8FA3BF" stroke-width="1.4"/>
            </svg>
            TJKT Admin
        </a>
        <div class="flex items-center gap-4 text-sm">
            <span class="hidden text-slate-400 sm:inline">Halo, <span class="font-semibold text-white"><?= htmlspecialchars($admin['username']) ?></span></span>
            <a href="../index.php" class="hidden rounded-full border border-white/15 px-4 py-2 font-semibold text-white transition hover:bg-white/10 sm:inline-flex">Lihat Situs</a>
            <a href="logout.php" class="rounded-full border border-white/15 px-4 py-2 font-semibold text-white transition hover:bg-white/10">Logout</a>
        </div>
    </div>
    <div class="mx-auto max-w-7xl px-6 pb-4 lg:px-8">
        <div class="flex gap-2 overflow-x-auto">
            <a href="dashboard.php" class="<?= admin_tab_class('dashboard', $active_admin) ?>">Dashboard</a>
            <a href="kelola-kegiatan.php" class="<?= admin_tab_class('kegiatan', $active_admin) ?>">Kegiatan</a>
            <a href="kelola-galeri.php" class="<?= admin_tab_class('galeri', $active_admin) ?>">Galeri</a>
            <a href="kelola-flyer.php" class="<?= admin_tab_class('flyer', $active_admin) ?>">Flyer PKL</a>
            <a href="kelola-profil.php" class="<?= admin_tab_class('profil', $active_admin) ?>">Profil Jurusan</a>
            <a href="kelola-akun.php" class="<?= admin_tab_class('akun', $active_admin) ?>">Akun</a>
            <a href="pengaturan.php" class="<?= admin_tab_class('pengaturan', $active_admin) ?>">Pengaturan</a>
        </div>
    </div>
</nav>

<main class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
