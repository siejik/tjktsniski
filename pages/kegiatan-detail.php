<?php
require_once __DIR__ . '/../includes/db.php';

$pdo  = get_db();
$slug = $_GET['slug'] ?? '';
$item = null;

if ($pdo && $slug !== '') {
    try {
        $stmt = $pdo->prepare('SELECT judul, deskripsi, tanggal, gambar FROM kegiatan WHERE slug = ? LIMIT 1');
        $stmt->execute([$slug]);
        $item = $stmt->fetch();
    } catch (PDOException $e) { /* fallback ke dummy di bawah */ }
}

if (!$item) {
    $item = [
        'judul'     => 'Praktik Konfigurasi Router & Switch',
        'deskripsi' => "Pada kegiatan ini, siswa kelas XI TJKT melakukan praktik langsung konfigurasi perangkat router dan switch di laboratorium jaringan. Praktik difokuskan pada pemahaman routing statis, pembagian VLAN antar-departemen simulasi, serta pengujian konektivitas antar-segmen jaringan.\n\nKegiatan ini merupakan bagian dari rangkaian pembelajaran praktik berbasis proyek yang dirancang agar siswa terbiasa dengan skenario nyata di lapangan kerja, termasuk troubleshooting dasar ketika konfigurasi tidak berjalan sesuai rencana.\n\nSelain aspek teknis, siswa juga dilatih untuk mendokumentasikan setiap langkah konfigurasi sebagai bagian dari standar kerja profesional di industri jaringan.",
        'tanggal'   => '2026-08-12',
        'gambar'    => 'https://placehold.co/1200x520/0f172a/8FA3BF?text=Praktik+Konfigurasi+Jaringan',
    ];
}

$base_url    = '../';
$active_page = 'kegiatan';
$page_title  = htmlspecialchars($item['judul']) . ' — Kegiatan TJKT';
require __DIR__ . '/../includes/header.php';
?>

<div class="pt-28 sm:pt-32">
    <div class="mx-auto max-w-4xl px-6 lg:px-8">
        <a href="kegiatan.php" class="inline-flex items-center gap-2 text-sm font-medium text-slate-400 transition hover:text-white">← Kembali ke Kegiatan</a>
    </div>
</div>

<article class="py-10 sm:py-14">
    <div class="mx-auto max-w-4xl px-6 lg:px-8">
        <div class="overflow-hidden rounded-3xl border border-white/10">
            <img src="<?= htmlspecialchars($item['gambar'] ?: 'https://placehold.co/1200x520/0f172a/8FA3BF?text=Kegiatan') ?>" alt="<?= htmlspecialchars($item['judul']) ?>" class="h-full w-full object-cover">
        </div>

        <h1 class="mt-8 font-display text-2xl font-bold tracking-tight text-white sm:text-3xl lg:text-4xl"><?= htmlspecialchars($item['judul']) ?></h1>

        <div class="mt-4 flex flex-wrap gap-x-6 gap-y-2 border-b border-white/10 pb-6 text-sm text-slate-500">
            <span>📅 <?= date('d F Y', strtotime($item['tanggal'])) ?></span>
            <span>✍️ Ditulis oleh Admin Jurusan</span>
        </div>

        <div class="mt-8 flex flex-col gap-5 text-base leading-relaxed text-slate-300">
            <?php foreach (explode("\n\n", $item['deskripsi']) as $paragraf): ?>
                <?php if (trim($paragraf) !== ''): ?>
                <p><?= nl2br(htmlspecialchars($paragraf)) ?></p>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</article>

<?php require __DIR__ . '/../includes/footer.php'; ?>
