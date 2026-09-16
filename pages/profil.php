<?php
require_once __DIR__ . '/../includes/db.php';

$pdo = get_db();
$profil = null;

if ($pdo) {
    try {
        $profil = $pdo->query('SELECT * FROM profil_jurusan ORDER BY id DESC LIMIT 1')->fetch();
    } catch (PDOException $e) { /* fallback ke dummy di bawah */ }
}

$identitas  = $profil['identitas'] ?? 'Teknik Jaringan Komputer dan Telekomunikasi (TJKT) adalah program keahlian yang mempelajari perancangan, instalasi, konfigurasi, dan pemeliharaan sistem jaringan komputer serta perangkat telekomunikasi. Siswa dibekali kompetensi praktik langsung di laboratorium jaringan dan kesempatan magang (PKL) di industri mitra.';
$visi       = $profil['visi'] ?? 'Menjadi program keahlian unggulan yang menghasilkan lulusan kompeten, adaptif terhadap teknologi jaringan terkini, dan siap bersaing di dunia industri maupun dunia kerja.';

$misi = json_decode($profil['misi'] ?? '', true);
if (!$misi) {
    $misi = [
        'Menyelenggarakan pembelajaran berbasis praktik industri.',
        'Membangun kerja sama aktif dengan mitra industri jaringan & telekomunikasi.',
        'Membekali siswa dengan sertifikasi kompetensi yang relevan.',
        'Menumbuhkan sikap disiplin, mandiri, dan etos kerja profesional.',
    ];
}

$keunggulan = json_decode($profil['keunggulan'] ?? '', true);
if (!$keunggulan) {
    $keunggulan = [
        ['judul' => 'Routing & Switching', 'deskripsi' => 'Konfigurasi perangkat Cisco/Mikrotik, VLAN, dan manajemen jaringan skala kecil-menengah.'],
        ['judul' => 'Instalasi Fiber Optic', 'deskripsi' => 'Praktik penyambungan & pengukuran kabel fiber optic sesuai standar industri.'],
        ['judul' => 'Keamanan Jaringan Dasar', 'deskripsi' => 'Konsep firewall, access control, dan praktik pengamanan jaringan sederhana.'],
        ['judul' => 'Administrasi Server', 'deskripsi' => 'Instalasi & konfigurasi server dasar untuk kebutuhan jaringan lokal.'],
        ['judul' => 'Wireless & Telekomunikasi', 'deskripsi' => 'Dasar sistem komunikasi data nirkabel dan perangkat telekomunikasi.'],
        ['judul' => 'Praktik Kerja Industri', 'deskripsi' => 'Penempatan PKL di mitra industri jaringan & telekomunikasi terpercaya.'],
    ];
}

$base_url    = '../';
$active_page = 'profil';
$page_title  = 'Profil Jurusan — TJKT';
require __DIR__ . '/../includes/header.php';
?>

<header class="relative pb-16 pt-36 sm:pb-20 sm:pt-44">
    <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-96 bg-gradient-to-b from-brand-primary/15 to-transparent"></div>
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-brand-primary">
            <span class="h-1.5 w-1.5 rounded-full bg-brand-primary"></span>
            Profil Jurusan
        </span>
        <h1 class="mt-5 max-w-2xl font-display text-3xl font-bold tracking-tight text-white sm:text-4xl lg:text-5xl">Teknik Jaringan Komputer dan Telekomunikasi</h1>
        <p class="mt-4 max-w-xl text-slate-400">Mengenal identitas, arah, keunggulan kompetensi, dan prospek lulusan Jurusan TJKT.</p>
    </div>
</header>

<!-- IDENTITAS — grid responsive (fix overflow di layar HP) -->
<section class="py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <span class="mb-6 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-brand-secondary">
            <span class="h-1.5 w-1.5 rounded-full bg-brand-secondary"></span>
            Identitas Jurusan
        </span>
        <div class="grid grid-cols-1 items-start gap-8 rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl sm:grid-cols-[160px_1fr] sm:p-8">
            <div class="mx-auto w-32 shrink-0 overflow-hidden rounded-2xl sm:mx-0 sm:w-full">
                <img src="https://placehold.co/280x280/132238/EAF1FB?text=Logo" alt="Logo Jurusan TJKT" class="h-full w-full object-cover">
            </div>
            <div>
                <h3 class="font-display text-xl font-semibold text-white">Jurusan TJKT</h3>
                <p class="mt-3 leading-relaxed text-slate-400"><?= htmlspecialchars($identitas) ?></p>
            </div>
        </div>
    </div>
</section>

