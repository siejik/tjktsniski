-- =====================================================================
-- schema.sql — Website Jurusan TJKT
-- Dibuat oleh: AI ke-1 (Fase 1 — struktur & halaman publik)
-- Catatan: nama tabel PERSIS sesuai PRD. Beberapa kolom disempurnakan
-- (lihat catatan di setiap tabel) tapi tidak mengubah nama tabel inti.
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- admins — akun pengelola (dipakai AI ke-2 untuk login, AI ke-3 untuk role)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS admins (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(50)  NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role          ENUM('super_admin', 'admin') NOT NULL DEFAULT 'admin',
    created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- profil_jurusan — biasanya cuma 1 baris (single-row config table)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS profil_jurusan (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    identitas   TEXT         NOT NULL,   -- nama jurusan, deskripsi singkat, path logo
    visi        TEXT         NOT NULL,
    misi        TEXT         NOT NULL,
    keunggulan  TEXT         NOT NULL,   -- disimpan sbg JSON array string, dipecah jadi kartu di frontend
    updated_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- kegiatan — list & detail kegiatan
-- Ditambah: slug (untuk URL detail yang rapi) & created_at
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS kegiatan (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    judul       VARCHAR(150) NOT NULL,
    slug        VARCHAR(170) NOT NULL UNIQUE,
    deskripsi   TEXT         NOT NULL,
    tanggal     DATE         NOT NULL,
    gambar      VARCHAR(255) DEFAULT NULL,
    created_by  INT UNSIGNED DEFAULT NULL,
    created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_kegiatan_admin FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- galeri — foto praktek, video kegiatan, foto fasilitas & lab
-- kategori ditambah 'kegiatan' supaya video kegiatan punya tempat sendiri
-- (sesuai PRD 4.3: Album Foto Praktek / Video Kegiatan / Foto Fasilitas & Lab)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS galeri (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tipe        ENUM('foto', 'video') NOT NULL,
    kategori    ENUM('praktek', 'kegiatan', 'fasilitas') NOT NULL,
    url         VARCHAR(255) NOT NULL,   -- path file lokal (foto) atau URL embed YouTube (video)
    caption     VARCHAR(255) DEFAULT NULL,
    created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- flyer_pkl — daftar tempat PKL + flyer + kontak mitra
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS flyer_pkl (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_tempat   VARCHAR(150) NOT NULL,
    deskripsi     TEXT         NOT NULL,
    gambar_flyer  VARCHAR(255) DEFAULT NULL,
    kontak_mitra  VARCHAR(150) DEFAULT NULL,  -- no WA / email / nama PIC
    created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- pengaturan_situs — key-value config (tema, kontak sekolah, sosial media)
-- Catatan: kolom `key` adalah reserved word di MySQL, diganti `setting_key`
-- supaya query tidak perlu backtick terus-menerus. Nilai/tujuan sama persis.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS pengaturan_situs (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key  VARCHAR(100) NOT NULL UNIQUE,
    value        TEXT         DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- plugins — kontrol on/off fitur modular (mis. chat-agent)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS plugins (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_plugin   VARCHAR(100) NOT NULL UNIQUE,
    status_aktif  TINYINT(1)   NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
-- Seed data secukupnya (boleh dihapus/disesuaikan AI ke-2/3)
-- =====================================================================
INSERT INTO plugins (nama_plugin, status_aktif) VALUES
    ('chat-agent', 0)
ON DUPLICATE KEY UPDATE nama_plugin = nama_plugin;

INSERT INTO pengaturan_situs (setting_key, value) VALUES
    ('nama_sekolah', 'SMK Negeri - Jurusan TJKT'),
    ('warna_primer', '#2F6FED'),
    ('warna_sekunder', '#17A398'),
    ('kontak_email', 'tjkt@sekolah.sch.id'),
    ('kontak_whatsapp', '628123456789')
ON DUPLICATE KEY UPDATE value = VALUES(value);

-- =====================================================================
-- Ditambahkan oleh: AI ke-2 (Fase — sambungkan ke PHP + MySQL)
-- Catatan: TIDAK ada seed akun admin di sini dengan sengaja — jangan
-- taruh password/hash default di file yang ikut ter-commit/ter-upload.
-- Buat admin pertama lewat admin/setup.php (form 1x pakai, otomatis
-- terkunci sendiri begitu tabel `admins` sudah ada isinya).
-- =====================================================================

INSERT INTO profil_jurusan (identitas, visi, misi, keunggulan) VALUES (
    'Teknik Jaringan Komputer dan Telekomunikasi (TJKT) adalah program keahlian yang mempelajari perancangan, instalasi, konfigurasi, dan pemeliharaan sistem jaringan komputer serta perangkat telekomunikasi. Siswa dibekali kompetensi praktik langsung di laboratorium jaringan dan kesempatan magang (PKL) di industri mitra.',
    'Menjadi program keahlian unggulan yang menghasilkan lulusan kompeten, adaptif terhadap teknologi jaringan terkini, dan siap bersaing di dunia industri maupun dunia kerja.',
    '["Menyelenggarakan pembelajaran berbasis praktik industri.","Membangun kerja sama aktif dengan mitra industri jaringan & telekomunikasi.","Membekali siswa dengan sertifikasi kompetensi yang relevan.","Menumbuhkan sikap disiplin, mandiri, dan etos kerja profesional."]',
    '[{"judul":"Routing & Switching","deskripsi":"Konfigurasi perangkat Cisco/Mikrotik, VLAN, dan manajemen jaringan skala kecil-menengah."},{"judul":"Instalasi Fiber Optic","deskripsi":"Praktik penyambungan & pengukuran kabel fiber optic sesuai standar industri."},{"judul":"Keamanan Jaringan Dasar","deskripsi":"Konsep firewall, access control, dan praktik pengamanan jaringan sederhana."},{"judul":"Administrasi Server","deskripsi":"Instalasi & konfigurasi server dasar untuk kebutuhan jaringan lokal."},{"judul":"Wireless & Telekomunikasi","deskripsi":"Dasar sistem komunikasi data nirkabel dan perangkat telekomunikasi."},{"judul":"Praktik Kerja Industri","deskripsi":"Penempatan PKL di mitra industri jaringan & telekomunikasi terpercaya."}]'
);

INSERT INTO kegiatan (judul, slug, deskripsi, tanggal, gambar) VALUES
    ('Praktik Konfigurasi Router & Switch', 'praktik-konfigurasi-router-switch', 'Siswa kelas XI TJKT melakukan praktik langsung konfigurasi perangkat router dan switch di laboratorium jaringan. Praktik difokuskan pada pemahaman routing statis, pembagian VLAN antar-departemen simulasi, serta pengujian konektivitas antar-segmen jaringan.\n\nKegiatan ini merupakan bagian dari rangkaian pembelajaran praktik berbasis proyek yang dirancang agar siswa terbiasa dengan skenario nyata di lapangan kerja, termasuk troubleshooting dasar ketika konfigurasi tidak berjalan sesuai rencana.', '2026-08-12', 'https://placehold.co/1200x520/0f172a/8FA3BF?text=Praktik+Konfigurasi+Jaringan'),
    ('LKS Bidang IT Network Systems Administration', 'lks-it-network-systems-administration', 'Perwakilan TJKT meraih juara 2 tingkat kota pada ajang LKS tahun ini, berkompetisi di bidang IT Network Systems Administration.', '2026-09-02', 'https://placehold.co/1200x520/0f172a/8FA3BF?text=LKS+2026'),
    ('Kunjungan ke Data Center Mitra Industri', 'kunjungan-data-center-mitra-industri', 'Siswa mengunjungi fasilitas data center salah satu mitra PKL untuk melihat operasional langsung, mulai dari rak server hingga sistem monitoring jaringan.', '2026-09-20', 'https://placehold.co/1200x520/0f172a/8FA3BF?text=Kunjungan+Data+Center'),
    ('Uji Sertifikasi Kompetensi Keahlian', 'uji-sertifikasi-kompetensi-keahlian', 'Siswa kelas XII mengikuti uji kompetensi sebagai syarat kelulusan jurusan, menguji kemampuan instalasi dan konfigurasi jaringan secara menyeluruh.', '2026-10-05', 'https://placehold.co/1200x520/0f172a/8FA3BF?text=Uji+Sertifikasi'),
    ('Workshop Fiber Optic bersama Praktisi Industri', 'workshop-fiber-optic-praktisi-industri', 'Menghadirkan praktisi dari mitra industri untuk berbagi praktik penyambungan dan pengukuran fiber optic langsung kepada siswa.', '2026-10-18', 'https://placehold.co/1200x520/0f172a/8FA3BF?text=Workshop+Fiber+Optic'),
    ('Study Tour Politeknik & Kampus Vokasi', 'study-tour-politeknik-kampus-vokasi', 'Agenda kunjungan kampus vokasi untuk memperkenalkan jenjang studi lanjutan bagi siswa yang berminat melanjutkan pendidikan.', '2026-11-15', 'https://placehold.co/1200x520/0f172a/8FA3BF?text=Study+Tour')
ON DUPLICATE KEY UPDATE judul = VALUES(judul);

INSERT INTO galeri (tipe, kategori, url, caption) VALUES
    ('foto', 'praktek', 'https://placehold.co/1000x750/132238/2F6FED?text=Praktik+1', 'Praktik crimping kabel UTP'),
    ('foto', 'praktek', 'https://placehold.co/1000x750/132238/2F6FED?text=Praktik+2', 'Konfigurasi switch di lab'),
    ('foto', 'praktek', 'https://placehold.co/1000x750/132238/2F6FED?text=Praktik+3', 'Penyambungan fiber optic'),
    ('video', 'kegiatan', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'Dokumentasi kegiatan jurusan 1'),
    ('video', 'kegiatan', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'Dokumentasi kegiatan jurusan 2'),
    ('foto', 'fasilitas', 'https://placehold.co/1000x750/132238/17A398?text=Lab+1', 'Laboratorium Jaringan Utama'),
    ('foto', 'fasilitas', 'https://placehold.co/1000x750/132238/17A398?text=Lab+2', 'Rak server praktik siswa'),
    ('foto', 'fasilitas', 'https://placehold.co/1000x750/132238/17A398?text=Lab+3', 'Ruang praktik fiber optic');

INSERT INTO flyer_pkl (nama_tempat, deskripsi, gambar_flyer, kontak_mitra) VALUES
    ('PT Jaringan Nusantara', 'PT Jaringan Nusantara membuka kesempatan PKL bagi siswa TJKT untuk terlibat langsung dalam instalasi jaringan pelanggan, pemasangan perangkat access point, hingga penanganan gangguan koneksi di lapangan. Kuota 4 siswa/semester, durasi 3 bulan.', 'https://placehold.co/520x680/132238/EAF1FB?text=Flyer+PKL', 'Bpk. Andi — 0812-1111-2222'),
    ('CV Telko Data Prima', 'Praktik operasional server & monitoring jaringan data center skala kecil-menengah.', 'https://placehold.co/520x680/132238/EAF1FB?text=Flyer+2', '0812-3333-4444'),
    ('PT Sinyal Komunika', 'Praktik instalasi perangkat BTS & pengukuran fiber optic di lapangan.', 'https://placehold.co/520x680/132238/EAF1FB?text=Flyer+3', '0812-5555-6666'),
    ('PT Network Solusi Indonesia', 'Praktik implementasi jaringan perusahaan skala menengah, termasuk keamanan jaringan dasar.', 'https://placehold.co/520x680/132238/EAF1FB?text=Flyer+4', '0812-7777-8888');
