<?php
require_once __DIR__.'/config/db.php';
require_once __DIR__.'/includes/functions.php';
require_pj();
$pageTitle='Kelola Tugas | ClassPlan';
$error='';
$courses=$pdo->prepare('SELECT id,nama_mk FROM mata_kuliah WHERE id_pj=? ORDER BY nama_mk');
$courses->execute([(int)$_SESSION['user_id']]);$courses=$courses->fetchAll();
if($_SERVER['REQUEST_METHOD']==='POST'){
  $course=(int)($_POST['mata_kuliah_id']??0);$title=trim((string)($_POST['judul']??''));$desc=trim((string)($_POST['deskripsi']??''));
  $deadline=trim((string)($_POST['deadline']??''));$link=trim((string)($_POST['link_pengumpulan']??''));
  if(!csrf_valid())$error='Sesi formulir tidak valid.';
  elseif($course<1||$title===''||$deadline==='')$error='Mata kuliah, judul tugas, dan deadline wajib diisi.';
  elseif($link!==''&&!filter_var($link,FILTER_VALIDATE_URL))$error='Tautan pengumpulan harus berupa URL yang valid.';
  else{
    $owner=$pdo->prepare('SELECT id FROM mata_kuliah WHERE id=? AND id_pj=?');$owner->execute([$course,(int)$_SESSION['user_id']]);
    if(!$owner->fetch())$error='Kamu hanya dapat mengelola tugas pada mata kuliah tanggung jawabmu.';
    else{$stmt=$pdo->prepare('INSERT INTO tugas(mata_kuliah_id,judul,deskripsi,deadline,link_pengumpulan,dibuat_oleh) VALUES(?,?,?,?,?,?)');$stmt->execute([$course,$title,$desc,$deadline,$link?:null,(int)$_SESSION['user_id']]);flash_set('success','Tugas berhasil ditambahkan.');header('Location: tugas.php');exit;}
  }
}
require __DIR__.'/includes/header.php';
?>
<main class="container inner-page form-page"><div class="page-heading"><div><span class="eyebrow">AREA PJ MATA KULIAH</span><h1>Kelola <span>Tugas.</span></h1><p>Tambahkan tugas untuk mata kuliah yang menjadi tanggung jawab akun PJ kamu.</p></div><a class="text-link" href="tugas.php">← Kembali ke tugas</a></div>
<div class="form-layout"><form class="schedule-form" method="post" id="taskForm" novalidate><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><?php if($error):?><div class="form-alert" role="alert"><?=e($error)?></div><?php endif;?>
<div class="form-section-title"><span>01</span><div><h2>Informasi tugas</h2><p>Detail yang akan dilihat mahasiswa.</p></div></div>
<div class="form-grid"><label class="form-field form-field-wide">Mata kuliah *<select name="mata_kuliah_id" required><?php foreach($courses as $course):?><option value="<?=(int)$course['id']?>"><?=e($course['nama_mk'])?></option><?php endforeach;?></select></label>
<label class="form-field form-field-wide">Judul tugas *<input name="judul" maxlength="160" required placeholder="Contoh: Membuat homepage responsif"></label>
<label class="form-field form-field-wide">Deskripsi<textarea name="deskripsi" rows="4" placeholder="Instruksi singkat tugas (opsional)"></textarea></label>
<label class="form-field form-field-wide">Deadline *<input type="datetime-local" name="deadline" required></label>
<label class="form-field form-field-wide">Link pengumpulan<input type="url" name="link_pengumpulan" placeholder="https://..."><small>Opsional, misalnya Google Drive atau Google Classroom.</small></label></div>
<div class="form-actions"><a class="button button-ghost" href="tugas.php">Batal</a><button class="button button-primary" type="submit">Simpan Tugas →</button></div></form>
<aside class="form-aside"><div class="aside-icon">✓</div><h3>Kelola hanya mata kuliahmu.</h3><p>Akses PJ dibatasi pada mata kuliah yang ditetapkan untuk akun tersebut.</p><div class="aside-check"><span>✓</span><div><strong>Deadline wajib</strong><small>Tentukan tanggal dan jam pengumpulan.</small></div></div><div class="aside-check"><span>✓</span><div><strong>Link tervalidasi</strong><small>URL pengumpulan diperiksa sebelum disimpan.</small></div></div></aside></div></main>
<?php require __DIR__.'/includes/footer.php'; ?>
