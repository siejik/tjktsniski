<?php
/**
 * admin/kelola-profil.php
 * Edit satu baris tabel `profil_jurusan`. `misi` disimpan sebagai JSON array
 * string, `keunggulan` sebagai JSON array of {judul, deskripsi}. UI-nya pakai
 * baris dinamis (tambah/hapus) yang di-assemble jadi JSON di sisi PHP saat
 * disimpan — JS di halaman ini cuma urusan tambah/hapus baris di DOM.
 */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();
$admin = current_admin();
$pdo   = get_db();
$error = $pdo ? '' : 'Tidak bisa konek ke database. Cek konfigurasi di includes/db.php.';
$success = false;

if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $identitas = trim($_POST['identitas'] ?? '');
    $visi      = trim($_POST['visi'] ?? '');
    $misi_arr  = array_values(array_filter(array_map('trim', $_POST['misi'] ?? []), fn($v) => $v !== ''));

    $keu_judul = $_POST['keunggulan_judul'] ?? [];
    $keu_desk  = $_POST['keunggulan_deskripsi'] ?? [];
    $keunggulan_arr = [];
    foreach ($keu_judul as $i => $judul) {
        $judul = trim($judul);
        $desk  = trim($keu_desk[$i] ?? '');
        if ($judul !== '') {
            $keunggulan_arr[] = ['judul' => $judul, 'deskripsi' => $desk];
        }
    }

    if ($identitas === '' || $visi === '') {
        $error = 'Identitas dan visi wajib diisi.';
    } else {
        $misi_json       = json_encode($misi_arr, JSON_UNESCAPED_UNICODE);
        $keunggulan_json = json_encode($keunggulan_arr, JSON_UNESCAPED_UNICODE);

        $count = (int) $pdo->query('SELECT COUNT(*) FROM profil_jurusan')->fetchColumn();
        if ($count > 0) {
            $stmt = $pdo->prepare('UPDATE profil_jurusan SET identitas=?, visi=?, misi=?, keunggulan=? ORDER BY id ASC LIMIT 1');
            $stmt->execute([$identitas, $visi, $misi_json, $keunggulan_json]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO profil_jurusan (identitas, visi, misi, keunggulan) VALUES (?, ?, ?, ?)');
            $stmt->execute([$identitas, $visi, $misi_json, $keunggulan_json]);
        }
        $success = true;
    }
}

$profil = null;
if ($pdo) {
    try {
        $profil = $pdo->query('SELECT * FROM profil_jurusan ORDER BY id ASC LIMIT 1')->fetch();
    } catch (PDOException $e) {
        $error = $error ?: 'Tabel `profil_jurusan` belum ada. Import database/schema.sql.';
    }
}

$identitas = $profil['identitas'] ?? '';
$visi      = $profil['visi'] ?? '';
$misi      = $profil ? (json_decode($profil['misi'], true) ?: []) : [];
$keunggulan = $profil ? (json_decode($profil['keunggulan'], true) ?: []) : [];
if (!$misi) $misi = [''];
if (!$keunggulan) $keunggulan = [['judul' => '', 'deskripsi' => '']];

$page_title   = 'Kelola Profil Jurusan — TJKT Admin';
$active_admin = 'profil';
require __DIR__ . '/../includes/admin-nav.php';
?>

<h1 class="font-display text-2xl font-bold tracking-tight text-white sm:text-3xl">Kelola Profil Jurusan</h1>
<p class="mt-2 text-slate-400">Identitas, visi &amp; misi, dan keunggulan &amp; kompetensi.</p>

<?php if ($error): ?>
<p class="mt-6 rounded-2xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-300"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<?php if ($success): ?>
<p class="mt-6 rounded-2xl border border-brand-secondary/30 bg-brand-secondary/10 p-4 text-sm text-emerald-200">Profil jurusan berhasil disimpan.</p>
<?php endif; ?>

