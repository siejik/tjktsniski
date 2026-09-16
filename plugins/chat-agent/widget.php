<?php
/**
 * plugins/chat-agent/widget.php
 * Widget chat sederhana (Fase 2 PRD): FAQ rule-based + tombol WA ke admin.
 * Di-include dari includes/footer.php HANYA kalau plugin 'chat-agent' aktif
 * (lihat kolom status_aktif di tabel `plugins`).
 *
 * Variabel yang dipakai (opsional, sudah ada fallback):
 *   $chat_wa_number = nomor WA tujuan, dari pengaturan_situs('kontak_whatsapp')
 */

$chat_wa_number = $chat_wa_number ?? '628123456789';

$faq = [
    'Apa itu jurusan TJKT?' => 'TJKT (Teknik Jaringan Komputer dan Telekomunikasi) adalah program keahlian SMK yang mempelajari instalasi, konfigurasi, dan pemeliharaan jaringan komputer serta perangkat telekomunikasi.',
    'Bagaimana alur PKL di TJKT?' => 'Siswa kelas XI/XII ditempatkan di mitra industri (lihat halaman Flyer PKL) selama beberapa bulan untuk praktik kerja langsung sesuai kompetensi jaringan.',
    'Kompetensi apa saja yang dipelajari?' => 'Routing & switching, instalasi fiber optic, keamanan jaringan dasar, administrasi server, dan dasar telekomunikasi nirkabel — detail lengkap ada di halaman Profil Jurusan.',
    'Bagaimana cara melihat kegiatan terbaru?' => 'Buka menu Kegiatan di navbar untuk melihat semua dokumentasi praktik, lomba, dan agenda jurusan terbaru.',
    'Bagaimana kalau pertanyaan saya belum terjawab?' => 'Silakan hubungi admin/guru langsung lewat tombol WhatsApp di bawah ini.',
];
?>
<div id="chatbot-root" class="fixed bottom-5 right-5 z-[60]">
    <button id="chatbot-toggle" aria-label="Buka chat bantuan" class="flex h-14 w-14 items-center justify-center rounded-full bg-brand-primary text-white shadow-lg shadow-blue-500/30 transition hover:bg-blue-500">
        <svg id="chatbot-icon-open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8-1.06 0-2.077-.163-3.02-.465L3 21l1.5-4.5C3.55 15.18 3 13.64 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z"/></svg>
        <svg id="chatbot-icon-close" class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
    </button>

    <div id="chatbot-panel" class="hidden absolute bottom-[72px] right-0 w-[320px] max-w-[88vw] rounded-3xl border border-white/10 bg-slate-950/95 p-5 text-slate-300 shadow-2xl backdrop-blur-xl">
        <div class="flex items-center gap-2 font-display text-sm font-bold text-white">
            <svg class="h-6 w-6" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="6" cy="24" r="3" fill="#17A398"/><circle cx="24" cy="6" r="3" fill="#3B82F6"/>
                <circle cx="24" cy="24" r="3" fill="#3B82F6"/><path d="M6 24L24 6M6 24L24 24" stroke="#8FA3BF" stroke-width="1.4"/>
            </svg>
            Tanya Jawab TJKT
        </div>
        <p class="mt-1 text-xs text-slate-500">Pilih pertanyaan populer di bawah.</p>

        <div id="chatbot-answer" class="mt-3 hidden rounded-2xl border border-white/10 bg-white/5 p-3 text-xs leading-relaxed text-slate-300"></div>

        <div class="mt-3 flex max-h-52 flex-col gap-2 overflow-y-auto">
            <?php foreach ($faq as $q => $a): ?>
            <button type="button" class="chatbot-faq-btn rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-left text-xs text-slate-300 hover:bg-white/10" data-jawaban="<?= htmlspecialchars($a) ?>"><?= htmlspecialchars($q) ?></button>
            <?php endforeach; ?>
        </div>

        <a href="https://wa.me/<?= htmlspecialchars($chat_wa_number) ?>" target="_blank" rel="noopener" class="mt-4 flex items-center justify-center gap-2 rounded-full bg-brand-secondary px-4 py-2.5 text-xs font-semibold text-white hover:opacity-90">
            Hubungi Admin/Guru via WhatsApp
        </a>
    </div>
</div>

<script>
(function () {
    var toggle = document.getElementById('chatbot-toggle');
    var panel  = document.getElementById('chatbot-panel');
    var iconOpen = document.getElementById('chatbot-icon-open');
    var iconClose = document.getElementById('chatbot-icon-close');
    var answerBox = document.getElementById('chatbot-answer');

    toggle.addEventListener('click', function () {
        var isHidden = panel.classList.contains('hidden');
        if (isHidden) {
            panel.classList.remove('hidden');
            iconOpen.classList.add('hidden');
            iconClose.classList.remove('hidden');
        } else {
            panel.classList.add('hidden');
            iconOpen.classList.remove('hidden');
            iconClose.classList.add('hidden');
        }
    });

    document.querySelectorAll('.chatbot-faq-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            answerBox.textContent = btn.dataset.jawaban;
            answerBox.classList.remove('hidden');
        });
    });
})();
</script>
