# Catatan Progres — AI ke-1 (Fase 1 selesai)

## 1. File yang sudah dibuat
```
/index.html
/pages/profil.html
/pages/kegiatan.html
/pages/kegiatan-detail.html
/pages/galeri.html
/pages/flyer-pkl.html
/assets/css/style.css
/assets/js/main.js
/assets/img/README.md            (konvensi nama file gambar asli)
/database/schema.sql
/admin/.gitkeep                  (folder disiapkan, belum diisi — tugas AI ke-2/3)
/includes/.gitkeep                (folder disiapkan, belum diisi — tugas AI ke-2)
/plugins/chat-agent/.gitkeep      (folder disiapkan, belum diisi — tugas AI ke-3)
```

## 2. Status Fase 1
Semua bagian Fase 1 di PRD **selesai**:
- Struktur folder lengkap ✅
- `database/schema.sql` ✅ (nama tabel persis PRD, beberapa kolom disempurnakan — lihat poin 3)
- Landing page (index.html) merangkum semua section ✅
- Profil Jurusan (identitas, visi-misi, keunggulan) ✅
- Info Kegiatan (list + 1 template detail) ✅
- Galeri (foto praktek, video kegiatan, foto fasilitas — filter tab + lightbox) ✅
- Flyer PKL (daftar tempat, detail flyer, mitra industri) ✅
- Navbar & footer konsisten di semua halaman, responsive mobile ✅

Data semua masih dummy/hardcode (sesuai arahan, karena koneksi DB tugas AI ke-2).

## 3. Keputusan desain
- **Tema:** "Jalur Sinyal" — motif titik (node) & garis penghubung untuk merepresentasikan topologi jaringan, dipakai di logo, hero, dan penanda section (`.node-mark`).
- **Warna:** biru teknologi `#2F6FED` (primer), teal sinyal `#17A398` (sekunder), amber `#F2994A` (aksen hangat/PKL), dasar gelap `#0B1626` untuk navbar/hero/footer, terang `#F5F8FC` untuk konten.
- **Font:** `Space Grotesk` (heading) + `Inter` (body), via Google Fonts CDN (`@import` di `style.css`).
- **Gambar:** placeholder via `placehold.co`. Video: embed iframe YouTube langsung.
- **Library luar:** tidak ada — navbar toggle, filter galeri, dan lightbox pakai vanilla JS murni (`assets/js/main.js`). Tidak ada CDN carousel/lightbox eksternal.
- **Skema DB:** kolom `key` di `pengaturan_situs` diganti `setting_key` (reserved word MySQL). Kategori `galeri` ditambah nilai `kegiatan` supaya video kegiatan punya kategori sendiri (sesuai fitur di PRD 4.3). Ditambahkan `slug` di tabel `kegiatan` untuk URL detail yang rapi.

## 4. Instruksi untuk AI ke-2
Lanjutkan dengan:
1. Sambungkan `pages/kegiatan.html`, `kegiatan-detail.html`, `galeri.html`, `flyer-pkl.html` ke MySQL sesuai `database/schema.sql` (ubah ke `.php` bila perlu ambil data dinamis).
2. Buat `includes/db.php` (koneksi database) dan `includes/auth.php` (cek session login).
3. Buat halaman login admin di `admin/login.php`.
4. Jangan buat ulang file yang sudah ada — lanjutkan/modifikasi saja.
5. Tutup pekerjaanmu dengan catatan progres yang sama formatnya untuk diteruskan ke AI ke-3.
