<?php

declare(strict_types=1);

require_once APP_ROOT . '/config/session.php';

class AuthController
{
    private PDO $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function login(string $identity, string $password): array
    {
        $stmt = $this->db->prepare("
            SELECT
                users.id,
                users.name,
                users.username,
                users.email,
                users.password_hash,
                users.status,
                roles.name AS role
            FROM users
            INNER JOIN roles ON roles.id = users.role_id
            WHERE users.username = :username
               OR users.email = :email
            LIMIT 1
        ");

        $stmt->execute([
            'username' => $identity,
            'email' => $identity,
        ]);

        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return [
                'success' => false,
                'message' => 'Username/email atau password salah.'
            ];
        }

        if ($user['status'] !== 'active') {
            return [
                'success' => false,
                'message' => 'Akun Anda tidak aktif. Hubungi administrator.'
            ];
        }

        // Mencegah session fixation
        session_regenerate_id(true);

        // Simpan informasi pengguna ke session
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];

        // Catat waktu login
        $update = $this->db->prepare("
            UPDATE users
            SET last_login_at = NOW()
            WHERE id = :id
        ");

        $update->execute(['id' => $user['id']]);

        return [
            'success' => true,
            'message' => 'Login berhasil.'
        ];
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(session_name(), '', [
                'expires' => time() - 42000,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => $params['samesite'] ?? 'Lax',
            ]);
        }

        session_destroy();
    }
}