<?php
require_once __DIR__.'/config/db.php';
require_once __DIR__.'/includes/functions.php';
$pageTitle = 'Tugas & Deadline | ClassPlan';
$flash = flash_get();
$rows = $pdo->query("SELECT t.id,t.judul,t.deskripsi,t.deadline,t.link_pengumpulan,m.nama_mk,
  COALESCE(s.status,'Belum dikerjakan') AS status_tugas
  FROM tugas t JOIN mata_kuliah m ON m.id=t.mata_kuliah_id
  LEFT JOIN status_tugas s ON s.tugas_id=t.id AND s.user_id=".(int)($_SESSION['user_id'] ?? 0)."
  ORDER BY t.deadline ASC")->fetchAll();
require __DIR__.'/includes/header.php';
?>
<main class="container inner-page"><div class="page-heading"><div><span class="eyebrow">TUGAS & DEADLINE</span><h1>Jangan sampai <span>terlewat.</span></h1><p>Pantau tugas per mata kuliah, tenggat waktu, status pengerjaan, dan tautan pengumpulan.</p></div><?php if(($_SESSION['role']??'')==='pj'): ?><a class="button button-primary" href="kelola-tugas.php">＋ Kelola Tugas</a><?php else: ?><a class="button button-primary" href="login.php">Masuk untuk update status</a><?php endif; ?></div>
<?php if($flash): ?><div class="flash-message <?=e($flash['type'])?>"><?=e($flash['message'])?></div><?php endif; ?>
<div class="task-grid"><?php foreach($rows as $row): $overdue = strtotime($row['deadline']) < time(); ?><article class="task-card"><div class="task-card-top"><span class="task-course"><?=e($row['nama_mk'])?></span><span class="task-status status-<?= $row['status_tugas']==='Selesai'?'done':($row['status_tugas']==='Sedang dikerjakan'?'progress':'todo')?>"><?=e($row['status_tugas'])?></span></div><h2><?=e($row['judul'])?></h2><p><?=e($row['deskripsi'] ?: 'Tidak ada deskripsi tambahan.')?></p><div class="deadline-row"><span>◷ Deadline</span><strong class="<?= $overdue?'overdue':''?>"><?=e(date('d M Y, H:i',strtotime($row['deadline'])))?></strong></div><div class="task-actions"><?php if($row['link_pengumpulan']): ?><a class="button button-ghost" href="<?=e($row['link_pengumpulan'])?>" target="_blank" rel="noopener noreferrer">Buka tautan ↗</a><?php else: ?><span class="muted-small">Tautan belum tersedia</span><?php endif; ?><?php if(!empty($_SESSION['user_id'])): ?><form method="post" action="status-tugas.php"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="tugas_id" value="<?=(int)$row['id']?>"><select name="status" aria-label="Status tugas"><option <?= $row['status_tugas']==='Belum dikerjakan'?'selected':''?>>Belum dikerjakan</option><option <?= $row['status_tugas']==='Sedang dikerjakan'?'selected':''?>>Sedang dikerjakan</option><option <?= $row['status_tugas']==='Selesai'?'selected':''?>>Selesai</option></select><button class="small-submit" type="submit">Simpan</button></form><?php endif; ?></div></article><?php endforeach; ?></div>
<?php if(!$rows): ?><div class="empty-state"><h3>Belum ada tugas.</h3><p>Tugas yang ditambahkan PJ akan tampil di halaman ini.</p></div><?php endif; ?>
<div class="tip-box"><span>🔔</span><p><strong>Pengingat:</strong> daftar ini menampilkan deadline dari database. Notifikasi otomatis di luar website/browser belum diaktifkan; fitur reminder tersimpan sebagai data di tabel reminder.</p></div>
</main>
<?php require __DIR__.'/includes/footer.php'; ?>
