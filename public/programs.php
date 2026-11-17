<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/app.php';
require_once APP_ROOT . '/config/database.php';
require_once APP_ROOT . '/app/Middleware/AuthMiddleware.php';
require_once APP_ROOT . '/app/Controllers/ProgramController.php';

requireAuth();

$role = currentUser()['role'] ?? '';

$canManage = in_array($role, ['admin', 'management'], true);

$controller = new ProgramController($pdo);

$action = $_GET['action'] ?? 'index';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Hak akses
if (
    in_array($action, ['create', 'store', 'edit', 'update', 'status', 'delete'], true)
    && !$canManage
) {
    http_response_code(403);
    require APP_ROOT . '/app/Views/errors/403.php';
    exit;
}

// Daftar program
if ($action === 'index') {

    $filters = [
        'search' => trim($_GET['search'] ?? ''),
        'status' => $_GET['status'] ?? '',
        'level' => $_GET['level'] ?? '',
        'age_range' => $_GET['age_range'] ?? '',
    ];

    $programs = $controller->filter($filters);

    $pageTitle = 'Manajemen Program';

    require APP_ROOT . '/app/Views/layouts/header.php';
    require APP_ROOT . '/app/Views/programs/index.php';
    require APP_ROOT . '/app/Views/layouts/footer.php';

    exit;
}

// Form tambah program
if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $pageTitle = 'Tambah Program';
    $errors = [];

    require APP_ROOT . '/app/Views/layouts/header.php';
    require APP_ROOT . '/app/Views/programs/create.php';
    require APP_ROOT . '/app/Views/layouts/footer.php';

    exit;
}

// Simpan program baru
if ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->store($_POST);

    if ($result['success']) {
        header('Location: ' . APP_URL . '/programs.php?message=created');
        exit;
    }

    $errors = $result['errors'] ?? [];
    $pageTitle = 'Tambah Program';

    require APP_ROOT . '/app/Views/layouts/header.php';
    require APP_ROOT . '/app/Views/programs/create.php';
    require APP_ROOT . '/app/Views/layouts/footer.php';

    exit;
}

// Form edit program
if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $program = $controller->show($id);

    if (!$program) {
        http_response_code(404);
        exit('Program tidak ditemukan.');
    }

    $pageTitle = 'Edit Program';
    $errors = [];

    require APP_ROOT . '/app/Views/layouts/header.php';
    require APP_ROOT . '/app/Views/programs/edit.php';
    require APP_ROOT . '/app/Views/layouts/footer.php';

    exit;
}

// Perbarui program
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->update($id, $_POST);

    if ($result['success']) {
        header('Location: ' . APP_URL . '/programs.php?message=updated');
        exit;
    }

    $program = $controller->show($id);

    if (!$program) {
        http_response_code(404);
        exit('Program tidak ditemukan.');
    }

    $errors = $result['errors'] ?? [];
    $pageTitle = 'Edit Program';

    require APP_ROOT . '/app/Views/layouts/header.php';
    require APP_ROOT . '/app/Views/programs/edit.php';
    require APP_ROOT . '/app/Views/layouts/footer.php';

    exit;
}

// Ubah status program
if ($action === 'status' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] ?? '';

    $result = $controller->changeStatus($id, $status);

    header(
        'Location: ' . APP_URL . '/programs.php?message=' .
        ($result['success'] ? 'status_updated' : 'error')
    );
    exit;
}

// Hapus program
if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $result = $controller->delete($id);

    $message = $result['success']
        ? 'deleted'
        : 'delete_error';

    $detail = urlencode($result['message']);

    header(
        'Location: ' . APP_URL .
        '/programs.php?message=' . $message .
        '&detail=' . $detail
    );

    exit;
}

http_response_code(404);
echo 'Halaman tidak ditemukan.';