<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/config/database.php';

$testUsers = [
    [
        'role' => 'management',
        'name' => 'Management DragonFly',
        'username' => 'management',
        'email' => 'management@dragonfly.local',
        'password' => 'Management2026!'
    ],
    [
        'role' => 'coach',
        'name' => 'Coach DragonFly',
        'username' => 'coach',
        'email' => 'coach@dragonfly.local',
        'password' => 'Coach2026!'
    ]
];

foreach ($testUsers as $data) {

    // Ambil ID role
    $stmt = $pdo->prepare("
        SELECT id FROM roles
        WHERE name = :role
        LIMIT 1
    ");

    $stmt->execute(['role' => $data['role']]);
    $role = $stmt->fetch();

    if (!$role) {
        echo "Role {$data['role']} tidak ditemukan.\n";
        continue;
    }

    // Periksa username
    $stmt = $pdo->prepare("
        SELECT id FROM users
        WHERE username = :username
        LIMIT 1
    ");

    $stmt->execute(['username' => $data['username']]);

    if ($stmt->fetch()) {
        echo "User {$data['username']} sudah tersedia.\n";
        continue;
    }

    // Buat akun
    $passwordHash = password_hash(
        $data['password'],
        PASSWORD_DEFAULT
    );

    $insert = $pdo->prepare("
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

    $insert->execute([
        'role_id' => $role['id'],
        'name' => $data['name'],
        'username' => $data['username'],
        'email' => $data['email'],
        'password_hash' => $passwordHash
    ]);

    echo "User {$data['username']} berhasil dibuat.\n";
}