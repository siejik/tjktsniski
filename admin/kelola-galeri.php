<?php
/**
 * admin/kelola-galeri.php
 * CRUD tabel `galeri` (tipe foto/video, kategori praktek/kegiatan/fasilitas).
 */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();
$admin = current_admin();
$pdo   = get_db();
$error = $pdo ? '' : 'Tidak bisa konek ke database. Cek konfigurasi di includes/db.php.';

if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_id'])) {
    $stmt = $pdo->prepare('DELETE FROM galeri WHERE id = ?');
    $stmt->execute([(int) $_POST['hapus_id']]);
    header('Location: kelola-galeri.php?deleted=1');
    exit;
}

if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    $id      = (int) ($_POST['id'] ?? 0);
    $tipe    = $_POST['tipe'] ?? 'foto';
    $kategori = $_POST['kategori'] ?? 'praktek';
    $url     = trim($_POST['url'] ?? '');
    $caption = trim($_POST['caption'] ?? '');

    if ($url === '') {
        $error = 'URL wajib diisi (path foto atau URL embed video).';
    } else {
        if ($id > 0) {
            $stmt = $pdo->prepare('UPDATE galeri SET tipe=?, kategori=?, url=?, caption=? WHERE id=?');
            $stmt->execute([$tipe, $kategori, $url, $caption ?: null, $id]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO galeri (tipe, kategori, url, caption) VALUES (?, ?, ?, ?)');
            $stmt->execute([$tipe, $kategori, $url, $caption ?: null]);
        }
        header('Location: kelola-galeri.php?saved=1');
        exit;
    }
}

$edit_row = null;
if ($pdo && isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM galeri WHERE id = ?');
    $stmt->execute([(int) $_GET['edit']]);
    $edit_row = $stmt->fetch() ?: null;
}

$daftar = [];
if ($pdo) {
    try {
        $daftar = $pdo->query('SELECT * FROM galeri ORDER BY created_at DESC')->fetchAll();
    } catch (PDOException $e) {
        $error = $error ?: 'Tabel `galeri` belum ada. Import database/schema.sql.';
    }
}

$page_title   = 'Kelola Galeri — TJKT Admin';
$active_admin = 'galeri';
require __DIR__ . '/../includes/admin-nav.php';
?>

<h1 class="font-display text-2xl font-bold tracking-tight text-white sm:text-3xl">Kelola Galeri</h1>
<p class="mt-2 text-slate-400">Foto praktek, video kegiatan, dan foto fasilitas &amp; lab.</p>

<?php if ($error): ?>
<p class="mt-6 rounded-2xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-300"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<?php if (isset($_GET['saved'])): ?>
<p class="mt-6 rounded-2xl border border-brand-secondary/30 bg-brand-secondary/10 p-4 text-sm text-emerald-200">Item galeri berhasil disimpan.</p>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
<p class="mt-6 rounded-2xl border border-brand-secondary/30 bg-brand-secondary/10 p-4 text-sm text-emerald-200">Item galeri berhasil dihapus.</p>
<?php endif; ?>

<div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-5">
    <div class="lg:col-span-2">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl">
            <h2 class="font-display text-lg font-semibold text-white"><?= $edit_row ? 'Edit Item' : 'Tambah Item' ?></h2>
            <form method="post" class="mt-4 flex flex-col gap-4">
                <input type="hidden" name="id" value="<?= (int) ($edit_row['id'] ?? 0) ?>">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-300">Tipe</label>
                    <select name="tipe" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary">
                        <?php foreach (['foto', 'video'] as $t): ?>
                        <option value="<?= $t ?>" <?= ($edit_row['tipe'] ?? '') === $t ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-300">Kategori</label>
                    <select name="kategori" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary">
                        <?php foreach (['praktek' => 'Foto Praktek', 'kegiatan' => 'Video Kegiatan', 'fasilitas' => 'Fasilitas & Lab'] as $val => $label): ?>
                        <option value="<?= $val ?>" <?= ($edit_row['kategori'] ?? '') === $val ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-300">URL</label>
                    <input type="text" name="url" required value="<?= htmlspecialchars($edit_row['url'] ?? '') ?>" placeholder="Path foto atau https://www.youtube.com/embed/..." class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary">
                    <p class="mt-1 text-xs text-slate-500">Untuk video, pakai URL <em>embed</em> YouTube (bukan link biasa).</p>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-300">Caption</label>
                    <input type="text" name="caption" value="<?= htmlspecialchars($edit_row['caption'] ?? '') ?>" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary">
                </div>
                <div class="flex gap-3">
                    <button type="submit" name="simpan" value="1" class="rounded-full bg-brand-primary px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-500">Simpan</button>
                    <?php if ($edit_row): ?>
                        <a href="kelola-galeri.php" class="rounded-full border border-white/15 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10">Batal</a>
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
                        <th class="px-5 py-3">Caption</th>
                        <th class="px-5 py-3">Tipe</th>
                        <th class="px-5 py-3">Kategori</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($daftar as $g): ?>
                    <tr>
                        <td class="px-5 py-4 text-white"><?= htmlspecialchars($g['caption'] ?: '—') ?></td>
                        <td class="px-5 py-4 text-slate-400"><?= htmlspecialchars($g['tipe']) ?></td>
                        <td class="px-5 py-4 text-slate-400"><?= htmlspecialchars($g['kategori']) ?></td>
                        <td class="px-5 py-4 text-right">
                            <a href="?edit=<?= (int) $g['id'] ?>" class="text-brand-primary hover:text-blue-400">Edit</a>
                            <form method="post" class="inline" onsubmit="return confirm('Hapus item ini?');">
                                <input type="hidden" name="hapus_id" value="<?= (int) $g['id'] ?>">
                                <button type="submit" class="ml-3 text-red-400 hover:text-red-300">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (!$daftar): ?>
                    <tr><td colspan="4" class="px-5 py-6 text-center text-slate-500">Belum ada item galeri.</td></tr>
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
