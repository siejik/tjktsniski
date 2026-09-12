# Konvensi Nama File Gambar

Semua gambar di halaman publik saat ini masih pakai placeholder dari `placehold.co`.
Saat gambar asli tersedia, simpan di folder ini dengan pola nama berikut lalu ganti
`src` di HTML (atau, setelah AI ke-2 menyambungkan database, isi kolom `gambar` /
`url` pada tabel terkait dengan path file ini):

- `logo-jurusan.png` — logo Jurusan TJKT (dipakai di `pages/profil.html`)
- `kegiatan-{id}.jpg` — gambar kegiatan, mis. `kegiatan-1.jpg` (tabel `kegiatan`, kolom `gambar`)
- `galeri-praktek-{n}.jpg` — foto praktek (tabel `galeri`, kategori `praktek`)
- `galeri-fasilitas-{n}.jpg` — foto lab/fasilitas (tabel `galeri`, kategori `fasilitas`)
- `flyer-{nama-tempat}.jpg` — flyer PKL, mis. `flyer-jaringan-nusantara.jpg` (tabel `flyer_pkl`, kolom `gambar_flyer`)
- `mitra-{nama-perusahaan}.png` — logo mitra industri (latar transparan disarankan)

Video kegiatan tidak disimpan di sini — pakai URL embed YouTube langsung di kolom `url` tabel `galeri` (tipe `video`).
