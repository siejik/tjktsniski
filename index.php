<?php
require_once __DIR__ . '/includes/db.php';

$pdo = get_db();

// --- Kegiatan terbaru (3) ---------------------------------------------
$kegiatan = [];
if ($pdo) {
    try {
        $kegiatan = $pdo->query('SELECT judul, slug, tanggal, gambar FROM kegiatan ORDER BY tanggal DESC LIMIT 3')->fetchAll();
    } catch (PDOException $e) { /* fallback ke dummy di bawah */ }
}
if (!$kegiatan) {
    $kegiatan = [
        ['judul' => 'Praktik Konfigurasi Router & Switch', 'slug' => '', 'tanggal' => '2026-08-12', 'gambar' => 'https://placehold.co/480x360/0f172a/8FA3BF?text=Kegiatan+1'],
        ['judul' => 'LKS Bidang IT Network Systems Administration', 'slug' => '', 'tanggal' => '2026-09-02', 'gambar' => 'https://placehold.co/480x360/0f172a/8FA3BF?text=Kegiatan+2'],
        ['judul' => 'Kunjungan ke Data Center Mitra Industri', 'slug' => '', 'tanggal' => '2026-09-20', 'gambar' => 'https://placehold.co/480x360/0f172a/8FA3BF?text=Kegiatan+3'],
    ];
}

// --- Galeri terbaru (4, untuk bento preview) ---------------------------
$galeri = [];
if ($pdo) {
    try {
        $galeri = $pdo->query("SELECT url, caption FROM galeri WHERE tipe = 'foto' ORDER BY created_at DESC LIMIT 4")->fetchAll();
    } catch (PDOException $e) { /* fallback ke dummy di bawah */ }
}
if (!$galeri) {
    $galeri = [
        ['url' => 'https://placehold.co/800x800/132238/2F6FED?text=Galeri+1', 'caption' => 'Galeri praktik 1'],
        ['url' => 'https://placehold.co/480x360/132238/17A398?text=Galeri+2', 'caption' => 'Galeri praktik 2'],
        ['url' => 'https://placehold.co/480x360/132238/F2994A?text=Galeri+3', 'caption' => 'Galeri praktik 3'],
        ['url' => 'https://placehold.co/960x360/132238/8FA3BF?text=Galeri+4', 'caption' => 'Galeri praktik 4'],
    ];
}

// --- Flyer PKL (3) -------------------------------------------------------
$flyer = [];
if ($pdo) {
    try {
        $flyer = $pdo->query('SELECT nama_tempat, deskripsi FROM flyer_pkl ORDER BY created_at DESC LIMIT 3')->fetchAll();
    } catch (PDOException $e) { /* fallback ke dummy di bawah */ }
}
if (!$flyer) {
    $flyer = [
        ['nama_tempat' => 'PT Jaringan Nusantara', 'deskripsi' => 'Instalasi & maintenance jaringan pelanggan.'],
        ['nama_tempat' => 'CV Telko Data Prima', 'deskripsi' => 'Operasional server & monitoring jaringan.'],
        ['nama_tempat' => 'PT Sinyal Komunika', 'deskripsi' => 'Instalasi perangkat BTS & fiber optic.'],
    ];
}

$base_url    = '';
$active_page = 'beranda';
$page_title  = 'Jurusan TJKT — Teknik Jaringan Komputer dan Telekomunikasi';
require __DIR__ . '/includes/header.php';
?>

<!--
    HERO — background slideshow foto (ganti src di bawah dengan foto asli
    kegiatan/lab jurusan lewat dashboard admin nanti). Crossfade dikendalikan
    assets/js/main.js lewat class .hero-slide (opacity-0 <-> opacity-100).
