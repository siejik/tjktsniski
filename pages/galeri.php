<?php
require_once __DIR__ . '/../includes/db.php';

$pdo   = get_db();
$items = [];

if ($pdo) {
    try {
        $items = $pdo->query('SELECT tipe, kategori, url, caption FROM galeri ORDER BY created_at DESC')->fetchAll();
    } catch (PDOException $e) { /* fallback ke dummy di bawah */ }
}

if (!$items) {
    $items = [
        ['tipe' => 'foto',  'kategori' => 'praktek',   'url' => 'https://placehold.co/1000x750/132238/2F6FED?text=Praktik+1', 'caption' => 'Praktik crimping kabel UTP'],
        ['tipe' => 'foto',  'kategori' => 'praktek',   'url' => 'https://placehold.co/1000x750/132238/2F6FED?text=Praktik+2', 'caption' => 'Konfigurasi switch di lab'],
        ['tipe' => 'foto',  'kategori' => 'praktek',   'url' => 'https://placehold.co/1000x750/132238/2F6FED?text=Praktik+3', 'caption' => 'Penyambungan fiber optic'],
        ['tipe' => 'video', 'kategori' => 'kegiatan',  'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'caption' => null],
        ['tipe' => 'video', 'kategori' => 'kegiatan',  'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'caption' => null],
        ['tipe' => 'foto',  'kategori' => 'fasilitas', 'url' => 'https://placehold.co/1000x750/132238/17A398?text=Lab+1', 'caption' => 'Laboratorium Jaringan Utama'],
        ['tipe' => 'foto',  'kategori' => 'fasilitas', 'url' => 'https://placehold.co/1000x750/132238/17A398?text=Lab+2', 'caption' => 'Rak server praktik siswa'],
        ['tipe' => 'foto',  'kategori' => 'fasilitas', 'url' => 'https://placehold.co/1000x750/132238/17A398?text=Lab+3', 'caption' => 'Ruang praktik fiber optic'],
    ];
}

$base_url    = '../';
$active_page = 'galeri';
$page_title  = 'Galeri — TJKT';
require __DIR__ . '/../includes/header.php';
?>

<header class="relative pb-10 pt-36 sm:pb-14 sm:pt-44">
    <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-96 bg-gradient-to-b from-brand-primary/15 to-transparent"></div>
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-brand-primary">
            <span class="h-1.5 w-1.5 rounded-full bg-brand-primary"></span>
            Galeri Praktek
        </span>
        <h1 class="mt-5 max-w-2xl font-display text-3xl font-bold tracking-tight text-white sm:text-4xl lg:text-5xl">Album Foto &amp; Video Kegiatan</h1>
        <p class="mt-4 max-w-xl text-slate-400">Dokumentasi praktik, kegiatan, serta fasilitas dan laboratorium jurusan.</p>
    </div>
</header>

<section class="pb-24">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">

        <div class="mb-8 flex flex-wrap gap-2">
            <button class="tab-btn rounded-full bg-brand-primary px-5 py-2 text-sm font-semibold text-white transition" data-filter="semua">Semua</button>
            <button class="tab-btn rounded-full bg-white/5 px-5 py-2 text-sm font-semibold text-slate-300 transition hover:bg-white/10" data-filter="praktek">Foto Praktek</button>
            <button class="tab-btn rounded-full bg-white/5 px-5 py-2 text-sm font-semibold text-slate-300 transition hover:bg-white/10" data-filter="kegiatan">Video Kegiatan</button>
            <button class="tab-btn rounded-full bg-white/5 px-5 py-2 text-sm font-semibold text-slate-300 transition hover:bg-white/10" data-filter="fasilitas">Fasilitas &amp; Lab</button>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($items as $g): ?>
                <?php if ($g['tipe'] === 'video'): ?>
                <div class="gallery-item overflow-hidden rounded-3xl border border-white/10 bg-white/5" data-kategori="<?= htmlspecialchars($g['kategori']) ?>">
                    <div class="video-embed">
                        <iframe src="<?= htmlspecialchars($g['url']) ?>" title="<?= htmlspecialchars($g['caption'] ?: 'Video kegiatan TJKT') ?>" allowfullscreen></iframe>
                    </div>
                </div>
                <?php else: ?>
                <div class="gallery-item group cursor-pointer overflow-hidden rounded-3xl border border-white/10 bg-white/5" data-kategori="<?= htmlspecialchars($g['kategori']) ?>" data-full="<?= htmlspecialchars($g['url']) ?>">
                    <div class="aspect-[4/3] overflow-hidden">
                        <img src="<?= htmlspecialchars($g['url']) ?>" alt="<?= htmlspecialchars($g['caption'] ?? 'Galeri') ?>" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                    </div>
                    <?php if (!empty($g['caption'])): ?>
                    <span class="block p-4 text-sm text-slate-300"><?= htmlspecialchars($g['caption']) ?></span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Lightbox foto galeri -->
<div id="lightbox" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-950/90 p-6 backdrop-blur-sm">
    <button id="lightbox-close" aria-label="Tutup" class="absolute right-6 top-6 rounded-full bg-white/10 p-2 text-white hover:bg-white/20">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
    </button>
    <img id="lightbox-img" src="" alt="Tampilan penuh foto galeri" class="max-h-[85vh] max-w-full rounded-2xl border border-white/10 object-contain">
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
