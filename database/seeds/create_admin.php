<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/config/database.php';

// Ambil role admin
$stmt = $pdo->prepare("
    SELECT id
    FROM roles
    WHERE name = :role
    LIMIT 1
");

$stmt->execute(['role' => 'admin']);
$adminRole = $stmt->fetch();

if (!$adminRole) {
    exit("Role admin tidak ditemukan.\n");
}

// Periksa apakah akun admin sudah tersedia
$stmt = $pdo->prepare("
    SELECT id
    FROM users
    WHERE username = :username
    LIMIT 1
");

$stmt->execute(['username' => 'admin']);

if ($stmt->fetch()) {
    exit("Akun admin sudah tersedia.\n");
}

// Buat password hash
$password = password_hash(
    'AdminDfly2026!',
    PASSWORD_DEFAULT
);

// Simpan akun admin
$stmt = $pdo->prepare("
    INSERT INTO users (
        role_id,
        name,
        username,
        email,
        password_hash,
        status,
        created_at,
        updated_at
    ) VALUES (
        :role_id,
        :name,
        :username,
        :email,
        :password_hash,
        'active',
        NOW(),
        NOW()
    )
");

$stmt->execute([
    'role_id' => $adminRole['id'],
    'name' => 'Administrator',
    'username' => 'admin',
    'email' => 'admin@dragonfly.local',
    'password_hash' => $password
]);

echo "Akun admin berhasil dibuat.\n";