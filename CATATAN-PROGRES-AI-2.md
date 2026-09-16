# Catatan Progres — AI ke-2 (Fase: Sambungkan PHP + MySQL + Login Admin)

Melanjutkan pekerjaan AI ke-1 (struktur & halaman publik statis) dan revisi UI
Tailwind sebelumnya. Fase ini fokus ke: data dinamis dari MySQL + sistem login admin.

## 1. File baru
```
/includes/db.php            — koneksi PDO ke MySQL (edit 4 konstanta di sini)
/includes/auth.php          — helper session login (is_logged_in, require_login, dst)
/includes/header.php        — <head> + navbar, dipakai bareng semua halaman publik
/includes/footer.php        — footer + <script main.js>, dipakai bareng semua halaman publik
/admin/setup.php            — bikin akun admin PERTAMA (sekali pakai, lihat poin 4)
/admin/login.php            — form login admin
/admin/logout.php           — hapus sesi & redirect ke login
/admin/dashboard.php        — dashboard STUB (sudah dilindungi login, tampil statistik asli
                               dari DB, tapi CRUD-nya belum — itu tugas Fase 3 / AI ke-3)
```

## 2. File yang diubah dari `.html` jadi `.php`
```
/index.html              -> /index.php
/pages/profil.html       -> /pages/profil.php
/pages/kegiatan.html     -> /pages/kegiatan.php
/pages/kegiatan-detail.html -> /pages/kegiatan-detail.php
/pages/galeri.html       -> /pages/galeri.php
/pages/flyer-pkl.html    -> /pages/flyer-pkl.php
```
Semua halaman ini sekarang mengambil data dari MySQL (tabel `kegiatan`, `galeri`,
`flyer_pkl`, `profil_jurusan`) lewat `includes/db.php`, **dengan fallback ke data
dummy** kalau database belum di-setup / query gagal — jadi situs tetap tampil
normal walau DB belum di-import (sesuai PRD bagian 8: "boleh dummy dulu kalau
database belum sempat").

Markup/desain Tailwind dari revisi sebelumnya **tidak diubah** — hanya konten
statis diganti jadi `<?php foreach (...) ?>` dari hasil query. Navbar & footer
sekarang jadi satu file (`includes/header.php` / `includes/footer.php`) supaya
tidak duplikat di 6 halaman — perubahan struktural ini disebutkan di sini sesuai
PRD bagian 7.

## 3. Database
`database/schema.sql` (bikinan AI ke-1) dipakai apa adanya + ditambah seed data
untuk `profil_jurusan`, `kegiatan` (6 baris, sudah ada `slug` untuk URL detail),
`galeri` (8 baris, foto+video), `flyer_pkl` (4 baris) — datanya sama persis
dengan dummy yang sebelumnya hardcode di HTML, supaya tampilan tidak berubah
begitu database di-import.

**Cara pakai:**
1. Buat database MySQL (mis. `tjkt_db`), import `database/schema.sql`.
2. Edit `includes/db.php` — isi `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`
   sesuai hosting.
3. Buka `admin/setup.php` di browser untuk bikin akun admin pertama.
4. Login di `admin/login.php`.

## 4. Kenapa tidak ada akun admin default di schema.sql
Sengaja **tidak** menaruh username/password (atau hash-nya) langsung di
`schema.sql`, supaya tidak ada kredensial default yang ikut ter-upload ke
server produksi. Sebagai gantinya dibuat `admin/setup.php`: form 1x pakai yang
otomatis mengunci diri begitu tabel `admins` sudah berisi ≥1 baris. Setelah
akun pertama dibuat, sebaiknya **hapus file `admin/setup.php`** dari server
(atau pindahkan keluar folder publik).

## 5. Yang BELUM dikerjakan (lanjutan untuk AI ke-3 / Fase 3-4 di PRD)
- CRUD lengkap di dashboard: `kelola-kegiatan.php`, `kelola-galeri.php`,
  `kelola-flyer.php`, `kelola-profil.php`, `pengaturan.php` — `admin/dashboard.php`
  saat ini baru stub (statistik asli dari DB + daftar menu yang akan datang).
- Kelola Akun & Peran (multi-admin) dan Reset Password — tabel `admins` sudah
  siap (kolom `role`), tinggal dibuatkan halamannya.
- Plugin chat agent (Fase 2 di PRD) — tabel `plugins` sudah ada kolom
  `status_aktif`, tinggal dibuatkan widget-nya di `/plugins/chat-agent/`.
- Section "Alumni & Prospek Lulusan" di `pages/profil.php` masih hardcode
  (bukan dari DB) karena sifatnya konten "marketing" yang jarang berubah —
  kalau mau dibuat editable dari dashboard, tambahkan kolom
  `alumni_prospek` (JSON) di tabel `profil_jurusan` lalu render seperti pola
  `keunggulan` yang sudah ada.
- Upload gambar asli (lihat `assets/img/README.md`) — saat ini kolom
  `gambar` / `url` / `gambar_flyer` masih diisi placeholder `placehold.co`.
