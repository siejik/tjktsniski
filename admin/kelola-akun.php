<?php
/**
 * admin/kelola-akun.php
 * List admin, tambah admin baru, dan ganti password (pakai <details> native,
 * tanpa JS tambahan, biar sederhana).
 */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();
$admin = current_admin();
$pdo   = get_db();
$error = $pdo ? '' : 'Tidak bisa konek ke database. Cek konfigurasi di includes/db.php.';
$success = '';

// ---- Tambah admin baru ----
if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_admin'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? 'admin';

    if ($username === '' || strlen($password) < 8) {
        $error = 'Username wajib diisi dan password minimal 8 karakter.';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO admins (username, password_hash, role) VALUES (?, ?, ?)');
            $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $role]);
            header('Location: kelola-akun.php?added=1');
            exit;
        } catch (PDOException $e) {
            $error = 'Username sudah dipakai.';
        }
    }
}

// ---- Ganti password ----
if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ganti_password'])) {
    $target_id = (int) $_POST['admin_id'];
    $password  = $_POST['password_baru'] ?? '';

    if (strlen($password) < 8) {
        $error = 'Password baru minimal 8 karakter.';
    } else {
        $stmt = $pdo->prepare('UPDATE admins SET password_hash = ? WHERE id = ?');
        $stmt->execute([password_hash($password, PASSWORD_DEFAULT), $target_id]);
        header('Location: kelola-akun.php?pw_changed=1');
        exit;
    }
}

$daftar = [];
if ($pdo) {
    try {
        $daftar = $pdo->query('SELECT id, username, role, created_at FROM admins ORDER BY created_at ASC')->fetchAll();
    } catch (PDOException $e) {
        $error = $error ?: 'Tabel `admins` belum ada. Import database/schema.sql.';
    }
}

$page_title   = 'Kelola Akun — TJKT Admin';
$active_admin = 'akun';
require __DIR__ . '/../includes/admin-nav.php';
?>

<h1 class="font-display text-2xl font-bold tracking-tight text-white sm:text-3xl">Kelola Akun</h1>
<p class="mt-2 text-slate-400">Daftar admin, tambah admin baru, dan ganti password.</p>

<?php if ($error): ?>
<p class="mt-6 rounded-2xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-300"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<?php if (isset($_GET['added'])): ?>
<p class="mt-6 rounded-2xl border border-brand-secondary/30 bg-brand-secondary/10 p-4 text-sm text-emerald-200">Admin baru berhasil ditambahkan.</p>
<?php endif; ?>
<?php if (isset($_GET['pw_changed'])): ?>
<p class="mt-6 rounded-2xl border border-brand-secondary/30 bg-brand-secondary/10 p-4 text-sm text-emerald-200">Password berhasil diganti.</p>
<?php endif; ?>

<div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-5">
    <div class="lg:col-span-2">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl">
            <h2 class="font-display text-lg font-semibold text-white">Tambah Admin Baru</h2>
            <form method="post" class="mt-4 flex flex-col gap-4">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-300">Username</label>
                    <input type="text" name="username" required class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-300">Password</label>
                    <input type="password" name="password" required minlength="8" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-300">Peran</label>
                    <select name="role" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-white outline-none focus:border-brand-primary">
                        <option value="admin">Admin</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
                <button type="submit" name="tambah_admin" value="1" class="rounded-full bg-brand-primary px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-500">Tambah Admin</button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-3">
        <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-white/10 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Username</th>
                        <th class="px-5 py-3">Peran</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($daftar as $a): ?>
                    <tr>
                        <td class="px-5 py-4 text-white"><?= htmlspecialchars($a['username']) ?><?= $a['id'] == $admin['id'] ? ' <span class="text-xs text-slate-500">(kamu)</span>' : '' ?></td>
                        <td class="px-5 py-4 text-slate-400"><?= htmlspecialchars($a['role']) ?></td>
                        <td class="px-5 py-4 text-right">
                            <details class="inline-block text-left">
                                <summary class="cursor-pointer text-brand-primary hover:text-blue-400">Ganti Password</summary>
                                <form method="post" class="mt-3 flex items-center gap-2">
                                    <input type="hidden" name="admin_id" value="<?= (int) $a['id'] ?>">
                                    <input type="password" name="password_baru" required minlength="8" placeholder="Password baru" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white outline-none focus:border-brand-primary">
                                    <button type="submit" name="ganti_password" value="1" class="shrink-0 rounded-full bg-brand-primary px-4 py-2 text-xs font-semibold text-white hover:bg-blue-500">Simpan</button>
                                </form>
                            </details>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</main>
<script src="../assets/js/main.js"></script>
</body>
</html>
