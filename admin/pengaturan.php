<?php
/**
 * admin/pengaturan.php
 * Edit key-value tabel `pengaturan_situs` (nama sekolah, kontak, dsb).
 * Semua baris yang ada di tabel dirender otomatis jadi field form —
 * jadi kalau ada key baru ditambah lewat SQL, langsung muncul di sini.
 */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();
$admin = current_admin();
$pdo   = get_db();
$error = $pdo ? '' : 'Tidak bisa konek ke database. Cek konfigurasi di includes/db.php.';
$success = false;

$label_map = [
    'nama_sekolah'    => 'Nama Sekolah',
    'warna_primer'    => 'Warna Primer (hex)',
    'warna_sekunder'  => 'Warna Sekunder (hex)',
    'kontak_email'    => 'Email Kontak',
    'kontak_whatsapp' => 'Nomor WhatsApp (format 62xxxxxxxxxx)',
];

if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('UPDATE pengaturan_situs SET value = ? WHERE setting_key = ?');
    foreach ($_POST['setting'] ?? [] as $key => $value) {
        $stmt->execute([trim($value), $key]);
    }
    $success = true;
}

$rows = [];
if ($pdo) {
    try {
        $rows = $pdo->query('SELECT setting_key, value FROM pengaturan_situs ORDER BY setting_key')->fetchAll();
    } catch (PDOException $e) {
        $error = $error ?: 'Tabel `pengaturan_situs` belum ada. Import database/schema.sql.';
    }
}

$page_title   = 'Pengaturan Situs — TJKT Admin';
$active_admin = 'pengaturan';
require __DIR__ . '/../includes/admin-nav.php';
?>

<h1 class="font-display text-2xl font-bold tracking-tight text-white sm:text-3xl">Pengaturan Umum Situs</h1>
<p class="mt-2 text-slate-400">Nama sekolah, kontak, dan pengaturan tema dasar.</p>

<?php if ($error): ?>
<p class="mt-6 rounded-2xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-300"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<?php if ($success): ?>
<p class="mt-6 rounded-2xl border border-brand-secondary/30 bg-brand-secondary/10 p-4 text-sm text-emerald-200">Pengaturan berhasil disimpan.</p>
<?php endif; ?>

<form method="post" class="mt-8 max-w-xl rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl">
    <div class="flex flex-col gap-4">
        <?php foreach ($rows as $r): ?>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-300"><?= htmlspecialchars($label_map[$r['setting_key']] ?? $r['setting_key']) ?></label>
            <input type="text" name="setting[<?= htmlspecialchars($r['setting_key']) ?>]" value="<?= htmlspecialchars($r['value']) ?>" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary">
        </div>
        <?php endforeach; ?>
        <?php if (!$rows): ?>
        <p class="text-sm text-slate-500">Belum ada pengaturan tersimpan.</p>
        <?php endif; ?>
    </div>
    <button type="submit" class="mt-6 rounded-full bg-brand-primary px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-500">Simpan Pengaturan</button>
</form>

<p class="mt-4 max-w-xl text-xs text-slate-500">Catatan: mengubah warna di sini belum otomatis mengubah tema visual situs (tema masih hardcode di CSS/Tailwind config) — cocok untuk pencatatan/rencana rebrand, penerapan otomatisnya bisa dikembangkan lebih lanjut.</p>

</main>
<script src="../assets/js/main.js"></script>
</body>
</html>
