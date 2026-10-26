<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Tambah Jadwal | Sikulia';
$days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
$values = ['hari'=>'Senin','jam_mulai'=>'08:00','jam_selesai'=>'09:40','mata_kuliah'=>'','dosen'=>'','ruangan'=>'','kelas'=>'2025B'];
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($values as $key => $default) $values[$key] = trim((string)($_POST[$key] ?? $default));
    if (!csrf_valid()) {
        $error = 'Sesi formulir tidak valid. Muat ulang halaman lalu coba lagi.';
    } elseif (!in_array($values['hari'], $days, true) || $values['mata_kuliah']==='' || $values['dosen']==='' || $values['ruangan']==='' || $values['kelas']==='') {
        $error = 'Semua kolom wajib diisi dengan benar.';
    } elseif (!preg_match('/^\d{2}:\d{2}$/', $values['jam_mulai']) || !preg_match('/^\d{2}:\d{2}$/', $values['jam_selesai']) || $values['jam_mulai'] >= $values['jam_selesai']) {
        $error = 'Waktu selesai harus setelah waktu mulai.';
    } else {
        $conflict = $pdo->prepare("SELECT COUNT(*) FROM jadwal WHERE hari = ? AND (? < jam_selesai AND ? > jam_mulai)");
        $conflict->execute([$values['hari'], $values['jam_mulai'] . ':00', $values['jam_selesai'] . ':00']);
        if ((int)$conflict->fetchColumn() > 0) {
            $error = 'Jadwal bentrok dengan jadwal lain pada hari dan rentang waktu tersebut. Periksa kembali.';
        } else {
            $stmt = $pdo->prepare("INSERT INTO jadwal (hari,jam_mulai,jam_selesai,mata_kuliah,dosen,ruangan,kelas) VALUES (?,?,?,?,?,?,?)");
            $stmt->execute([$values['hari'], $values['jam_mulai'], $values['jam_selesai'], $values['mata_kuliah'], $values['dosen'], $values['ruangan'], $values['kelas']]);
            header('Location: jadwal.php?added=1');
            exit;
        }
    }
}
require __DIR__ . '/includes/header.php';
?>
<main class="container inner-page form-page">
  <div class="page-heading"><div><span class="eyebrow">DATA PERKULIAHAN</span><h1>Tambah <span>Jadwal.</span></h1><p>Isi detail perkuliahan. Sistem akan memeriksa potensi bentrok waktu.</p></div><a class="text-link" href="jadwal.php">← Kembali ke jadwal</a></div>
  <div class="form-layout"><form class="schedule-form" method="post" action="tambah-jadwal.php" id="scheduleForm" novalidate>
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <?php if ($error): ?><div class="form-alert" role="alert"><?= e($error) ?></div><?php endif; ?>
    <div class="form-section-title"><span>01</span><div><h2>Informasi mata kuliah</h2><p>Masukkan informasi utama perkuliahan.</p></div></div>
    <div class="form-grid"><label class="form-field form-field-wide">Nama mata kuliah *<input name="mata_kuliah" id="mata_kuliah" value="<?= e($values['mata_kuliah']) ?>" placeholder="Contoh: Pemrograman Web" maxlength="120" required><small class="field-error"></small></label>
      <label class="form-field form-field-wide">Dosen pengampu *<input name="dosen" id="dosen" value="<?= e($values['dosen']) ?>" placeholder="Nama dosen" maxlength="120" required><small class="field-error"></small></label></div>
    <div class="form-section-title section-gap"><span>02</span><div><h2>Waktu dan lokasi</h2><p>Pastikan tidak bertabrakan dengan jadwal lain.</p></div></div>
    <div class="form-grid"><label class="form-field">Hari *<select name="hari" required><?php foreach ($days as $day): ?><option <?= $values['hari']===$day?'selected':'' ?> value="<?= e($day) ?>"><?= e($day) ?></option><?php endforeach; ?></select></label>
      <label class="form-field">Kelas *<input name="kelas" id="kelas" value="<?= e($values['kelas']) ?>" maxlength="40" required placeholder="Contoh: 2025B"></label>
      <label class="form-field">Jam mulai *<input type="time" name="jam_mulai" id="jam_mulai" value="<?= e($values['jam_mulai']) ?>" required></label>
      <label class="form-field">Jam selesai *<input type="time" name="jam_selesai" id="jam_selesai" value="<?= e($values['jam_selesai']) ?>" required><small class="field-error"></small></label>
      <label class="form-field form-field-wide">Ruangan *<input name="ruangan" id="ruangan" value="<?= e($values['ruangan']) ?>" maxlength="80" placeholder="Contoh: Lab Komputer A-203" required></label></div>
    <div class="form-actions"><a class="button button-ghost" href="jadwal.php">Batal</a><button class="button button-primary" type="submit">Simpan Jadwal <span>→</span></button></div>
  </form>
  <aside class="form-aside"><div class="aside-icon">◷</div><h3>Jadwal yang baik dimulai dari perencanaan.</h3><p>Periksa kembali waktu, kelas, dan ruangan sebelum menyimpan data.</p><div class="aside-check"><span>✓</span><div><strong>Validasi formulir</strong><small>Kolom wajib dan rentang waktu diperiksa.</small></div></div><div class="aside-check"><span>✓</span><div><strong>Pemeriksaan bentrok</strong><small>Sistem mengecek jadwal di hari yang sama.</small></div></div></aside></div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
