-- ClassPlan: sesuaikan jadwal dengan data KRS pada screenshot.
-- Jalankan di phpMyAdmin pada database jadwal_perkuliahan.
-- Skrip ini mempertahankan users, tugas, status_tugas, dan reminder.
USE jadwal_perkuliahan;

UPDATE mata_kuliah SET kode_mk='MI201', nama_mk='Pemrograman Web', sks=2, dosen='Belum ditentukan' WHERE id=1;
UPDATE mata_kuliah SET kode_mk='MI202', nama_mk='Basis Data Lanjut', sks=2, dosen='Asmunin, S.Kom., M.Kom.' WHERE id=2;
UPDATE mata_kuliah SET kode_mk='MI203', nama_mk='Analisis dan Disain Perangkat Lunak', sks=2, dosen='M Adamu Islam Mashuri, S.Tr.T., M.Tr.Kom.' WHERE id=3;
UPDATE mata_kuliah SET kode_mk='MI204', nama_mk='Pemrograman Berorientasi Objek', sks=2, dosen='Faris Abdi El Hakim, S.Kom., M.Tr.Kom.' WHERE id=4;
UPDATE mata_kuliah SET kode_mk='MI205', nama_mk='Kecerdasan Buatan', sks=3, dosen='Salamun Rohman Nudin, S.Kom., M.Kom.' WHERE id=5;

INSERT INTO mata_kuliah (id,kode_mk,nama_mk,sks,dosen,id_pj) VALUES
(6,'MI206','Prak. Pemrograman Web',2,'Belum ditentukan',2),
(7,'MI207','Prak. Analisis dan Disain Perangkat Lunak',1,'M Adamu Islam Mashuri, S.Tr.T., M.Tr.Kom.',2),
(8,'MI208','Prak. Pemrograman Game',2,'Moch. Badrus Sholeh, S.Kom., M.Kom.',2),
(9,'MI209','Prak. Basis Data Lanjut',2,'Asmunin, S.Kom., M.Kom.',2),
(10,'MI210','Prak. Pemrograman Berorientasi Objek',2,'Faris Abdi El Hakim, S.Kom., M.Tr.Kom.',2),
(11,'MI211','Pemrograman Game',2,'Moch. Badrus Sholeh, S.Kom., M.Kom.',2),
(12,'MI212','Kewarganegaraan',2,'Dr. Agus Machfud Fauzi, M.Si.',2)
ON DUPLICATE KEY UPDATE nama_mk=VALUES(nama_mk), sks=VALUES(sks), dosen=VALUES(dosen);

DELETE FROM jadwal;
INSERT INTO jadwal (mata_kuliah_id,hari,jam_mulai,jam_selesai,ruangan,kelas) VALUES
(1,'Senin','10:20:00','12:00:00','K10.02.02','2025B'),
(6,'Senin','13:00:00','14:40:00','K10.02.02','2025B'),
(3,'Senin','15:30:00','17:10:00','K02.01.03','2025B'),
(7,'Senin','17:10:00','18:00:00','K02.01.03','2025B'),
(12,'Selasa','07:00:00','08:40:00','VIRTUAL','PK001'),
(11,'Selasa','14:40:00','16:20:00','K10.02.06','2025B'),
(8,'Selasa','16:20:00','18:00:00','K10.02.06','2025B'),
(5,'Rabu','09:30:00','12:00:00','K02.01.03','2025B'),
(2,'Kamis','10:20:00','12:00:00','K10.02.06','2025B'),
(9,'Kamis','13:00:00','14:40:00','K10.02.06','2025B'),
(4,'Jumat','10:20:00','12:00:00','K10.02.09','2025B'),
(10,'Jumat','13:00:00','14:40:00','K10.02.09','2025B');

-- Total SKS KRS: 24
