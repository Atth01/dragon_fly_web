<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/app.php';
require_once APP_ROOT . '/app/Middleware/AuthMiddleware.php';

requireAuth();

$user = currentUser();
$role = $user['role'] ?? '';

$dashboardViews = [
    'admin' => 'admin.php',
    'management' => 'management.php',
    'coach' => 'coach.php',
];

if (!isset($dashboardViews[$role])) {
    http_response_code(403);
    require APP_ROOT . '/app/Views/errors/403.php';
    exit;
}

$pageTitle = 'Dashboard';

// Tampilkan header dan sidebar
require APP_ROOT . '/app/Views/layouts/header.php';

// Tampilkan dashboard berdasarkan role
require APP_ROOT . '/app/Views/dashboard/' . $dashboardViews[$role];

// Tampilkan footer
require APP_ROOT . '/app/Views/layouts/footer.php';
