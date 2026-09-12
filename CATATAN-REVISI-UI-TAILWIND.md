# Catatan Revisi — Redesign UI dengan Tailwind CSS (CDN v4)

Revisi ini menjawab 3 permintaan dari user:
1. Hero beranda: background jadi foto slideshow bergeser.
2. Halaman Profil: tambahan section "Alumni & Prospek Lulusan".
3. Perbaikan bug tampilan mobile (overflow) + desain diperbarui total ke gaya modern 2026 (bento grid, glassmorphism, dark mode, Tailwind CDN v4).

## 1. File yang diubah
```
/index.html                     — ditulis ulang total (Tailwind)
/pages/profil.html              — ditulis ulang total + section Alumni baru
/pages/kegiatan.html            — ditulis ulang total (Tailwind)
/pages/kegiatan-detail.html     — ditulis ulang total (Tailwind)
/pages/galeri.html              — ditulis ulang total (Tailwind)
/pages/flyer-pkl.html           — ditulis ulang total (Tailwind) + fix bug overflow
/assets/js/main.js              — ditulis ulang: toggle menu mobile, tandai
                                   nav aktif, SLIDESHOW hero baru, filter
                                   galeri, lightbox — semua disesuaikan ke
                                   class Tailwind (bukan lagi class CSS custom lama)
/assets/css/custom.css          — file BARU, pelengkap kecil (scrollbar,
                                   selection color, wrapper video 16:9)
/assets/css/style.css           — DIHAPUS. Sudah tidak dipakai karena semua
                                   styling sekarang lewat utility class
                                   Tailwind langsung di HTML.
```
Struktur folder inti (`/pages`, `/admin`, `/includes`, `/assets`, `/plugins`, `/database`) **tidak diubah**, tetap sesuai PRD.

## 2. Root cause bug mobile di screenshot
Section "Detail Flyer" (dan section "Identitas" di halaman Profil) sebelumnya
pakai `grid-template-columns: 260px 1fr` (atau `140px 1fr`) tanpa breakpoint
sama sekali — di layar HP kolom 260px itu tetap dipaksakan sehingga total
lebar grid lebih besar dari layar, dan menyebabkan konten geser ke kanan/terpotong
seperti di screenshot yang dikirim. Sudah diperbaiki: sekarang default
`grid-cols-1` (tumpuk vertikal) di mobile, baru berubah jadi 2 kolom mulai
breakpoint `md:` (≥768px) lewat `md:grid-cols-[300px_1fr]` / `sm:grid-cols-[160px_1fr]`.

## 3. Stack & pendekatan desain baru
- **Tailwind CDN v4**: `<script src="https://unpkg.com/@tailwindcss/browser@4"></script>`
  di tiap halaman (bukan build step — tetap native, sesuai PRD no-framework/no-build).
- Kustomisasi warna & font pakai `@theme` langsung di `<style type="text/tailwindcss">`
  di setiap halaman (cara resmi Tailwind v4, gantinya `tailwind.config` versi lama):
  `--color-brand-primary`, `--color-brand-secondary`, `--color-brand-accent`,
  `--font-sans` (Inter), `--font-display` (Space Grotesk).
- **Dark mode by default** — seluruh halaman pakai palet gelap (`bg-slate-950`)
  sebagai basis, bukan toggle light/dark.
- **Bento grid** dipakai di section Profil, Alumni, Galeri (kartu besar-kecil
  tidak seragam, `md:col-span-2` dsb).
- **Glassmorphism**: kartu pakai `bg-white/5 backdrop-blur-xl border border-white/10`,
  hero card pakai `bg-white/5 backdrop-blur-xl` di atas foto slideshow.
- **Rounded 2xl/3xl** di semua kartu, tombol pakai `rounded-full`.
- Spacing dilebarkan: section `py-20 sm:py-28`, card padding `p-6`–`p-8`.

## 4. Hero slideshow (poin revisi #1)
- 4 foto (`.hero-slide`, absolute, `object-cover`) ditumpuk di background hero
  beranda, dikontrol `assets/js/main.js` — ganti class `opacity-0` ⟷ `opacity-100`
  tiap 5 detik dengan transisi crossfade `transition-opacity duration-[2000ms]`.
- Foto sekarang pakai URL Unsplash sebagai **placeholder** (tema server/lab/kelas/kabel).
  **Ganti ke foto asli kegiatan/lab jurusan** begitu tersedia — idealnya lewat
  dashboard admin (Fase 3/4) supaya guru bisa ganti tanpa edit kode.

## 5. Section Alumni baru (poin revisi #2)
Ditambahkan di `pages/profil.html` setelah "Keunggulan & Kompetensi":
**"Alumni & Prospek Lulusan"** — bento grid 5 kartu: Bekerja di Industri,
Studi Lanjut, Wirausaha, Sertifikasi & Peluang Global, Karier Terus Berkembang.
Copy saat ini masih **gambaran umum jalur karier TJKT** (bukan data alumni asli
sekolah) — sudah diberi keterangan kecil di bawah section. AI/admin berikutnya
sebaiknya mengganti dengan testimoni/data alumni nyata via dashboard (tabel
`profil_jurusan` bisa diperluas, atau tabel baru `alumni` kalau perlu).

## 6. Untuk AI/kolaborator berikutnya (Fase 2 & 3)
- Saat menyambungkan ke PHP/MySQL, pertahankan class Tailwind yang sudah ada —
  cukup ganti isi teks/gambar/loop `<article>` jadi hasil query, jangan hapus
  struktur `class="..."`.
- Widget chat agent (Fase 2) sebaiknya dibungkus jadi `<div id="chat-widget">`
  glass + `rounded-3xl`, konsisten dengan gaya ini, dan tetap bisa on/off dari
  dashboard sesuai PRD.
- Dashboard admin (Fase 3) sebaiknya melanjutkan tema visual yang sama
  (Tailwind CDN v4, dark, glass) supaya konsisten dengan halaman publik.
- `assets/css/custom.css` boleh ditambah isinya kalau ada kebutuhan CSS yang
  benar-benar tidak bisa lewat utility Tailwind, tapi usahakan tetap minim.