-->
<header class="relative isolate overflow-hidden">
    <div class="absolute inset-0 -z-10">
        <img class="hero-slide absolute inset-0 h-full w-full object-cover opacity-100 transition-opacity duration-[2000ms]"
             src="https://placehold.co/1800x1200/0B1626/1E3A5F?text=Lab+Jaringan+TJKT" alt="">
        <img class="hero-slide absolute inset-0 h-full w-full object-cover opacity-0 transition-opacity duration-[2000ms]"
             src="https://placehold.co/1800x1200/0B1626/1E3A5F?text=Praktik+Fiber+Optic" alt="">
        <img class="hero-slide absolute inset-0 h-full w-full object-cover opacity-0 transition-opacity duration-[2000ms]"
             src="https://placehold.co/1800x1200/0B1626/1E3A5F?text=Server+%26+Data+Center" alt="">
        <img class="hero-slide absolute inset-0 h-full w-full object-cover opacity-0 transition-opacity duration-[2000ms]"
             src="https://placehold.co/1800x1200/0B1626/1E3A5F?text=Kegiatan+PKL+Industri" alt="">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-950/95 via-slate-950/90 to-slate-950"></div>
    </div>

    <div class="mx-auto max-w-7xl px-6 pb-24 pt-40 sm:pb-32 sm:pt-48 lg:px-8">
        <div class="max-w-2xl rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl shadow-black/40 backdrop-blur-xl sm:p-10">
            <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-brand-secondary">
                <span class="h-1.5 w-1.5 rounded-full bg-brand-secondary"></span>
                Jurusan TJKT
            </span>
            <h1 class="mt-5 font-display text-4xl font-bold leading-tight tracking-tight text-white sm:text-5xl">
                Menghubungkan setiap titik, membangun jaringan masa depan.
            </h1>
            <p class="mt-5 text-base leading-relaxed text-slate-300 sm:text-lg">
                Teknik Jaringan Komputer dan Telekomunikasi menyiapkan siswa menguasai infrastruktur jaringan, sistem telekomunikasi, dan praktik industri langsung sejak bangku SMK.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="pages/profil.php" class="rounded-full bg-brand-primary px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 transition hover:bg-blue-500">
                    Lihat Profil Jurusan
                </a>
                <a href="pages/kegiatan.php" class="rounded-full border border-white/20 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                    Kegiatan Terbaru
                </a>
            </div>
        </div>
    </div>
</header>

<!-- PROFIL — bento grid -->
<section id="profil" class="relative py-20 sm:py-28">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mb-10 flex flex-col gap-3">
            <span class="inline-flex w-fit items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-brand-primary">
                <span class="h-1.5 w-1.5 rounded-full bg-brand-primary"></span>
                Profil Jurusan
            </span>
            <h2 class="max-w-xl font-display text-3xl font-bold tracking-tight text-white sm:text-4xl">Disiapkan untuk industri jaringan &amp; telekomunikasi</h2>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur-xl transition hover:bg-white/[0.08] md:col-span-2">
                <h3 class="font-display text-xl font-semibold text-white">Identitas</h3>
                <p class="mt-3 leading-relaxed text-slate-400">TJKT membekali siswa dengan kompetensi instalasi jaringan, konfigurasi perangkat, dan dasar telekomunikasi sejak kelas X — lewat praktik langsung di lab dan pengalaman PKL di industri mitra.</p>
            </div>
            <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-brand-primary/20 to-transparent p-8 backdrop-blur-xl transition hover:bg-white/[0.08]">
                <h3 class="font-display text-xl font-semibold text-white">Visi</h3>
                <p class="mt-3 leading-relaxed text-slate-400">Menjadi jurusan unggulan penghasil teknisi jaringan yang kompeten, adaptif, dan siap kerja.</p>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur-xl transition hover:bg-white/[0.08] md:col-span-2">
                <h3 class="font-display text-xl font-semibold text-white">Kompetensi Utama</h3>
                <p class="mt-3 leading-relaxed text-slate-400">Routing &amp; switching, instalasi fiber optic, keamanan jaringan dasar, hingga manajemen server sederhana.</p>
            </div>
            <a href="pages/profil.php" class="group flex flex-col justify-between rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur-xl transition hover:bg-brand-primary/10">
                <span class="font-display text-xl font-semibold text-white">Profil Lengkap</span>
                <span class="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-brand-primary">
                    Baca selengkapnya
                    <span class="transition group-hover:translate-x-1">→</span>
                </span>
            </a>
        </div>
    </div>
</section>

