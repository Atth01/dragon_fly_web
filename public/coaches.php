<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/app.php';
require_once APP_ROOT . '/config/database.php';
require_once APP_ROOT . '/app/Middleware/AuthMiddleware.php';
require_once APP_ROOT . '/app/Controllers/CoachController.php';

// Semua pengguna harus login.
requireAuth();

// Ambil data pengguna.
$user = currentUser();
$role = $user['role'] ?? '';

// Admin dan Management boleh mengelola data.
$canManage = in_array($role, ['admin', 'management'], true);

// Inisialisasi database dan controller.
// Sesuaikan $pdo jika nama koneksi di database.php berbeda.
$controller = new CoachController($pdo);

$action = $_GET['action'] ?? 'index';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Proses daftar pelatih.
if ($action === 'index') {
    $coaches = $controller->index();

    $pageTitle = 'Manajemen Pelatih';

    require APP_ROOT . '/app/Views/layouts/header.php';
    require APP_ROOT . '/app/Views/coaches/index.php';
    require APP_ROOT . '/app/Views/layouts/footer.php';

    exit;
}

// Selain admin dan management tidak boleh melakukan perubahan data.
if (!$canManage) {
    http_response_code(403);
    require APP_ROOT . '/app/Views/errors/403.php';
    exit;
}

// Halaman tambah pelatih.
if ($action === 'create') {
    $pageTitle = 'Tambah Pelatih';

    require APP_ROOT . '/app/Views/layouts/header.php';
    require APP_ROOT . '/app/Views/coaches/create.php';
    require APP_ROOT . '/app/Views/layouts/footer.php';

    exit;
}

// Proses penyimpanan pelatih.
if ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->store($_POST, $_FILES['photo'] ?? []);

    if ($result['success']) {
        header('Location: ' . APP_URL . '/coaches.php?message=created');
    } else {
        header('Location: ' . APP_URL . '/coaches.php?message=error&detail=' . urlencode($result['message']));
    }

    exit;
}

// Halaman edit pelatih.
if ($action === 'edit' && $id > 0) {
    $coach = $controller->show($id);

    if (!$coach) {
        header('Location: ' . APP_URL . '/coaches.php?message=error');
        exit;
    }

    $pageTitle = 'Edit Pelatih';

    require APP_ROOT . '/app/Views/layouts/header.php';
    require APP_ROOT . '/app/Views/coaches/edit.php';
    require APP_ROOT . '/app/Views/layouts/footer.php';

    exit;
}

// Proses update pelatih.
if ($action === 'update' && $id > 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->update($id, $_POST, $_FILES['photo'] ?? []);

    if ($result['success']) {
        header('Location: ' . APP_URL . '/coaches.php?message=updated');
    } else {
        header('Location: ' . APP_URL . '/coaches.php?message=error&detail=' . urlencode($result['message']));
    }

    exit;
}

// Proses perubahan status.
if ($action === 'status' && $id > 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->changeStatus(
        $id,
        $_POST['status'] ?? ''
    );

    header(
        'Location: ' . APP_URL . '/coaches.php?message=' .
        ($result['success'] ? 'status_updated' : 'error')
    );

    exit;
}

// Proses penghapusan pelatih.
if ($action === 'delete' && $id > 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->delete($id);

    if ($result['success']) {
        header('Location: ' . APP_URL . '/coaches.php?message=deleted');
    } else {
        header(
            'Location: ' . APP_URL .
            '/coaches.php?message=delete_error&detail=' .
            urlencode($result['message'])
        );
    }

    exit;
}

// Jika action tidak dikenal.
http_response_code(404);
echo 'Halaman tidak ditemukan.';
