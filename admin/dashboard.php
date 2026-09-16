<?php
/**
 * admin/dashboard.php
 * Ringkasan statistik + quick links ke semua halaman kelola konten, plus
 * toggle sederhana untuk plugin (mis. chat-agent).
 */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();
$admin = current_admin();

$pdo = get_db();
$stats = ['kegiatan' => 0, 'galeri' => 0, 'flyer_pkl' => 0];
$plugins = [];
$db_error = !$pdo;

if ($pdo) {
    try {
        foreach (array_keys($stats) as $table) {
            $stats[$table] = (int) $pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
        }
        $plugins = $pdo->query('SELECT id, nama_plugin, status_aktif FROM plugins ORDER BY nama_plugin')->fetchAll();
    } catch (PDOException $e) {
        $db_error = true;
    }
}

// Toggle status plugin (on/off)
if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_plugin_id'])) {
    $stmt = $pdo->prepare('UPDATE plugins SET status_aktif = NOT status_aktif WHERE id = ?');
    $stmt->execute([(int) $_POST['toggle_plugin_id']]);
    header('Location: dashboard.php');
    exit;
}

$page_title   = 'Dashboard Admin — TJKT';
$active_admin = 'dashboard';
require __DIR__ . '/../includes/admin-nav.php';
?>

<h1 class="font-display text-2xl font-bold tracking-tight text-white sm:text-3xl">Ringkasan Statistik</h1>
<p class="mt-2 text-slate-400">Data langsung dari database.</p>

<?php if ($db_error): ?>
<p class="mt-6 rounded-2xl border border-amber-500/30 bg-amber-500/10 p-4 text-sm text-amber-200">
    Belum bisa konek ke database. Cek konfigurasi di <code>includes/db.php</code> dan pastikan <code>database/schema.sql</code> sudah di-import.
</p>
<?php endif; ?>

<div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-3">
    <div class="rounded-3xl border border-white/10 bg-white/5 p-7 backdrop-blur-xl">
        <span class="text-xs font-semibold uppercase tracking-wider text-brand-primary">Kegiatan</span>
        <p class="mt-2 font-display text-4xl font-bold text-white"><?= $stats['kegiatan'] ?></p>
    </div>
    <div class="rounded-3xl border border-white/10 bg-white/5 p-7 backdrop-blur-xl">
        <span class="text-xs font-semibold uppercase tracking-wider text-brand-secondary">Item Galeri</span>
        <p class="mt-2 font-display text-4xl font-bold text-white"><?= $stats['galeri'] ?></p>
    </div>
    <div class="rounded-3xl border border-white/10 bg-white/5 p-7 backdrop-blur-xl">
        <span class="text-xs font-semibold uppercase tracking-wider text-brand-accent">Tempat PKL</span>
        <p class="mt-2 font-display text-4xl font-bold text-white"><?= $stats['flyer_pkl'] ?></p>
    </div>
</div>

<div class="mt-12">
    <h2 class="font-display text-lg font-semibold text-white">Kelola Konten</h2>
    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <a href="kelola-kegiatan.php" class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl transition hover:bg-white/[0.08]">
            <span class="font-display font-semibold text-white">Kelola Kegiatan</span>
            <p class="mt-1 text-sm text-slate-400">Tambah, edit, hapus postingan kegiatan.</p>
        </a>
        <a href="kelola-galeri.php" class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl transition hover:bg-white/[0.08]">
            <span class="font-display font-semibold text-white">Kelola Galeri</span>
            <p class="mt-1 text-sm text-slate-400">Foto praktek, video kegiatan, foto fasilitas.</p>
        </a>
        <a href="kelola-flyer.php" class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl transition hover:bg-white/[0.08]">
            <span class="font-display font-semibold text-white">Kelola Flyer PKL</span>
            <p class="mt-1 text-sm text-slate-400">Tempat PKL, flyer, kontak mitra.</p>
        </a>
        <a href="kelola-profil.php" class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl transition hover:bg-white/[0.08]">
            <span class="font-display font-semibold text-white">Kelola Profil Jurusan</span>
            <p class="mt-1 text-sm text-slate-400">Identitas, visi misi, keunggulan.</p>
        </a>
        <a href="kelola-akun.php" class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl transition hover:bg-white/[0.08]">
            <span class="font-display font-semibold text-white">Kelola Akun</span>
            <p class="mt-1 text-sm text-slate-400">Tambah admin, ganti password.</p>
        </a>
        <a href="pengaturan.php" class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl transition hover:bg-white/[0.08]">
            <span class="font-display font-semibold text-white">Pengaturan Situs</span>
            <p class="mt-1 text-sm text-slate-400">Nama sekolah, kontak email/WA.</p>
        </a>
    </div>
</div>

<div class="mt-12">
    <h2 class="font-display text-lg font-semibold text-white">Plugin</h2>
    <p class="mt-1 text-sm text-slate-400">Aktifkan/nonaktifkan fitur modular. Widget hanya tampil di situs publik kalau statusnya aktif.</p>
    <div class="mt-4 space-y-3">
        <?php if (!$plugins): ?>
            <p class="rounded-2xl border border-dashed border-white/15 bg-white/[0.03] p-6 text-sm text-slate-400">Belum ada plugin terdaftar (cek tabel <code>plugins</code>).</p>
        <?php endif; ?>
        <?php foreach ($plugins as $p): ?>
        <form method="post" class="flex items-center justify-between rounded-2xl border border-white/10 bg-white/5 p-5">
            <div>
                <span class="font-semibold text-white"><?= htmlspecialchars($p['nama_plugin']) ?></span>
                <span class="ml-2 text-xs <?= $p['status_aktif'] ? 'text-brand-secondary' : 'text-slate-500' ?>"><?= $p['status_aktif'] ? '● Aktif' : '○ Nonaktif' ?></span>
            </div>
            <input type="hidden" name="toggle_plugin_id" value="<?= (int) $p['id'] ?>">
            <button type="submit" class="rounded-full border border-white/15 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/10">
                <?= $p['status_aktif'] ? 'Nonaktifkan' : 'Aktifkan' ?>
            </button>
        </form>
        <?php endforeach; ?>
    </div>
</div>

</main>
</body>
</html>
