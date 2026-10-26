<?php
require __DIR__ . '/config/db.php';

$emails = [
    'mahasiswa@classplan.local',
    'pj@classplan.local'
];

foreach ($emails as $email) {
    $stmt = $pdo->prepare(
        'SELECT email, password FROM users WHERE email = ? LIMIT 1'
    );
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        echo htmlspecialchars($email) . ': AKUN TIDAK DITEMUKAN<br>';
        continue;
    }

    $valid = password_verify('password', $user['password']);

    echo htmlspecialchars($email) . ': ';
    echo $valid ? 'PASSWORD COCOK' : 'PASSWORD TIDAK COCOK';
    echo '<br>';
}