<form method="post" class="mt-8 flex flex-col gap-8">
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl">
        <h2 class="font-display text-lg font-semibold text-white">Identitas &amp; Visi</h2>
        <div class="mt-4 flex flex-col gap-4">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-300">Identitas Jurusan</label>
                <textarea name="identitas" rows="4" required class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary"><?= htmlspecialchars($identitas) ?></textarea>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-300">Visi</label>
                <textarea name="visi" rows="3" required class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary"><?= htmlspecialchars($visi) ?></textarea>
            </div>
        </div>
    </div>

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl">
        <div class="flex items-center justify-between">
            <h2 class="font-display text-lg font-semibold text-white">Misi</h2>
            <button type="button" id="tambah-misi" class="rounded-full border border-white/15 px-4 py-1.5 text-xs font-semibold text-white hover:bg-white/10">+ Tambah Misi</button>
        </div>
        <div id="misi-list" class="mt-4 flex flex-col gap-3">
            <?php foreach ($misi as $m): ?>
            <div class="flex gap-2">
                <input type="text" name="misi[]" value="<?= htmlspecialchars($m) ?>" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary">
                <button type="button" class="hapus-baris shrink-0 rounded-xl border border-white/10 px-3 text-red-400 hover:bg-white/10">✕</button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl">
        <div class="flex items-center justify-between">
            <h2 class="font-display text-lg font-semibold text-white">Keunggulan &amp; Kompetensi</h2>
            <button type="button" id="tambah-keunggulan" class="rounded-full border border-white/15 px-4 py-1.5 text-xs font-semibold text-white hover:bg-white/10">+ Tambah Kartu</button>
        </div>
        <div id="keunggulan-list" class="mt-4 flex flex-col gap-4">
            <?php foreach ($keunggulan as $k): ?>
            <div class="flex flex-col gap-2 rounded-2xl border border-white/10 bg-white/[0.03] p-4 sm:flex-row sm:items-start">
                <div class="flex-1 space-y-2">
                    <input type="text" name="keunggulan_judul[]" value="<?= htmlspecialchars($k['judul'] ?? '') ?>" placeholder="Judul kompetensi" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary">
                    <textarea name="keunggulan_deskripsi[]" rows="2" placeholder="Deskripsi singkat" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary"><?= htmlspecialchars($k['deskripsi'] ?? '') ?></textarea>
                </div>
                <button type="button" class="hapus-baris shrink-0 rounded-xl border border-white/10 px-3 py-2 text-red-400 hover:bg-white/10">✕</button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div>
        <button type="submit" class="rounded-full bg-brand-primary px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-500">Simpan Profil</button>
    </div>
</form>

<script>
document.getElementById('tambah-misi').addEventListener('click', function () {
    const wrap = document.createElement('div');
    wrap.className = 'flex gap-2';
    wrap.innerHTML = '<input type="text" name="misi[]" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary">' +
        '<button type="button" class="hapus-baris shrink-0 rounded-xl border border-white/10 px-3 text-red-400 hover:bg-white/10">\u2715</button>';
    document.getElementById('misi-list').appendChild(wrap);
});

document.getElementById('tambah-keunggulan').addEventListener('click', function () {
    const wrap = document.createElement('div');
    wrap.className = 'flex flex-col gap-2 rounded-2xl border border-white/10 bg-white/[0.03] p-4 sm:flex-row sm:items-start';
    wrap.innerHTML = '<div class="flex-1 space-y-2">' +
        '<input type="text" name="keunggulan_judul[]" placeholder="Judul kompetensi" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary">' +
        '<textarea name="keunggulan_deskripsi[]" rows="2" placeholder="Deskripsi singkat" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary"></textarea>' +
        '</div><button type="button" class="hapus-baris shrink-0 rounded-xl border border-white/10 px-3 py-2 text-red-400 hover:bg-white/10">\u2715</button>';
    document.getElementById('keunggulan-list').appendChild(wrap);
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('hapus-baris')) {
        e.target.closest('div.flex').remove();
    }
});
</script>

</main>
<script src="../assets/js/main.js"></script>
</body>
</html>
