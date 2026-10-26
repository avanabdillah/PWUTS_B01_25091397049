<?php
require_once __DIR__.'/config/db.php';
require_once __DIR__.'/includes/functions.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valid()) { flash_set('error','Permintaan tidak valid.'); header('Location: tugas.php'); exit; }
$id=(int)($_POST['tugas_id']??0); $status=(string)($_POST['status']??'');
$allowed=['Belum dikerjakan','Sedang dikerjakan','Selesai'];
if($id<1 || !in_array($status,$allowed,true)){flash_set('error','Data status tidak valid.');header('Location: tugas.php');exit;}
$check=$pdo->prepare('SELECT id FROM tugas WHERE id=?');$check->execute([$id]);
if(!$check->fetch()){flash_set('error','Tugas tidak ditemukan.');header('Location: tugas.php');exit;}
$stmt=$pdo->prepare('INSERT INTO status_tugas(tugas_id,user_id,status) VALUES(?,?,?) ON DUPLICATE KEY UPDATE status=VALUES(status)');
$stmt->execute([$id,(int)$_SESSION['user_id'],$status]);
flash_set('success','Status tugas berhasil diperbarui.');header('Location: tugas.php');exit;
