<?php
/**
 * admin/kelola-flyer.php
 * CRUD tabel `flyer_pkl`.
 */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();
$admin = current_admin();
$pdo   = get_db();
$error = $pdo ? '' : 'Tidak bisa konek ke database. Cek konfigurasi di includes/db.php.';

if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_id'])) {
    $stmt = $pdo->prepare('DELETE FROM flyer_pkl WHERE id = ?');
    $stmt->execute([(int) $_POST['hapus_id']]);
    header('Location: kelola-flyer.php?deleted=1');
    exit;
}

if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    $id           = (int) ($_POST['id'] ?? 0);
    $nama_tempat  = trim($_POST['nama_tempat'] ?? '');
    $deskripsi    = trim($_POST['deskripsi'] ?? '');
    $gambar_flyer = trim($_POST['gambar_flyer'] ?? '');
    $kontak_mitra = trim($_POST['kontak_mitra'] ?? '');

    if ($nama_tempat === '' || $deskripsi === '') {
        $error = 'Nama tempat dan deskripsi wajib diisi.';
    } else {
        if ($id > 0) {
            $stmt = $pdo->prepare('UPDATE flyer_pkl SET nama_tempat=?, deskripsi=?, gambar_flyer=?, kontak_mitra=? WHERE id=?');
            $stmt->execute([$nama_tempat, $deskripsi, $gambar_flyer ?: null, $kontak_mitra ?: null, $id]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO flyer_pkl (nama_tempat, deskripsi, gambar_flyer, kontak_mitra) VALUES (?, ?, ?, ?)');
            $stmt->execute([$nama_tempat, $deskripsi, $gambar_flyer ?: null, $kontak_mitra ?: null]);
        }
        header('Location: kelola-flyer.php?saved=1');
        exit;
    }
}

$edit_row = null;
if ($pdo && isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM flyer_pkl WHERE id = ?');
    $stmt->execute([(int) $_GET['edit']]);
    $edit_row = $stmt->fetch() ?: null;
}

$daftar = [];
if ($pdo) {
    try {
        $daftar = $pdo->query('SELECT * FROM flyer_pkl ORDER BY created_at DESC')->fetchAll();
    } catch (PDOException $e) {
        $error = $error ?: 'Tabel `flyer_pkl` belum ada. Import database/schema.sql.';
    }
}

$page_title   = 'Kelola Flyer PKL — TJKT Admin';
$active_admin = 'flyer';
require __DIR__ . '/../includes/admin-nav.php';
?>

<h1 class="font-display text-2xl font-bold tracking-tight text-white sm:text-3xl">Kelola Flyer PKL</h1>
<p class="mt-2 text-slate-400">Daftar tempat PKL, flyer, dan kontak mitra industri.</p>

<?php if ($error): ?>
<p class="mt-6 rounded-2xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-300"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<?php if (isset($_GET['saved'])): ?>
<p class="mt-6 rounded-2xl border border-brand-secondary/30 bg-brand-secondary/10 p-4 text-sm text-emerald-200">Flyer berhasil disimpan.</p>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
<p class="mt-6 rounded-2xl border border-brand-secondary/30 bg-brand-secondary/10 p-4 text-sm text-emerald-200">Flyer berhasil dihapus.</p>
<?php endif; ?>

<div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-5">
    <div class="lg:col-span-2">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl">
            <h2 class="font-display text-lg font-semibold text-white"><?= $edit_row ? 'Edit Flyer' : 'Tambah Flyer' ?></h2>
            <form method="post" class="mt-4 flex flex-col gap-4">
                <input type="hidden" name="id" value="<?= (int) ($edit_row['id'] ?? 0) ?>">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-300">Nama Tempat</label>
                    <input type="text" name="nama_tempat" required value="<?= htmlspecialchars($edit_row['nama_tempat'] ?? '') ?>" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-300">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" required class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary"><?= htmlspecialchars($edit_row['deskripsi'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-300">URL Gambar Flyer</label>
                    <input type="text" name="gambar_flyer" value="<?= htmlspecialchars($edit_row['gambar_flyer'] ?? '') ?>" placeholder="https://..." class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-300">Kontak Mitra</label>
                    <input type="text" name="kontak_mitra" value="<?= htmlspecialchars($edit_row['kontak_mitra'] ?? '') ?>" placeholder="Nama — 08xx-xxxx-xxxx" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary">
                </div>
                <div class="flex gap-3">
                    <button type="submit" name="simpan" value="1" class="rounded-full bg-brand-primary px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-500">Simpan</button>
                    <?php if ($edit_row): ?>
                        <a href="kelola-flyer.php" class="rounded-full border border-white/15 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="lg:col-span-3">
        <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-white/10 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Nama Tempat</th>
                        <th class="px-5 py-3">Kontak</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($daftar as $f): ?>
                    <tr>
                        <td class="px-5 py-4 text-white"><?= htmlspecialchars($f['nama_tempat']) ?></td>
                        <td class="px-5 py-4 text-slate-400"><?= htmlspecialchars($f['kontak_mitra'] ?: '—') ?></td>
                        <td class="px-5 py-4 text-right">
                            <a href="?edit=<?= (int) $f['id'] ?>" class="text-brand-primary hover:text-blue-400">Edit</a>
                            <form method="post" class="inline" onsubmit="return confirm('Hapus flyer ini?');">
                                <input type="hidden" name="hapus_id" value="<?= (int) $f['id'] ?>">
                                <button type="submit" class="ml-3 text-red-400 hover:text-red-300">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (!$daftar): ?>
                    <tr><td colspan="3" class="px-5 py-6 text-center text-slate-500">Belum ada flyer PKL.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</main>
<script src="../assets/js/main.js"></script>
</body>
</html>
