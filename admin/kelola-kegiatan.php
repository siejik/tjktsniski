<?php
/**
 * admin/kelola-kegiatan.php
 * CRUD tabel `kegiatan`. List + form tambah/edit (mode edit lewat ?edit=ID) + hapus.
 */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();
$admin = current_admin();
$pdo   = get_db();

function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

$error = '';
$success = '';

if (!$pdo) {
    $error = 'Tidak bisa konek ke database. Cek konfigurasi di includes/db.php.';
}

// ---- Hapus ----
if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_id'])) {
    $stmt = $pdo->prepare('DELETE FROM kegiatan WHERE id = ?');
    $stmt->execute([(int) $_POST['hapus_id']]);
    header('Location: kelola-kegiatan.php?deleted=1');
    exit;
}

// ---- Simpan (tambah / edit) ----
if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    $id        = (int) ($_POST['id'] ?? 0);
    $judul     = trim($_POST['judul'] ?? '');
    $slug      = trim($_POST['slug'] ?? '') ?: slugify($judul);
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $tanggal   = $_POST['tanggal'] ?? '';
    $gambar    = trim($_POST['gambar'] ?? '');

    if ($judul === '' || $deskripsi === '' || $tanggal === '') {
        $error = 'Judul, deskripsi, dan tanggal wajib diisi.';
    } else {
        try {
            if ($id > 0) {
                $stmt = $pdo->prepare('UPDATE kegiatan SET judul=?, slug=?, deskripsi=?, tanggal=?, gambar=? WHERE id=?');
                $stmt->execute([$judul, $slug, $deskripsi, $tanggal, $gambar ?: null, $id]);
            } else {
                $stmt = $pdo->prepare('INSERT INTO kegiatan (judul, slug, deskripsi, tanggal, gambar, created_by) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->execute([$judul, $slug, $deskripsi, $tanggal, $gambar ?: null, $admin['id']]);
            }
            header('Location: kelola-kegiatan.php?saved=1');
            exit;
        } catch (PDOException $e) {
            $error = $e->getCode() === '23000'
                ? 'Slug sudah dipakai kegiatan lain — ganti slug-nya.'
                : 'Gagal menyimpan data.';
        }
    }
}

// ---- Ambil data untuk mode edit ----
$edit_row = null;
if ($pdo && isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM kegiatan WHERE id = ?');
    $stmt->execute([(int) $_GET['edit']]);
    $edit_row = $stmt->fetch() ?: null;
}

// ---- List semua kegiatan ----
$daftar = [];
if ($pdo) {
    try {
        $daftar = $pdo->query('SELECT * FROM kegiatan ORDER BY tanggal DESC')->fetchAll();
    } catch (PDOException $e) {
        $error = $error ?: 'Tabel `kegiatan` belum ada. Import database/schema.sql.';
    }
}

$page_title   = 'Kelola Kegiatan — TJKT Admin';
$active_admin = 'kegiatan';
require __DIR__ . '/../includes/admin-nav.php';
?>

<h1 class="font-display text-2xl font-bold tracking-tight text-white sm:text-3xl">Kelola Kegiatan</h1>
<p class="mt-2 text-slate-400">Tambah, edit, dan hapus postingan kegiatan.</p>

<?php if ($error): ?>
<p class="mt-6 rounded-2xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-300"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<?php if (isset($_GET['saved'])): ?>
<p class="mt-6 rounded-2xl border border-brand-secondary/30 bg-brand-secondary/10 p-4 text-sm text-emerald-200">Kegiatan berhasil disimpan.</p>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
<p class="mt-6 rounded-2xl border border-brand-secondary/30 bg-brand-secondary/10 p-4 text-sm text-emerald-200">Kegiatan berhasil dihapus.</p>
<?php endif; ?>

<div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-5">
    <!-- Form tambah/edit -->
    <div class="lg:col-span-2">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl">
            <h2 class="font-display text-lg font-semibold text-white"><?= $edit_row ? 'Edit Kegiatan' : 'Tambah Kegiatan' ?></h2>
            <form method="post" class="mt-4 flex flex-col gap-4">
                <input type="hidden" name="id" value="<?= (int) ($edit_row['id'] ?? 0) ?>">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-300">Judul</label>
                    <input type="text" name="judul" required value="<?= htmlspecialchars($edit_row['judul'] ?? '') ?>" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-300">Slug <span class="text-slate-500">(kosongkan = otomatis dari judul)</span></label>
                    <input type="text" name="slug" value="<?= htmlspecialchars($edit_row['slug'] ?? '') ?>" placeholder="contoh-slug-url" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-300">Deskripsi</label>
                    <textarea name="deskripsi" rows="5" required class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary"><?= htmlspecialchars($edit_row['deskripsi'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-300">Tanggal</label>
                    <input type="date" name="tanggal" required value="<?= htmlspecialchars($edit_row['tanggal'] ?? '') ?>" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-300">URL Gambar</label>
                    <input type="text" name="gambar" value="<?= htmlspecialchars($edit_row['gambar'] ?? '') ?>" placeholder="https://..." class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary">
                </div>
                <div class="flex gap-3">
                    <button type="submit" name="simpan" value="1" class="rounded-full bg-brand-primary px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-500">Simpan</button>
                    <?php if ($edit_row): ?>
                        <a href="kelola-kegiatan.php" class="rounded-full border border-white/15 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- List -->
    <div class="lg:col-span-3">
        <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-white/10 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Judul</th>
                        <th class="px-5 py-3">Tanggal</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($daftar as $k): ?>
                    <tr>
                        <td class="px-5 py-4 text-white"><?= htmlspecialchars($k['judul']) ?></td>
                        <td class="px-5 py-4 text-slate-400"><?= date('d M Y', strtotime($k['tanggal'])) ?></td>
                        <td class="px-5 py-4 text-right">
                            <a href="?edit=<?= (int) $k['id'] ?>" class="text-brand-primary hover:text-blue-400">Edit</a>
                            <form method="post" class="inline" onsubmit="return confirm('Hapus kegiatan ini?');">
                                <input type="hidden" name="hapus_id" value="<?= (int) $k['id'] ?>">
                                <button type="submit" class="ml-3 text-red-400 hover:text-red-300">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (!$daftar): ?>
                    <tr><td colspan="3" class="px-5 py-6 text-center text-slate-500">Belum ada kegiatan.</td></tr>
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
