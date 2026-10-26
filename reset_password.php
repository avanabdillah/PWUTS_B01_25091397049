<?php
require_once __DIR__ . '/config/db.php';

$passwordBaru = password_hash('password', PASSWORD_DEFAULT);

$stmt = $pdo->prepare(
    "UPDATE users
     SET password = ?
     WHERE email IN (?, ?)"
);

$stmt->execute([
    $passwordBaru,
    'mahasiswa@classplan.local',
    'pj@classplan.local'
]);

echo "Password berhasil diperbarui.<br>";
echo "Jumlah akun diperbarui: " . $stmt->rowCount();