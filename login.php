<?php
require_once __DIR__.'/config/db.php';
require_once __DIR__.'/includes/functions.php';
if (!empty($_SESSION['user_id'])) { header('Location: index.php'); exit; }
$pageTitle = 'Masuk | ClassPlan';
$error = '';
$email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim((string)($_POST['email'] ?? ''));
  $password = (string)($_POST['password'] ?? '');
  if (!csrf_valid()) $error = 'Sesi formulir tidak valid. Muat ulang halaman.';
  elseif (!filter_var($email,FILTER_VALIDATE_EMAIL) || $password === '') $error = 'Masukkan email dan password dengan benar.';
  else {
    $stmt = $pdo->prepare('SELECT id,nama,email,password,role FROM users WHERE email=? LIMIT 1');
    $stmt->execute([$email]); $user = $stmt->fetch();
    if ($user && password_verify($password,$user['password'])) {
      session_regenerate_id(true);
      $_SESSION['user_id']=(int)$user['id']; $_SESSION['nama']=$user['nama'];
      $_SESSION['email']=$user['email']; $_SESSION['role']=$user['role'];
      flash_set('success','Berhasil masuk. Selamat datang di ClassPlan!');
      $next = basename((string)($_GET['next'] ?? 'index.php'));
      if (!in_array($next,['index.php','jadwal.php','tugas.php','kelola-tugas.php'],true)) $next='index.php';
      header('Location: '.$next); exit;
    } else $error = 'Email atau password tidak sesuai.';
  }
}
require __DIR__.'/includes/header.php';
?>
<main class="container inner-page auth-page"><div class="auth-card"><div class="auth-emblem">C</div><span class="eyebrow">SELAMAT DATANG KEMBALI</span><h1>Masuk ke <span>ClassPlan.</span></h1><p class="auth-intro">Lihat jadwal kuliah, pantau tugas, dan jangan lewatkan deadline.</p>
<?php if($error): ?><div class="form-alert" role="alert"><?=e($error)?></div><?php endif; ?>
<form method="post" class="schedule-form auth-form" novalidate><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>">
<label class="form-field">Email<input type="email" name="email" value="<?=e($email)?>" required autocomplete="username" placeholder="nama@email.com"></label>
<label class="form-field">Password<input type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password"></label>
<button class="button button-primary" type="submit">Masuk ke akun <span>→</span></button></form>
<div class="demo-credentials"><strong>Akun demo untuk pengujian</strong><p>Mahasiswa: <code>mahasiswa@classplan.local</code></p><p>PJ: <code>pj@classplan.local</code></p><p>Password keduanya: <code>password</code></p></div>
</div></main>
<?php require __DIR__.'/includes/footer.php'; ?>
