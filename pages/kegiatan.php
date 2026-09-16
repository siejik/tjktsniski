<?php
require_once __DIR__ . '/../includes/db.php';

$pdo = get_db();
$daftar = [];

if ($pdo) {
    try {
        $daftar = $pdo->query('SELECT judul, slug, deskripsi, tanggal, gambar FROM kegiatan ORDER BY tanggal DESC')->fetchAll();
    } catch (PDOException $e) { /* fallback ke dummy di bawah */ }
}

if (!$daftar) {
    $daftar = [
        ['judul' => 'Praktik Konfigurasi Router & Switch', 'slug' => '', 'deskripsi' => 'Siswa kelas XI melakukan praktik konfigurasi routing statis & VLAN di lab jaringan.', 'tanggal' => '2026-08-12', 'gambar' => 'https://placehold.co/480x360/0f172a/8FA3BF?text=Kegiatan+1'],
        ['judul' => 'LKS Bidang IT Network Systems Administration', 'slug' => '', 'deskripsi' => 'Perwakilan TJKT meraih juara 2 tingkat kota pada ajang LKS tahun ini.', 'tanggal' => '2026-09-02', 'gambar' => 'https://placehold.co/480x360/0f172a/8FA3BF?text=Kegiatan+2'],
        ['judul' => 'Kunjungan ke Data Center Mitra Industri', 'slug' => '', 'deskripsi' => 'Siswa mengunjungi fasilitas data center salah satu mitra PKL untuk melihat operasional langsung.', 'tanggal' => '2026-09-20', 'gambar' => 'https://placehold.co/480x360/0f172a/8FA3BF?text=Kegiatan+3'],
        ['judul' => 'Uji Sertifikasi Kompetensi Keahlian', 'slug' => '', 'deskripsi' => 'Siswa kelas XII mengikuti uji kompetensi sebagai syarat kelulusan jurusan.', 'tanggal' => '2026-10-05', 'gambar' => 'https://placehold.co/480x360/0f172a/8FA3BF?text=Kegiatan+4'],
        ['judul' => 'Workshop Fiber Optic bersama Praktisi Industri', 'slug' => '', 'deskripsi' => 'Menghadirkan praktisi dari mitra industri untuk berbagi praktik penyambungan fiber optic.', 'tanggal' => '2026-10-18', 'gambar' => 'https://placehold.co/480x360/0f172a/8FA3BF?text=Kegiatan+5'],
        ['judul' => 'Study Tour Politeknik & Kampus Vokasi', 'slug' => '', 'deskripsi' => 'Agenda kunjungan kampus vokasi untuk memperkenalkan jenjang studi lanjutan.', 'tanggal' => '2026-11-15', 'gambar' => 'https://placehold.co/480x360/0f172a/8FA3BF?text=Kegiatan+6'],
    ];
}

$base_url    = '../';
$active_page = 'kegiatan';
$page_title  = 'Kegiatan — TJKT';
require __DIR__ . '/../includes/header.php';
?>

<header class="relative pb-16 pt-36 sm:pb-20 sm:pt-44">
    <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-96 bg-gradient-to-b from-brand-secondary/15 to-transparent"></div>
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-brand-secondary">
            <span class="h-1.5 w-1.5 rounded-full bg-brand-secondary"></span>
            Info Kegiatan
        </span>
        <h1 class="mt-5 max-w-2xl font-display text-3xl font-bold tracking-tight text-white sm:text-4xl lg:text-5xl">Kegiatan &amp; Agenda Jurusan</h1>
        <p class="mt-4 max-w-xl text-slate-400">Dokumentasi praktik, lomba, kunjungan industri, dan agenda yang akan datang.</p>
    </div>
</header>

<section class="pb-24">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($daftar as $k): ?>
            <article class="group overflow-hidden rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl transition hover:bg-white/[0.08]">
                <div class="aspect-[4/3] overflow-hidden">
                    <img src="<?= htmlspecialchars($k['gambar'] ?: 'https://placehold.co/480x360/0f172a/8FA3BF?text=Kegiatan') ?>" alt="<?= htmlspecialchars($k['judul']) ?>" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                </div>
                <div class="p-6">
                    <h3 class="font-display text-lg font-semibold text-white"><?= htmlspecialchars($k['judul']) ?></h3>
                    <span class="mt-2 block text-sm text-slate-500"><?= date('d F Y', strtotime($k['tanggal'])) ?></span>
                    <p class="mt-3 text-sm leading-relaxed text-slate-400"><?= htmlspecialchars($k['deskripsi']) ?></p>
                    <a href="kegiatan-detail.php<?= $k['slug'] ? '?slug=' . urlencode($k['slug']) : '' ?>" class="mt-4 inline-flex text-sm font-semibold text-brand-primary hover:text-blue-400">Baca selengkapnya →</a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
