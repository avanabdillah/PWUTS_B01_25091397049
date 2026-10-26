<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
header('Content-Type: application/json; charset=utf-8');
$search=trim((string)($_GET['q']??''));$day=trim((string)($_GET['hari']??'Semua Hari'));$sort=(string)($_GET['sort']??'day');
$allowedDays=['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
$orderBy=match($sort){'name'=>'m.nama_mk ASC, j.jam_mulai ASC','time'=>"FIELD(j.hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), j.jam_mulai ASC",default=>"FIELD(j.hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), j.jam_mulai ASC"};
$sql="SELECT j.id,j.hari,j.jam_mulai,j.jam_selesai,m.nama_mk,m.sks,m.dosen,j.ruangan,j.kelas,j.catatan_perubahan FROM jadwal j JOIN mata_kuliah m ON m.id=j.mata_kuliah_id WHERE 1=1";$params=[];
if($day!=='Semua Hari'&&in_array($day,$allowedDays,true)){$sql.=" AND j.hari=?";$params[]=$day;}
if($search!==''){$sql.=" AND (m.nama_mk LIKE ? OR m.dosen LIKE ? OR j.ruangan LIKE ? OR j.kelas LIKE ?)";$like='%'.$search.'%';array_push($params,$like,$like,$like,$like);}
$sql.=" ORDER BY {$orderBy}";$stmt=$pdo->prepare($sql);$stmt->execute($params);$rows=$stmt->fetchAll();
echo json_encode(['success'=>true,'count'=>count($rows),'data'=>$rows],JSON_UNESCAPED_UNICODE);
