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