<!-- KEGIATAN -->
<section id="kegiatan" class="relative border-y border-white/10 bg-slate-900/40 py-20 sm:py-28">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mb-10 flex flex-col gap-3">
            <span class="inline-flex w-fit items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-brand-secondary">
                <span class="h-1.5 w-1.5 rounded-full bg-brand-secondary"></span>
                Info Kegiatan
            </span>
            <h2 class="font-display text-3xl font-bold tracking-tight text-white sm:text-4xl">Kegiatan Terbaru</h2>
            <p class="max-w-xl text-slate-400">Dokumentasi praktik, lomba, dan agenda jurusan.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($kegiatan as $k): ?>
            <article class="group overflow-hidden rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl transition hover:bg-white/[0.08]">
                <div class="aspect-[4/3] overflow-hidden">
                    <img src="<?= htmlspecialchars($k['gambar'] ?: 'https://placehold.co/480x360/0f172a/8FA3BF?text=Kegiatan') ?>" alt="<?= htmlspecialchars($k['judul']) ?>" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                </div>
                <div class="p-6">
                    <h3 class="font-display text-lg font-semibold text-white"><?= htmlspecialchars($k['judul']) ?></h3>
                    <span class="mt-2 block text-sm text-slate-500"><?= date('d F Y', strtotime($k['tanggal'])) ?></span>
                    <a href="pages/kegiatan-detail.php<?= $k['slug'] ? '?slug=' . urlencode($k['slug']) : '' ?>" class="mt-4 inline-flex text-sm font-semibold text-brand-primary hover:text-blue-400">Baca selengkapnya →</a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <a href="pages/kegiatan.php" class="mt-10 inline-flex items-center gap-1 text-sm font-semibold text-brand-secondary hover:text-teal-400">Lihat semua kegiatan →</a>
    </div>
</section>

<!-- GALERI -->
<section id="galeri" class="relative py-20 sm:py-28">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mb-10 flex flex-col gap-3">
            <span class="inline-flex w-fit items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-brand-primary">
                <span class="h-1.5 w-1.5 rounded-full bg-brand-primary"></span>
                Galeri Praktek
            </span>
            <h2 class="font-display text-3xl font-bold tracking-tight text-white sm:text-4xl">Momen di Lab &amp; Lapangan</h2>
            <p class="max-w-xl text-slate-400">Foto praktik, video kegiatan, dan fasilitas jurusan.</p>
        </div>

        <div class="grid grid-cols-2 gap-4 sm:gap-6 md:grid-cols-4">
            <?php $spans = ['col-span-2 row-span-2', '', '', 'col-span-2']; ?>
            <?php foreach ($galeri as $i => $g): ?>
            <div class="overflow-hidden rounded-3xl <?= $spans[$i] ?? '' ?>">
                <img src="<?= htmlspecialchars($g['url']) ?>" alt="<?= htmlspecialchars($g['caption'] ?? 'Galeri') ?>" class="h-full w-full object-cover transition duration-500 hover:scale-105">
            </div>
            <?php endforeach; ?>
        </div>

        <a href="pages/galeri.php" class="mt-10 inline-flex items-center gap-1 text-sm font-semibold text-brand-primary hover:text-blue-400">Lihat galeri lengkap →</a>
    </div>
</section>

<!-- PKL -->
<section id="pkl" class="relative border-y border-white/10 bg-slate-900/40 py-20 sm:py-28">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mb-10 flex flex-col gap-3">
            <span class="inline-flex w-fit items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-brand-accent">
                <span class="h-1.5 w-1.5 rounded-full bg-brand-accent"></span>
                Flyer PKL
            </span>
            <h2 class="font-display text-3xl font-bold tracking-tight text-white sm:text-4xl">Tempat Praktik Kerja Lapangan</h2>
            <p class="max-w-xl text-slate-400">Mitra industri yang bekerja sama dengan Jurusan TJKT.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($flyer as $f): ?>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl transition hover:bg-white/[0.08]">
                <h3 class="font-display text-lg font-semibold text-white"><?= htmlspecialchars($f['nama_tempat']) ?></h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-400"><?= htmlspecialchars($f['deskripsi']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <a href="pages/flyer-pkl.php" class="mt-10 inline-flex items-center gap-1 text-sm font-semibold text-brand-accent hover:text-amber-400">Lihat semua tempat PKL →</a>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
