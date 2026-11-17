<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/app.php';
require_once APP_ROOT . '/config/database.php';
require_once APP_ROOT . '/config/session.php';
require_once APP_ROOT . '/app/Controllers/AuthController.php';

$auth = new AuthController($pdo);
$error = '';

if (isset($_SESSION['user'])) {
    header('Location: ' . APP_URL . '/dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identity = trim($_POST['identity'] ?? '');
    $password = $_POST['password'] ?? '';

    $result = $auth->login($identity, $password);

    if ($result['success']) {
        header('Location: ' . APP_URL . '/dashboard.php');
        exit;
    }

    $error = $result['message'];
}

require APP_ROOT . '/app/Views/auth/login.php';