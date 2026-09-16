<footer class="border-t border-white/10 bg-slate-950">
    <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-2">
                <div class="mb-4 flex items-center gap-2 font-display text-lg font-bold text-white">
                    <svg class="h-8 w-8" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="6" cy="24" r="3" fill="#17A398"/>
                        <circle cx="24" cy="6" r="3" fill="#3B82F6"/>
                        <circle cx="24" cy="24" r="3" fill="#3B82F6"/>
                        <path d="M6 24L24 6M6 24L24 24" stroke="#8FA3BF" stroke-width="1.4"/>
                    </svg>
                    Jurusan TJKT
                </div>
                <p class="max-w-sm text-sm leading-relaxed text-slate-400">Teknik Jaringan Komputer dan Telekomunikasi — SMK Negeri. Mencetak teknisi jaringan siap kerja dan siap PKL di industri.</p>
            </div>
            <div>
                <h4 class="mb-4 font-display text-sm font-semibold text-white">Navigasi</h4>
                <ul class="space-y-3 text-sm text-slate-400">
                    <li><a href="<?= $base_url ?>pages/profil.php" class="hover:text-white">Profil Jurusan</a></li>
                    <li><a href="<?= $base_url ?>pages/kegiatan.php" class="hover:text-white">Kegiatan</a></li>
                    <li><a href="<?= $base_url ?>pages/galeri.php" class="hover:text-white">Galeri</a></li>
                    <li><a href="<?= $base_url ?>pages/flyer-pkl.php" class="hover:text-white">Flyer PKL</a></li>
                </ul>
            </div>
            <div>
                <h4 class="mb-4 font-display text-sm font-semibold text-white">Kontak</h4>
                <ul class="space-y-3 text-sm text-slate-400">
                    <li>tjkt@sekolah.sch.id</li>
                    <li>+62 812-3456-789</li>
                    <li>Jl. Pendidikan No. 1</li>
                </ul>
            </div>
        </div>
        <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-8 text-sm text-slate-500 sm:flex-row">
            <span>© 2026 Jurusan TJKT. Semua hak dilindungi.</span>
            <span>Dibangun oleh Tim Pengembang Jurusan</span>
        </div>
    </div>
</footer>

<?php
/**
 * Plugin chat-agent — modular, hanya tampil kalau status_aktif = 1 di
 * tabel `plugins`. require_once db.php di sini biar footer.php tetap aman
 * dipakai walau halaman pemanggilnya belum load db.php sendiri.
 */
require_once __DIR__ . '/db.php';
$pdo_footer = get_db();
if ($pdo_footer) {
    try {
        $chat_active = (bool) $pdo_footer->query("SELECT status_aktif FROM plugins WHERE nama_plugin = 'chat-agent'")->fetchColumn();
        if ($chat_active) {
            $chat_wa_number = trim((string) $pdo_footer->query("SELECT value FROM pengaturan_situs WHERE setting_key = 'kontak_whatsapp'")->fetchColumn()) ?: '628123456789';
            require __DIR__ . '/../plugins/chat-agent/widget.php';
        }
    } catch (PDOException $e) {
        // plugin/pengaturan_situs belum ada — diam saja, situs tetap jalan tanpa widget
    }
}
?>

<script src="<?= $base_url ?>assets/js/main.js"></script>
</body>
</html>
