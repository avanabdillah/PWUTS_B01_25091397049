CREATE DATABASE IF NOT EXISTS jadwal_perkuliahan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE jadwal_perkuliahan;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS reminder;
DROP TABLE IF EXISTS status_tugas;
DROP TABLE IF EXISTS tugas;
DROP TABLE IF EXISTS jadwal;
DROP TABLE IF EXISTS mata_kuliah;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('mahasiswa','pj') NOT NULL DEFAULT 'mahasiswa',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE mata_kuliah (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode_mk VARCHAR(20) NOT NULL UNIQUE,
  nama_mk VARCHAR(120) NOT NULL,
  sks TINYINT UNSIGNED NOT NULL DEFAULT 2,
  dosen VARCHAR(120) NOT NULL,
  id_pj INT UNSIGNED NULL,
  CONSTRAINT fk_mk_pj FOREIGN KEY (id_pj) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE jadwal (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  mata_kuliah_id INT UNSIGNED NOT NULL,
  hari ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
  jam_mulai TIME NOT NULL,
  jam_selesai TIME NOT NULL,
  ruangan VARCHAR(80) NOT NULL,
  kelas VARCHAR(40) NOT NULL DEFAULT '2025B',
  catatan_perubahan VARCHAR(255) NULL,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_jadwal_mk FOREIGN KEY (mata_kuliah_id) REFERENCES mata_kuliah(id) ON DELETE CASCADE,
  INDEX idx_jadwal_hari_waktu (hari,jam_mulai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE tugas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  mata_kuliah_id INT UNSIGNED NOT NULL,
  judul VARCHAR(160) NOT NULL,
  deskripsi TEXT NULL,
  deadline DATETIME NOT NULL,
  link_pengumpulan VARCHAR(500) NULL,
  dibuat_oleh INT UNSIGNED NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_tugas_mk FOREIGN KEY (mata_kuliah_id) REFERENCES mata_kuliah(id) ON DELETE CASCADE,
  CONSTRAINT fk_tugas_pj FOREIGN KEY (dibuat_oleh) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_tugas_deadline (deadline)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE status_tugas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  tugas_id INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  status ENUM('Belum dikerjakan','Sedang dikerjakan','Selesai') NOT NULL DEFAULT 'Belum dikerjakan',
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_status_tugas FOREIGN KEY (tugas_id) REFERENCES tugas(id) ON DELETE CASCADE,
  CONSTRAINT fk_status_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE KEY uq_status_user_tugas (tugas_id,user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reminder (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  tugas_id INT UNSIGNED NOT NULL,
  waktu_reminder DATETIME NOT NULL,
  status ENUM('Aktif','Terkirim','Selesai') NOT NULL DEFAULT 'Aktif',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reminder_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_reminder_tugas FOREIGN KEY (tugas_id) REFERENCES tugas(id) ON DELETE CASCADE,
  INDEX idx_reminder_waktu (waktu_reminder)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Demo accounts use password "password" (change after first login).
INSERT INTO users (nama,email,password,role) VALUES
('Mahasiswa Demo','mahasiswa@classplan.local','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.','mahasiswa'),
('PJ Demo','pj@classplan.local','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.','pj');

INSERT INTO mata_kuliah (kode_mk,nama_mk,sks,dosen,id_pj) VALUES
('MI201','Pemrograman Web',2,'Ari Kurniawan, S.Kom., M.T.',2),
('MI202','Basis Data',3,'Dwi Susanto, S.Kom., M.Kom.',2),
('MI203','Analisis dan Perancangan Sistem',3,'Budi Hartono, S.Kom., M.Kom.',2),
('MI204','Pemrograman Berorientasi Objek',3,'Siti Rahma, S.Kom., M.Kom.',2),
('MI205','Interaksi Manusia dan Komputer',2,'Nadia Putri, S.Ds., M.Ds.',2);

INSERT INTO jadwal (mata_kuliah_id,hari,jam_mulai,jam_selesai,ruangan,kelas) VALUES
(1,'Senin','08:00:00','09:40:00','Lab Komputer A-203','2025B'),
(2,'Senin','10:00:00','11:40:00','Ruang B-105','2025B'),
(3,'Selasa','08:00:00','09:40:00','Ruang A-102','2025B'),
(4,'Selasa','10:00:00','11:40:00','Lab Komputer A-201','2025B'),
(1,'Rabu','08:00:00','09:40:00','Lab Komputer A-203','2025B'),
(2,'Rabu','10:00:00','11:40:00','Ruang B-105','2025B'),
(5,'Kamis','09:00:00','10:40:00','Ruang D-302','2025B');

INSERT INTO tugas (mata_kuliah_id,judul,deskripsi,deadline,link_pengumpulan,dibuat_oleh) VALUES
(1,'Membuat Homepage Responsif','Buat halaman utama responsif menggunakan HTML, CSS, dan JavaScript.','2026-11-05 23:59:00','https://drive.google.com/',2),
(2,'Latihan Query SQL','Kerjakan latihan SELECT, JOIN, dan agregasi.','2026-11-08 23:59:00','https://classroom.google.com/',2),
(3,'Dokumentasi Analisis Sistem','Lengkapi dokumentasi kebutuhan dan diagram sistem.','2026-11-12 23:59:00',NULL,2);

INSERT INTO status_tugas (tugas_id,user_id,status) VALUES
(1,1,'Sedang dikerjakan'),(2,1,'Belum dikerjakan'),(3,1,'Belum dikerjakan');

INSERT INTO reminder (user_id,tugas_id,waktu_reminder,status) VALUES
(1,1,'2026-11-04 09:00:00','Aktif'),
(1,2,'2026-11-07 09:00:00','Aktif');
