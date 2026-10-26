# ClassPlan — Sistem Jadwal Perkuliahan Kelas Berbasis Web

ClassPlan dikembangkan berdasarkan proposal *Pengembangan Website ClassPlan: Sistem Jadwal Perkuliahan Kelas Berbasis Web* untuk kelas 2025B. Aplikasi menyatukan jadwal, detail mata kuliah, tugas, deadline, tautan pengumpulan, status tugas, dan data reminder.

## Teknologi
- HTML5 dan CSS3 (responsive web design)
- JavaScript dan jQuery
- AJAX dengan jQuery untuk mengambil jadwal secara dinamis
- PHP 8+ dan PDO prepared statements
- MySQL/MariaDB

## Fitur sesuai proposal
- Login dan pembagian peran Mahasiswa/PJ Mata Kuliah
- Mahasiswa melihat jadwal, mata kuliah, dosen, waktu, dan ruangan
- Pencarian dan filter jadwal menggunakan AJAX
- Mahasiswa melihat tugas, deadline, dan link pengumpulan
- Mahasiswa memperbarui status tugas: Belum dikerjakan, Sedang dikerjakan, Selesai
- PJ menambahkan tugas untuk mata kuliah yang menjadi tanggung jawabnya
- Validasi formulir, perlindungan CSRF, prepared statements, dan escaping output
- Database enam tabel: `users`, `mata_kuliah`, `jadwal`, `tugas`, `status_tugas`, `reminder`
- Layout responsif untuk desktop dan perangkat mobile

## Cara menjalankan di XAMPP
1. Ekstrak folder proyek ke `C:\\xampp\\htdocs\\PWUTS_B01_25091397049`.
2. Jalankan Apache dan MySQL di XAMPP.
3. Buka `http://localhost/phpmyadmin`.
4. Import file `database.sql`. File ini membuat database `jadwal_perkuliahan` dan memasukkan data contoh.
5. Buka `http://localhost/PWUTS_B01_25091397049/`.
6. Uji halaman Beranda, Jadwal, Tugas & Deadline, dan login menggunakan akun demo.

## Akun demo
Kedua akun berikut memakai password `password`:
- Mahasiswa: `mahasiswa@classplan.local`
- PJ: `pj@classplan.local`

Akun demo hanya untuk pengujian lokal. Ganti password dan data contoh sebelum deployment/penggunaan nyata.

## Struktur proyek
```text
PWUTS_B01_25091397049/
├── index.php
├── login.php
├── logout.php
├── jadwal.php
├── api-jadwal.php
├── tugas.php
├── status-tugas.php
├── kelola-tugas.php
├── database.sql
├── README.md
├── config/db.php
├── includes/functions.php
├── includes/header.php
├── includes/footer.php
└── assets/
    ├── css/style.css
    └── js/app.js
```

## Catatan penting tentang ruang lingkup
- Data jadwal, mata kuliah, dosen, tugas, dan deadline adalah data contoh untuk demonstrasi. Sesuaikan dengan data resmi kelas dan proposal.
- Tabel `reminder` sudah disediakan untuk menyimpan pengaturan pengingat, tetapi notifikasi otomatis melalui email/push belum diimplementasikan.
- Fitur pengelolaan jadwal oleh PJ dan pengubahan/penghapusan tugas belum lengkap; versi ini memprioritaskan jadwal yang dapat dilihat, AJAX, login, daftar tugas, status tugas, dan penambahan tugas oleh PJ.
- Sebelum dikumpulkan, uji semua halaman melalui Apache/MySQL dan cek ulang kesesuaian dengan ketentuan dosen.
- Folder repository sesuai format UTS: `PWUTS_B01_25091397049`.


## ClassPlan Redesign V2
Versi ini memperbarui tampilan dengan tema navy-teal dan aksen kuning serta menambahkan cache-busting pada stylesheet. Setelah menyalin file, buka ulang halaman dengan Ctrl+F5. File database tidak perlu diimpor ulang untuk perubahan tampilan. Data mata kuliah dan jadwal yang sesuai KRS perlu diperbarui di database aktif menggunakan detail hari, jam, dan ruangan dari KRS.


## Sinkronisasi KRS
1. Backup database `jadwal_perkuliahan` terlebih dahulu.
2. Import `update_krs_24_sks.sql` melalui phpMyAdmin pada database `jadwal_perkuliahan`.
3. Script ini mempertahankan tabel pengguna dan tugas, tetapi mengganti seluruh baris pada tabel `jadwal` sesuai 12 baris KRS.
4. Nama dosen praktikum dan Pemrograman Web belum dikonfirmasi; sementara ditandai `Belum dikonfirmasi`.
5. Tabel jadwal menampilkan kolom SKS setelah pembaruan `api-jadwal.php` dan `assets/js/app.js`.
