# INSTRUKSI KERJA — AI KE-3 (dari 3 AI kolaborator, TAHAP TERAKHIR)
Kamu AI ketiga/terakhir. Baca dulu PRD + catatan progres AI-1 dan AI-2 (semua ada di file project yang diupload). Lanjutkan, JANGAN bangun ulang dari nol.

## STATUS PROJECT SAAT INI (sudah beres, jangan diulang)
- Halaman publik (index, profil, kegiatan, kegiatan-detail, galeri, flyer-pkl) sudah `.php`, sudah tersambung MySQL, ada fallback dummy kalau DB belum siap.
- `includes/db.php` (fungsi `get_db()`), `includes/auth.php` (fungsi `is_logged_in()`, `require_login()`, `current_admin()`, `login_session()`, `logout_session()`), `includes/header.php`, `includes/footer.php` — dipakai bareng semua halaman.
- `admin/setup.php` — bikin akun admin pertama (mengunci diri otomatis kalau tabel `admins` sudah ada isinya).
- `admin/login.php`, `admin/logout.php` — sudah jalan.
- `admin/dashboard.php` — baru STUB: proteksi login sudah aktif + statistik asli dari DB, tapi belum ada CRUD.
- `database/schema.sql` — tabel `admins`, `profil_jurusan`, `kegiatan`, `galeri`, `flyer_pkl`, `pengaturan_situs`, `plugins` sudah ada + seed data dummy.
- Desain: Tailwind CDN (`@tailwindcss/browser@4`), tema warna `--color-brand-primary` (#3B82F6), `--color-brand-secondary` (#17A398), `--color-brand-accent` (#F2994A). WAJIB dipakai konsisten, jangan ganti tema.

## TUGAS KAMU — Fase 3 (CRUD Dashboard) + Fase 4 (Plugin & Pengaturan)

### 1. Kelola Postingan Kegiatan — `admin/kelola-kegiatan.php`
- List semua kegiatan dari tabel `kegiatan` (tabel/list dengan tombol edit & hapus)
- Form tambah/edit kegiatan (judul, slug — auto-generate dari judul kalau kosong, deskripsi, tanggal, gambar/URL gambar)
- Hapus kegiatan (dengan konfirmasi)
- Wajib `require_login()` di paling atas file

### 2. Kelola Galeri & Flyer — `admin/kelola-galeri.php` + `admin/kelola-flyer.php`
- Galeri: CRUD untuk tabel `galeri` (tipe foto/video, kategori praktek/kegiatan/fasilitas, url, caption)
- Flyer: CRUD untuk tabel `flyer_pkl` (nama_tempat, deskripsi, gambar_flyer, kontak_mitra)

### 3. Kelola Profil Jurusan — `admin/kelola-profil.php`
- Form edit 1 baris di tabel `profil_jurusan`: identitas, visi, misi (disimpan JSON array — buat UI tambah/hapus baris misi secara dinamis), keunggulan (JSON array of {judul, deskripsi} — UI tambah/hapus card)

### 4. Kelola Akun & Reset Password — tambahkan ke `admin/dashboard.php` atau halaman baru `admin/kelola-akun.php`
- List admin, tambah admin baru, ganti password (pakai `password_hash`)

### 5. Pengaturan Umum Situs — `admin/pengaturan.php`
- Form edit key-value di tabel `pengaturan_situs` (nama sekolah, kontak email/WA, warna tema kalau mau dibuat dinamis)

### 6. Plugin Chat Agent — `/plugins/chat-agent/`
- Buat widget chat sederhana (floating button pojok kanan bawah + panel chat)
- Versi awal: rule-based FAQ (pertanyaan populer + jawaban statis dari array PHP), bukan AI dulu
- Tombol "Hubungi Admin/Guru" → link ke WhatsApp (`https://wa.me/<nomor dari pengaturan_situs>`)
- WAJIB modular: on/off dikontrol dari tabel `plugins` (kolom `status_aktif`) — include widget ini di `includes/footer.php` HANYA kalau statusnya aktif
- Tambahkan toggle plugin di dashboard admin (bagian sederhana di `admin/dashboard.php` atau halaman baru `admin/plugin.php`)

### ATURAN WAJIB
- Semua halaman admin baru: include `db.php` + `auth.php`, panggil `require_login()` di baris atas.
- Pakai `htmlspecialchars()` untuk semua output ke HTML (sudah jadi konvensi di file-file sebelumnya, ikuti pola yang sama).
- Pakai prepared statement PDO untuk semua query (jangan concat string SQL langsung) — cegah SQL injection.
- Desain konsisten dengan halaman admin yang sudah ada (login.php, dashboard.php) — border/glassmorphism style `rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl`.
- Sertakan sidebar/navigasi sederhana antar-halaman admin (Dashboard, Kegiatan, Galeri, Flyer, Profil, Akun, Pengaturan, Logout) — bisa taruh di `includes/admin-nav.php` biar tidak duplikat di 6+ halaman admin.

### DI AKHIR, WAJIB BUAT
`CATATAN-PROGRES-AI-3.md` berisi: daftar file baru, cara pakai (langkah setup dari nol sampai bisa CRUD), dan status akhir (fitur apa yang 100% jalan vs yang masih perlu disempurnakan manual oleh user, misal: upload file gambar asli — sejauh ini semua gambar masih pakai placeholder/URL manual, belum ada fitur upload file ke server).
