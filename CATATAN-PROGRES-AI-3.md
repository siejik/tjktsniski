# Catatan Progres — AI ke-3 (Fase 3: CRUD Dashboard + Fase 4: Plugin & Pengaturan)

Melanjutkan pekerjaan AI ke-1 (struktur & statis), revisi UI Tailwind, dan AI ke-2
(PHP + MySQL + login). Fase ini melengkapi seluruh CRUD admin + plugin chat-agent.
**Sudah diuji end-to-end** (MariaDB + PHP dev server lokal): setup admin, login,
proteksi dashboard, CRUD kegiatan/galeri/flyer/profil/akun, ganti pengaturan, dan
toggle plugin — semua jalan tanpa error.

## 1. File baru
```
includes/admin-nav.php       — header + navigasi tab bersama semua halaman admin (biar tidak duplikat)
admin/kelola-kegiatan.php    — CRUD kegiatan (slug auto-generate dari judul kalau dikosongkan)
admin/kelola-galeri.php      — CRUD galeri (foto/video, kategori praktek/kegiatan/fasilitas)
admin/kelola-flyer.php       — CRUD flyer_pkl
admin/kelola-profil.php      — edit profil_jurusan; misi & keunggulan pakai baris dinamis (tambah/hapus)
admin/kelola-akun.php        — list admin, tambah admin baru, ganti password
admin/pengaturan.php         — edit key-value pengaturan_situs (render otomatis dari isi tabel)
plugins/chat-agent/widget.php — widget chat FAQ rule-based + tombol WA ke admin
```

## 2. File yang diubah
```
admin/dashboard.php   — sebelumnya stub, sekarang pakai includes/admin-nav.php bersama,
                         tambah quick-link ke semua halaman kelola, dan toggle plugin.
includes/footer.php   — ditambah include kondisional plugins/chat-agent/widget.php,
                         HANYA muncul kalau plugins.status_aktif = 1 untuk 'chat-agent'.
```
Semua halaman admin lain (login.php, setup.php, logout.php) **tidak diubah**.

## 3. Cara pakai dari nol sampai bisa CRUD
1. Import `database/schema.sql` ke MySQL (`mysql -u root -p nama_db < database/schema.sql`).
2. Edit 4 konstanta di `includes/db.php` (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`) sesuai hosting.
3. Buka `admin/setup.php` di browser → buat akun admin pertama (username + password ≥8 karakter).
4. Login di `admin/login.php`.
5. Di dashboard, klik menu tab (Kegiatan/Galeri/Flyer PKL/Profil Jurusan/Akun/Pengaturan) untuk kelola konten.
6. Untuk aktifkan chat widget: di Dashboard, bagian "Plugin", klik **Aktifkan** pada `chat-agent`. Widget langsung muncul di semua halaman publik.
7. **Hapus atau pindahkan `admin/setup.php`** dari server produksi setelah admin pertama dibuat (mencegah orang lain membuat akun via halaman itu — meski otomatis terkunci begitu tabel `admins` terisi, lebih aman dihapus).

## 4. Status akhir — 100% jalan
- Login, session, proteksi semua halaman admin (`require_login()`).
- CRUD penuh: kegiatan (create/update/delete + slug otomatis), galeri, flyer PKL.
- Edit profil jurusan dengan UI dinamis untuk misi (list) & keunggulan (kartu judul+deskripsi), disimpan sebagai JSON.
- Kelola akun: tambah admin baru, ganti password (hash pakai `password_hash`).
- Pengaturan situs: edit key-value (nama sekolah, kontak email/WA, warna tema — nilai tersimpan, lihat poin 5).
- Plugin chat-agent: toggle on/off dari dashboard, widget FAQ rule-based + tombol WA muncul/hilang sesuai status, nomor WA diambil otomatis dari `pengaturan_situs.kontak_whatsapp`.
- Semua query pakai prepared statement PDO, semua output pakai `htmlspecialchars()`.

## 5. Yang masih perlu disempurnakan manual oleh user
- **Upload gambar asli belum ada.** Semua kolom gambar (`kegiatan.gambar`, `galeri.url` tipe foto, `flyer_pkl.gambar_flyer`) masih diisi manual lewat URL/placeholder di form admin — belum ada fitur upload file ke server. Kalau mau ditambahkan: butuh folder upload (mis. `assets/img/uploads/`), validasi tipe file MIME, dan `move_uploaded_file()` di masing-masing `kelola-*.php`.
- **Warna tema di `pengaturan.php` belum otomatis mengubah tampilan** — nilainya tersimpan di database tapi tema visual situs masih hardcode di `@theme` Tailwind (`includes/admin-nav.php`, `includes/header.php`, dll). Untuk membuatnya benar-benar dinamis, warna itu perlu di-inject sebagai CSS variable inline dari PHP saat render halaman.
- **Chat agent masih rule-based statis** (array FAQ hardcode di `widget.php`) — sesuai instruksi Fase 2 ("AI-based kalau sempat"). Upgrade ke AI beneran (mis. panggil API eksternal) adalah pengembangan lanjutan, bukan scope Fase 3/4 ini.
- **Kelola Akun** belum ada fitur hapus admin (sengaja tidak dibuat — instruksi hanya minta list, tambah, ganti password). Kalau perlu hapus admin, tinggal tambah 1 form serupa `hapus_id` seperti pola di `kelola-kegiatan.php`.
