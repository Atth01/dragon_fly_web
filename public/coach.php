<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/app.php';
require_once APP_ROOT . '/app/Middleware/AuthMiddleware.php';

requireRole(['coach']);

$user = currentUser();

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Coach Dashboard - DragonFly</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <h3>Coach Dashboard</h3>

            <p>
                Selamat datang,
                <strong><?= htmlspecialchars($user['name']) ?></strong>
            </p>

            <p class="text-muted">
                Halaman ini khusus untuk Coach.
            </p>

            <a href="dashboard.php" class="btn btn-secondary">
                Kembali
            </a>

        </div>
    </div>

</div>

</body>
</html>