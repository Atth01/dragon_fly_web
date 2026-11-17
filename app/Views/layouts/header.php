<?php

$pageTitle = $pageTitle ?? 'Dashboard';
$user = currentUser();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars($pageTitle) ?> - DragonFly Swimming Club
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="<?= APP_URL ?>/assets/css/dashboard.css">
</head>

<body>

<div class="app-wrapper">

    <?php require APP_ROOT . '/app/Views/layouts/sidebar.php'; ?>

    <main class="main-content">

        <header class="topbar">

            <div>
                <h5><?= htmlspecialchars($pageTitle) ?></h5>
                <small>DragonFly Swimming Club Management System</small>
            </div>

            <div class="topbar-user">

                <div class="text-end">
                    <strong>
                        <?= htmlspecialchars($user['name'] ?? '') ?>
                    </strong>

                    <small>
                        <?= htmlspecialchars(ucfirst($user['role'] ?? '')) ?>
                    </small>
                </div>

                <div class="topbar-avatar">
                    <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
                </div>

                <a href="<?= APP_URL ?>/logout.php"
                   class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </a>

            </div>

        </header>

        <section class="page-content">