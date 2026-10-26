# ClassPlan — UTS Pemrograman Web

## Ringkasan
ClassPlan adalah prototype front-end untuk membantu mahasiswa melihat jadwal perkuliahan dan memantau tugas/deadline. Versi ini disesuaikan untuk latihan HTML, CSS, JavaScript/jQuery, dan AJAX tanpa PHP atau database server.

## Teknologi
- HTML untuk struktur halaman.
- CSS buatan sendiri pada `assets/css/style.css` untuk layout dan responsive design.
- JavaScript dan jQuery pada `assets/js/app.js` untuk pencarian, filter, validasi form, dan interaksi.
- AJAX (`$.ajax`) untuk memuat file halaman HTML ke dalam area konten di `index.html`.
- Teknologi yang digunakan: HTML, CSS, JavaScript, jQuery, dan AJAX sisi frontend.

## Struktur folder
- `index.html`: layout utama, navigasi, dan area konten.
- `pages/beranda.html`: halaman ringkasan.
- `pages/jadwal.html`: halaman jadwal dengan pencarian, filter hari, dan pengurutan.
- `pages/tugas.html`: daftar tugas, pencarian, filter status, dan simulasi perubahan status.
- `pages/form-tugas.html`: form tambah tugas dengan validasi JavaScript.
- `assets/css/style.css`: styling dan responsive design.
- `assets/js/app.js`: data contoh, AJAX, filter, dan validasi.

## Cara menjalankan
AJAX membutuhkan halaman yang dilayani melalui HTTP, jadi jangan membuka `index.html` langsung menggunakan `file://`.
1. Buka folder project di editor.
2. Jalankan server lokal sederhana, misalnya fitur Live Server di VS Code, atau gunakan hosting statis.
3. Buka alamat `index.html` dari server tersebut.
4. Navigasikan menu Beranda, Jadwal, Tugas & Deadline, dan Tambah Tugas. Konten halaman akan dimuat ke layout utama melalui AJAX.

## Catatan fitur
- Data jadwal mengikuti screenshot KRS kelas 2025B yang diberikan. Total 12 entri jadwal dengan total 24 SKS. Nama dosen menggunakan nama yang tersedia pada project sebelumnya; verifikasi kembali dengan jadwal resmi karena screenshot KRS tidak mencantumkan nama dosen.
- Data tugas contoh dapat ditambah melalui form dan disimpan di `localStorage` browser.
- Perubahan status tugas juga disimpan di `localStorage`.
- Data ini hanya tersedia di browser yang sama; belum ada akun, sinkronisasi antarperangkat, autentikasi, atau penyimpanan database.
- Form memvalidasi kolom wajib, tanggal deadline, dan format link menggunakan JavaScript sebelum data ditambahkan ke daftar simulasi di browser.
- Karena versi ini sengaja tidak memakai back-end, form tidak mengirim data untuk diproses ke endpoint server. Soal UTS menyebut submit ke server HTTP; jika dosen menafsirkan bagian itu sebagai kewajiban pengiriman sungguhan, konfirmasikan apakah simulasi front-end ini diterima. Pengiriman dan penyimpanan data sungguhan memerlukan endpoint penerima, yang tidak disediakan pada project statis ini.
- Data tugas dan deadline tetap berupa data simulasi. Data dosen perlu diverifikasi dengan jadwal resmi karena tidak terlihat pada screenshot KRS.