<!-- VISI & MISI -->
<section class="border-y border-white/10 bg-slate-900/40 py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <span class="mb-6 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-brand-primary">
            <span class="h-1.5 w-1.5 rounded-full bg-brand-primary"></span>
            Visi &amp; Misi
        </span>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur-xl">
                <h3 class="font-display text-lg font-semibold text-white">Visi</h3>
                <p class="mt-3 leading-relaxed text-slate-400"><?= htmlspecialchars($visi) ?></p>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur-xl">
                <h3 class="font-display text-lg font-semibold text-white">Misi</h3>
                <ul class="mt-3 flex flex-col gap-2.5 leading-relaxed text-slate-400">
                    <?php foreach ($misi as $m): ?>
                    <li class="flex gap-2"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-secondary"></span><?= htmlspecialchars($m) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- KEUNGGULAN & KOMPETENSI — bento -->
<section class="py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <span class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-brand-accent">
            <span class="h-1.5 w-1.5 rounded-full bg-brand-accent"></span>
            Keunggulan &amp; Kompetensi
        </span>
        <h2 class="mb-8 font-display text-2xl font-bold tracking-tight text-white sm:text-3xl">Apa yang dipelajari siswa TJKT</h2>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($keunggulan as $item): ?>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-7 backdrop-blur-xl transition hover:bg-white/[0.08]">
                <h3 class="font-display font-semibold text-white"><?= htmlspecialchars($item['judul']) ?></h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-400"><?= htmlspecialchars($item['deskripsi']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!--
    ALUMNI — copy umum jalur karier TJKT (bukan data alumni resmi sekolah).
    Belum ditarik dari database dengan sengaja: ini konten "marketing" yang
    jarang berubah. Kalau mau dibuat dinamis, tambahkan kolom baru di tabel
    `profil_jurusan` (mis. `alumni_prospek` JSON) — lihat catatan progres.
-->
<section class="relative overflow-hidden border-y border-white/10 bg-slate-900/40 py-16 sm:py-20">
    <div class="pointer-events-none absolute -right-24 -top-24 -z-10 h-72 w-72 rounded-full bg-brand-secondary/20 blur-3xl"></div>
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <span class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-brand-secondary">
            <span class="h-1.5 w-1.5 rounded-full bg-brand-secondary"></span>
            Alumni &amp; Prospek Lulusan
        </span>
        <h2 class="mb-3 font-display text-2xl font-bold tracking-tight text-white sm:text-3xl">Lulus dari TJKT, bisa jadi apa saja</h2>
        <p class="mb-8 max-w-2xl text-slate-400">Bekal kompetensi jaringan &amp; telekomunikasi membuka jalur yang luas — dari langsung bekerja di industri, melanjutkan kuliah vokasi, sampai membuka usaha sendiri.</p>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-7 backdrop-blur-xl transition hover:bg-white/[0.08] md:col-span-2">
                <span class="text-2xl">🏢</span>
                <h3 class="mt-3 font-display text-lg font-semibold text-white">Bekerja di Industri</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-400">Sebagai teknisi jaringan, network administrator, atau field engineer di ISP, penyedia telekomunikasi, data center, hingga perusahaan sistem integrator — mulai dari perusahaan lokal sampai nasional.</p>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-7 backdrop-blur-xl transition hover:bg-white/[0.08]">
                <span class="text-2xl">🎓</span>
                <h3 class="mt-3 font-display text-lg font-semibold text-white">Studi Lanjut</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-400">Melanjutkan ke Politeknik atau PTN/PTS vokasi jurusan Teknik Informatika, Jaringan, atau Telekomunikasi dengan bekal praktik yang sudah lebih matang.</p>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-7 backdrop-blur-xl transition hover:bg-white/[0.08]">
                <span class="text-2xl">🚀</span>
                <h3 class="mt-3 font-display text-lg font-semibold text-white">Wirausaha</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-400">Membuka jasa instalasi &amp; maintenance jaringan, RT/RW-net, atau layanan IT support sendiri untuk rumah, kantor, dan usaha kecil.</p>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-7 backdrop-blur-xl transition hover:bg-white/[0.08] md:col-span-2">
                <span class="text-2xl">🌏</span>
                <h3 class="mt-3 font-display text-lg font-semibold text-white">Sertifikasi &amp; Peluang Global</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-400">Bekal kompetensi jaringan sejalan dengan sertifikasi industri (mis. CCNA) yang diakui luas, membuka peluang kerja di berbagai kota bahkan luar negeri.</p>
            </div>
            <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-brand-secondary/20 to-transparent p-7 backdrop-blur-xl">
                <span class="text-2xl">🛠️</span>
                <h3 class="mt-3 font-display text-lg font-semibold text-white">Karier Terus Berkembang</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-400">Dari teknisi lapangan berkembang jadi network engineer, hingga posisi IT manager seiring pengalaman &amp; sertifikasi yang terus ditambah.</p>
            </div>
        </div>

        <p class="mt-6 text-xs text-slate-500">*Gambaran umum jalur karier lulusan TJKT — data alumni resmi &amp; testimoni akan ditambahkan lewat dashboard admin.</p>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
