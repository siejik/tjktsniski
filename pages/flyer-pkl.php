<?php
require_once __DIR__ . '/../includes/db.php';

$pdo    = get_db();
$daftar = [];

if ($pdo) {
    try {
        $daftar = $pdo->query('SELECT id, nama_tempat, deskripsi, gambar_flyer, kontak_mitra FROM flyer_pkl ORDER BY created_at DESC')->fetchAll();
    } catch (PDOException $e) { /* fallback ke dummy di bawah */ }
}

if (!$daftar) {
    $daftar = [
        ['id' => 1, 'nama_tempat' => 'PT Jaringan Nusantara', 'deskripsi' => 'PT Jaringan Nusantara membuka kesempatan PKL bagi siswa TJKT untuk terlibat langsung dalam instalasi jaringan pelanggan, pemasangan perangkat access point, hingga penanganan gangguan koneksi di lapangan. Kuota 4 siswa/semester, durasi 3 bulan.', 'gambar_flyer' => 'https://placehold.co/520x680/132238/EAF1FB?text=Flyer+PKL', 'kontak_mitra' => 'Bpk. Andi — 0812-1111-2222'],
        ['id' => 2, 'nama_tempat' => 'CV Telko Data Prima', 'deskripsi' => 'Praktik operasional server & monitoring jaringan data center skala kecil-menengah.', 'gambar_flyer' => 'https://placehold.co/520x680/132238/EAF1FB?text=Flyer+2', 'kontak_mitra' => '0812-3333-4444'],
        ['id' => 3, 'nama_tempat' => 'PT Sinyal Komunika', 'deskripsi' => 'Praktik instalasi perangkat BTS & pengukuran fiber optic di lapangan.', 'gambar_flyer' => 'https://placehold.co/520x680/132238/EAF1FB?text=Flyer+3', 'kontak_mitra' => '0812-5555-6666'],
        ['id' => 4, 'nama_tempat' => 'PT Network Solusi Indonesia', 'deskripsi' => 'Praktik implementasi jaringan perusahaan skala menengah, termasuk keamanan jaringan dasar.', 'gambar_flyer' => 'https://placehold.co/520x680/132238/EAF1FB?text=Flyer+4', 'kontak_mitra' => '0812-7777-8888'],
    ];
}

// Flyer yang ditampilkan di section "Detail Flyer" — lewat ?id=, default yang pertama.
$selectedId = isset($_GET['id']) ? (int) $_GET['id'] : ($daftar[0]['id'] ?? 0);
$detail     = null;
foreach ($daftar as $f) {
    if ((int) $f['id'] === $selectedId) {
        $detail = $f;
        break;
    }
}
$detail = $detail ?: ($daftar[0] ?? null);

$base_url    = '../';
$active_page = 'pkl';
$page_title  = 'Flyer PKL — TJKT';
require __DIR__ . '/../includes/header.php';
?>

<header class="relative pb-16 pt-36 sm:pb-20 sm:pt-44">
    <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-96 bg-gradient-to-b from-brand-accent/15 to-transparent"></div>
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-brand-accent">
            <span class="h-1.5 w-1.5 rounded-full bg-brand-accent"></span>
            Flyer PKL
        </span>
        <h1 class="mt-5 max-w-2xl font-display text-3xl font-bold tracking-tight text-white sm:text-4xl lg:text-5xl">Tempat Praktik Kerja Lapangan</h1>
        <p class="mt-4 max-w-xl text-slate-400">Informasi tempat PKL, flyer detail, dan daftar mitra industri Jurusan TJKT.</p>
    </div>
</header>

<!-- DAFTAR TEMPAT PKL -->
<section class="pb-16 sm:pb-20">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <h2 class="mb-8 font-display text-2xl font-bold tracking-tight text-white sm:text-3xl">Daftar Tempat PKL</h2>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <?php foreach ($daftar as $f): ?>
            <a href="flyer-pkl.php?id=<?= (int) $f['id'] ?>#detail" class="flex flex-col gap-5 rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl transition hover:bg-white/[0.08] sm:flex-row">
                <div class="mx-auto aspect-[3/4] w-32 shrink-0 overflow-hidden rounded-2xl sm:mx-0">
                    <img src="<?= htmlspecialchars($f['gambar_flyer'] ?: 'https://placehold.co/260x340/132238/EAF1FB?text=Flyer') ?>" alt="Flyer <?= htmlspecialchars($f['nama_tempat']) ?>" class="h-full w-full object-cover">
                </div>
                <div class="min-w-0">
                    <h3 class="mt-2 font-display text-lg font-semibold text-white"><?= htmlspecialchars($f['nama_tempat']) ?></h3>
                    <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-slate-400"><?= htmlspecialchars($f['deskripsi']) ?></p>
                    <?php if (!empty($f['kontak_mitra'])): ?>
                    <span class="mt-3 block text-sm text-slate-300">📞 <?= htmlspecialchars($f['kontak_mitra']) ?></span>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!--
    DETAIL FLYER — sebelumnya pakai grid-template-columns fixed (260px 1fr)
    tanpa breakpoint, itu penyebab overflow horizontal di HP (lihat laporan
    revisi). Sekarang grid-cols-1 di mobile, baru jadi 2 kolom mulai md:.
    Konten dipilih lewat ?id= dari daftar di atas.
-->
<?php if ($detail): ?>
<section id="detail" class="border-y border-white/10 bg-slate-900/40 py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <span class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-brand-accent">
            <span class="h-1.5 w-1.5 rounded-full bg-brand-accent"></span>
            Detail Flyer
        </span>
        <h2 class="mb-8 font-display text-2xl font-bold tracking-tight text-white sm:text-3xl"><?= htmlspecialchars($detail['nama_tempat']) ?></h2>

        <div class="grid grid-cols-1 items-start gap-8 md:grid-cols-[300px_1fr]">
            <div class="mx-auto aspect-[3/4] w-full max-w-xs overflow-hidden rounded-3xl border border-white/10 md:mx-0">
                <img src="<?= htmlspecialchars($detail['gambar_flyer'] ?: 'https://placehold.co/520x680/132238/EAF1FB?text=Flyer') ?>" alt="Flyer detail <?= htmlspecialchars($detail['nama_tempat']) ?>" class="h-full w-full object-cover">
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl sm:p-8">
                <p class="leading-relaxed text-slate-300"><?= htmlspecialchars($detail['deskripsi']) ?></p>
                <?php if (!empty($detail['kontak_mitra'])): ?>
                <dl class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Kontak Mitra</dt>
                        <dd class="mt-1 text-white"><?= htmlspecialchars($detail['kontak_mitra']) ?></dd>
                    </div>
                </dl>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- MITRA INDUSTRI -->
<section class="py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <span class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-brand-primary">
            <span class="h-1.5 w-1.5 rounded-full bg-brand-primary"></span>
            Mitra Industri
        </span>
        <h2 class="mb-8 font-display text-2xl font-bold tracking-tight text-white sm:text-3xl">Perusahaan Mitra PKL</h2>

        <div class="flex flex-wrap items-center gap-4">
            <?php foreach ($daftar as $i => $f): ?>
            <div class="flex h-16 w-32 items-center justify-center rounded-2xl border border-white/10 bg-white/5 p-3"><img src="https://placehold.co/120x50/0f172a/8FA3BF?text=Mitra+<?= $i + 1 ?>" alt="Logo <?= htmlspecialchars($f['nama_tempat']) ?>" class="max-h-full max-w-full"></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
