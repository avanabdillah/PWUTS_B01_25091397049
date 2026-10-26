-- ClassPlan: sinkronisasi jadwal sesuai screenshot KRS (24 SKS)
-- Backup database sebelum menjalankan. Jalankan file ini pada database jadwal_perkuliahan.
-- Script ini tidak menghapus tabel users/tugas/status_tugas/reminder.
USE jadwal_perkuliahan;
START TRANSACTION;

-- Perbarui mata kuliah yang sudah ada agar relasi tugas lama tetap terjaga.
UPDATE mata_kuliah SET nama_mk='Pemrograman Web', sks=2, dosen='Belum dikonfirmasi' WHERE kode_mk='MI201';
UPDATE mata_kuliah SET nama_mk='Basis Data Lanjut', sks=2, dosen='Asmunin, S.Kom., M.Kom.' WHERE kode_mk='MI202';
UPDATE mata_kuliah SET nama_mk='Analisis dan Desain Perangkat Lunak', sks=2, dosen='M. Adamu Islam Mashuri, S.Tr.T., M.Tr.Kom.' WHERE kode_mk='MI203';
UPDATE mata_kuliah SET nama_mk='Pemrograman Berorientasi Objek', sks=2, dosen='Faris Abdi El Hakim, S.Kom., M.Tr.Kom.' WHERE kode_mk='MI204';
UPDATE mata_kuliah SET nama_mk='Pemrograman Game', sks=2, dosen='Moch. Badrus Sholeh, S.Kom., M.Kom.' WHERE kode_mk='MI205';

INSERT INTO mata_kuliah (kode_mk,nama_mk,sks,dosen)
VALUES
('KRS006','Kecerdasan Buatan',3,'Salamun Rohman Nudin, S.Kom., M.Kom.'),
('KRS007','Kewarganegaraan',2,'Dr. Agus Machfud Fauzi, M.Si.'),
('KRS008','Praktikum Pemrograman Web',2,'Belum dikonfirmasi'),
('KRS009','Praktikum Analisis dan Desain Perangkat Lunak',1,'Belum dikonfirmasi'),
('KRS010','Praktikum Pemrograman Game',2,'Belum dikonfirmasi'),
('KRS011','Praktikum Basis Data Lanjut',2,'Belum dikonfirmasi'),
('KRS012','Praktikum Pemrograman Berorientasi Objek',2,'Belum dikonfirmasi')
ON DUPLICATE KEY UPDATE nama_mk=VALUES(nama_mk), sks=VALUES(sks), dosen=VALUES(dosen);

-- Ganti jadwal kuliah dengan 12 baris dari KRS.
DELETE FROM jadwal;

INSERT INTO jadwal (mata_kuliah_id,hari,jam_mulai,jam_selesai,ruangan,kelas)
SELECT id,'Senin','10:20:00','12:00:00','K10.02.02','2025B' FROM mata_kuliah WHERE kode_mk='MI201';
INSERT INTO jadwal (mata_kuliah_id,hari,jam_mulai,jam_selesai,ruangan,kelas)
SELECT id,'Senin','13:00:00','14:40:00','K10.02.02','2025B' FROM mata_kuliah WHERE kode_mk='KRS008';
INSERT INTO jadwal (mata_kuliah_id,hari,jam_mulai,jam_selesai,ruangan,kelas)
SELECT id,'Senin','15:30:00','17:10:00','K02.01.03','2025B' FROM mata_kuliah WHERE kode_mk='MI203';
INSERT INTO jadwal (mata_kuliah_id,hari,jam_mulai,jam_selesai,ruangan,kelas)
SELECT id,'Senin','17:10:00','18:00:00','K02.01.03','2025B' FROM mata_kuliah WHERE kode_mk='KRS009';
INSERT INTO jadwal (mata_kuliah_id,hari,jam_mulai,jam_selesai,ruangan,kelas)
SELECT id,'Selasa','14:40:00','16:20:00','K10.02.06','2025B' FROM mata_kuliah WHERE kode_mk='MI205';
INSERT INTO jadwal (mata_kuliah_id,hari,jam_mulai,jam_selesai,ruangan,kelas)
SELECT id,'Selasa','16:20:00','18:00:00','K10.02.06','2025B' FROM mata_kuliah WHERE kode_mk='KRS010';
INSERT INTO jadwal (mata_kuliah_id,hari,jam_mulai,jam_selesai,ruangan,kelas)
SELECT id,'Rabu','09:30:00','12:00:00','K02.01.03','2025B' FROM mata_kuliah WHERE kode_mk='KRS006';
INSERT INTO jadwal (mata_kuliah_id,hari,jam_mulai,jam_selesai,ruangan,kelas)
SELECT id,'Kamis','10:20:00','12:00:00','K10.02.06','2025B' FROM mata_kuliah WHERE kode_mk='MI202';
INSERT INTO jadwal (mata_kuliah_id,hari,jam_mulai,jam_selesai,ruangan,kelas)
SELECT id,'Kamis','13:00:00','14:40:00','K10.02.06','2025B' FROM mata_kuliah WHERE kode_mk='KRS011';
INSERT INTO jadwal (mata_kuliah_id,hari,jam_mulai,jam_selesai,ruangan,kelas)
SELECT id,'Jumat','10:20:00','12:00:00','K10.02.09','2025B' FROM mata_kuliah WHERE kode_mk='MI204';
INSERT INTO jadwal (mata_kuliah_id,hari,jam_mulai,jam_selesai,ruangan,kelas)
SELECT id,'Jumat','13:00:00','14:40:00','K10.02.09','2025B' FROM mata_kuliah WHERE kode_mk='KRS012';
INSERT INTO jadwal (mata_kuliah_id,hari,jam_mulai,jam_selesai,ruangan,kelas)
SELECT id,'Selasa','07:00:00','08:40:00','VIRTUAL','PK001' FROM mata_kuliah WHERE kode_mk='KRS007';

COMMIT;
