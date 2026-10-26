<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
$pageTitle='Jadwal Perkuliahan | ClassPlan';
$days=['Semua Hari','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
$flash=flash_get();
require __DIR__.'/includes/header.php';
?>
<main class="container inner-page"><div class="page-heading"><div><span class="eyebrow">KELAS 2025B</span><h1>Jadwal <span>Perkuliahan.</span></h1><p>Temukan jadwal berdasarkan hari, mata kuliah, dosen, waktu, dan ruangan.</p></div><a class="button button-primary" href="tugas.php">Lihat Tugas →</a></div>
<?php if($flash):?><div class="flash-message <?=e($flash['type'])?>"><?=e($flash['message'])?></div><?php endif;?>
<section class="filter-panel"><div class="search-field"><span>⌕</span><input id="searchSchedule" type="search" placeholder="Cari mata kuliah, dosen, atau ruangan..." autocomplete="off"></div><label class="filter-select"><span>Hari</span><select id="filterDay"><?php foreach($days as $day):?><option value="<?=e($day)?>"><?=e($day)?></option><?php endforeach;?></select></label><label class="filter-select"><span>Urutkan</span><select id="sortSchedule"><option value="day">Hari</option><option value="name">Nama mata kuliah</option><option value="time">Waktu mulai</option></select></label></section>
<div class="results-line"><span id="resultsCount">Memuat jadwal...</span><span class="live-indicator"><i></i> Data dari database</span></div><section id="scheduleResults" class="table-card" aria-live="polite"><div class="loading-state">Memuat data jadwal...</div></section>
<div class="tip-box"><span>💡</span><p><strong>Tips:</strong> Hasil pencarian dan filter diperbarui menggunakan AJAX tanpa memuat ulang seluruh halaman. Perubahan jadwal dari PJ dapat dicatat pada sistem.</p></div></main>
<?php require __DIR__.'/includes/footer.php'; ?>
