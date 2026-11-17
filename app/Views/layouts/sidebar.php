<?php

$user = currentUser();
$role = $user['role'] ?? '';

$menus = [
    'admin' => [
        ['label' => 'Dashboard', 'url' => 'dashboard.php', 'icon' => 'bi-speedometer2'],
        ['label' => 'Anggota', 'url' => '#', 'icon' => 'bi-people'],
        ['label' => 'Pelatih', 'url' => 'coaches.php', 'icon' => 'bi-person-badge'],
        ['label' => 'Program Latihan', 'url' => 'programs.php', 'icon' => 'bi-water'],
        ['label' => 'Jadwal Latihan', 'url' => '#', 'icon' => 'bi-calendar-week'],
        ['label' => 'Absensi', 'url' => '#', 'icon' => 'bi-clipboard-check'],
        ['label' => 'Galeri', 'url' => '#', 'icon' => 'bi-images'],
        ['label' => 'Pengguna', 'url' => '#', 'icon' => 'bi-person-gear'],
    ],

    'management' => [
        ['label' => 'Dashboard', 'url' => 'dashboard.php', 'icon' => 'bi-speedometer2'],
        ['label' => 'Anggota', 'url' => '#', 'icon' => 'bi-people'],
        ['label' => 'Pelatih', 'url' => 'coaches.php', 'icon' => 'bi-person-badge'],
        ['label' => 'Program Latihan', 'url' => 'programs.php', 'icon' => 'bi-water'],
        ['label' => 'Jadwal Latihan', 'url' => '#', 'icon' => 'bi-calendar-week'],
        ['label' => 'Penugasan Pelatih', 'url' => '#', 'icon' => 'bi-person-check'],
        ['label' => 'Laporan', 'url' => '#', 'icon' => 'bi-file-earmark-bar-graph'],
    ],

    'coach' => [
        ['label' => 'Dashboard', 'url' => 'dashboard.php', 'icon' => 'bi-speedometer2'],
        ['label' => 'Jadwal Latihan', 'url' => '#', 'icon' => 'bi-calendar-week'],
        ['label' => 'Program Latihan', 'url' => 'programs.php', 'icon' => 'bi-water'],
        ['label' => 'Absensi', 'url' => '#', 'icon' => 'bi-clipboard-check'],
        ['label' => 'Catatan Latihan', 'url' => '#', 'icon' => 'bi-journal-text'],
    ],
];

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidebar">

    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="bi bi-water"></i>
        </div>

        <div>
            <h5>DRAGONFLY</h5>
            <small>Swimming Club</small>
        </div>
    </div>

    <div class="sidebar-section">
        <small class="section-label">MENU UTAMA</small>

        <?php foreach ($menus[$role] ?? [] as $menu): ?>

            <?php
            $active = $currentPage === $menu['url'];
            ?>

            <a
                href="<?= htmlspecialchars(APP_URL . '/' . $menu['url']) ?>"
                class="sidebar-link <?= $active ? 'active' : '' ?>"
            >
                <i class="bi <?= htmlspecialchars($menu['icon']) ?>"></i>
                <span><?= htmlspecialchars($menu['label']) ?></span>
            </a>

        <?php endforeach; ?>

    </div>

    <div class="sidebar-bottom">
        <div class="sidebar-user">
            <div class="user-avatar">
                <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
            </div>

            <div class="user-info">
                <strong><?= htmlspecialchars($user['name'] ?? '') ?></strong>
                <small><?= htmlspecialchars(ucfirst($role)) ?></small>
            </div>
        </div>
    </div>

</aside>