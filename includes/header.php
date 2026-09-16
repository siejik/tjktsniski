<?php
/**
 * includes/header.php
 * Set variabel berikut SEBELUM include file ini:
 *   $base_url    = ''   di index.php, '../' di pages/*.php dan admin/*.php
 *   $active_page = salah satu: 'beranda','profil','kegiatan','galeri','pkl'
 *   $page_title  = judul untuk tag <title> (opsional)
 */
$base_url    = $base_url    ?? '';
$active_page = $active_page ?? '';
$page_title  = $page_title  ?? 'Jurusan TJKT';

function nav_class(string $key, string $active): string
{
    return $key === $active
        ? 'py-2 text-white transition hover:text-white'
        : 'py-2 transition hover:text-white';
}
function nav_class_mobile(string $key, string $active): string
{
    $base = 'block rounded-lg px-3 py-3 hover:bg-white/5 hover:text-white';
    return $key === $active ? $base . ' bg-white/5 text-white' : $base . ' text-slate-300';
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($page_title) ?></title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

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
<link rel="stylesheet" href="<?= $base_url ?>assets/css/custom.css">
</head>
<body class="bg-slate-950 font-sans text-slate-300 antialiased selection:bg-brand-primary selection:text-white">

<nav class="fixed inset-x-0 top-0 z-50 border-b border-white/10 bg-slate-950/70 backdrop-blur-xl">
    <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6 lg:px-8">
        <a href="<?= $base_url ?>index.php" class="flex items-center gap-2 font-display text-lg font-bold tracking-tight text-white">
            <svg class="h-8 w-8" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="6" cy="24" r="3" fill="#17A398"/>
                <circle cx="24" cy="6" r="3" fill="#3B82F6"/>
                <circle cx="24" cy="24" r="3" fill="#3B82F6"/>
                <path d="M6 24L24 6M6 24L24 24" stroke="#8FA3BF" stroke-width="1.4"/>
            </svg>
            TJKT
        </a>
        <ul class="hidden items-center gap-8 text-sm font-medium text-slate-300 md:flex">
            <li><a href="<?= $base_url ?>index.php" class="<?= nav_class('beranda', $active_page) ?>">Beranda</a></li>
            <li><a href="<?= $base_url ?>pages/profil.php" class="<?= nav_class('profil', $active_page) ?>">Profil</a></li>
            <li><a href="<?= $base_url ?>pages/kegiatan.php" class="<?= nav_class('kegiatan', $active_page) ?>">Kegiatan</a></li>
            <li><a href="<?= $base_url ?>pages/galeri.php" class="<?= nav_class('galeri', $active_page) ?>">Galeri</a></li>
            <li><a href="<?= $base_url ?>pages/flyer-pkl.php" class="<?= nav_class('pkl', $active_page) ?>">PKL</a></li>
        </ul>
        <a href="<?= $base_url ?>pages/flyer-pkl.php" class="hidden rounded-full bg-brand-primary px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/20 transition hover:bg-blue-500 md:inline-flex">
            Info PKL
        </a>
        <button id="nav-toggle" aria-label="Buka menu" aria-expanded="false" class="rounded-lg p-2 text-slate-200 hover:bg-white/10 md:hidden">
            <svg id="icon-menu" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" /></svg>
            <svg id="icon-close" class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
        </button>
    </div>
    <div id="mobile-menu" class="hidden border-t border-white/10 bg-slate-950/95 backdrop-blur-xl md:hidden">
        <ul class="flex flex-col gap-1 px-6 py-4 text-sm font-medium">
            <li><a href="<?= $base_url ?>index.php" class="<?= nav_class_mobile('beranda', $active_page) ?>">Beranda</a></li>
            <li><a href="<?= $base_url ?>pages/profil.php" class="<?= nav_class_mobile('profil', $active_page) ?>">Profil</a></li>
            <li><a href="<?= $base_url ?>pages/kegiatan.php" class="<?= nav_class_mobile('kegiatan', $active_page) ?>">Kegiatan</a></li>
            <li><a href="<?= $base_url ?>pages/galeri.php" class="<?= nav_class_mobile('galeri', $active_page) ?>">Galeri</a></li>
            <li><a href="<?= $base_url ?>pages/flyer-pkl.php" class="<?= nav_class_mobile('pkl', $active_page) ?>">PKL</a></li>
        </ul>
    </div>
</nav>
