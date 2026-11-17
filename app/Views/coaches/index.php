<?php

declare(strict_types=1);

/** @var array $coaches */
$coaches = $coaches ?? [];

$canManage = in_array(
    currentUser()['role'] ?? '',
    ['admin', 'management'],
    true
);

$message = $_GET['message'] ?? '';

$notifications = [
    'created' => 'Data pelatih berhasil ditambahkan.',
    'updated' => 'Data pelatih berhasil diperbarui.',
    'status_updated' => 'Status pelatih berhasil diperbarui.',
    'deleted' => 'Data pelatih berhasil dihapus.',
];

?>

<!-- Header Halaman -->
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

    <div>
        <h4 class="mb-1">Manajemen Pelatih</h4>
        <p class="text-muted mb-0">
            Kelola data pelatih DragonFly Swimming Club.
        </p>
    </div>

    <?php if ($canManage): ?>
        <a href="<?= APP_URL ?>/coaches.php?action=create"
           class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Pelatih
        </a>
    <?php endif; ?>

</div>

<!-- Notifikasi -->
<?php if (isset($notifications[$message])): ?>

    <div class="alert alert-success alert-dismissible fade show">
        <?= htmlspecialchars($notifications[$message], ENT_QUOTES, 'UTF-8') ?>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>
    </div>

<?php elseif ($message === 'error'): ?>

    <div class="alert alert-danger alert-dismissible fade show">
        Operasi data pelatih gagal dilakukan.

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>
    </div>

<?php elseif ($message === 'delete_error'): ?>

    <div class="alert alert-danger alert-dismissible fade show">
        <?= htmlspecialchars(
            $_GET['detail'] ?? 'Data pelatih gagal dihapus.',
            ENT_QUOTES,
            'UTF-8'
        ) ?>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>
    </div>

<?php endif; ?>

<!-- Ringkasan -->
<div class="row g-3 mb-4">

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small mb-1">Total Pelatih</div>
                <h3 class="mb-0"><?= count($coaches) ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small mb-1">Pelatih Aktif</div>
                <h3 class="mb-0">
                    <?= count(array_filter(
                        $coaches,
                        fn($coach) => $coach['status'] === 'active'
                    )) ?>
                </h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small mb-1">Pelatih Tidak Aktif</div>
                <h3 class="mb-0">
                    <?= count(array_filter(
                        $coaches,
                        fn($coach) => $coach['status'] === 'inactive'
                    )) ?>
                </h3>
            </div>
        </div>
    </div>

</div>

<!-- Daftar Pelatih -->
<div class="card border-0 shadow-sm">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Daftar Pelatih</h5>

            <span class="badge bg-primary">
                <?= count($coaches) ?> Pelatih
            </span>
        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th width="55">No.</th>
                        <th>Pelatih</th>
                        <th>Nomor HP</th>
                        <th>Spesialisasi</th>
                        <th>Alamat</th>
                        <th>Status</th>

                        <?php if ($canManage): ?>
                            <th width="150">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody>

                    <?php if (empty($coaches)): ?>

                        <tr>
                            <td colspan="<?= $canManage ? 7 : 6 ?>"
                                class="text-center text-muted py-5">

                                <i class="bi bi-people fs-2 d-block mb-2"></i>

                                Belum ada data pelatih.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($coaches as $index => $coach): ?>

                            <tr>

                                <td><?= $index + 1 ?></td>

                                <!-- Profil Pelatih -->
                                <td>
                                    <div class="d-flex align-items-center gap-3">

                                        <?php if (!empty($coach['photo'])): ?>

                                            <img
                                                src="<?= APP_URL . '/' . htmlspecialchars($coach['photo'], ENT_QUOTES, 'UTF-8') ?>"
                                                alt="Foto pelatih"
                                                width="45"
                                                height="45"
                                                class="rounded-circle"
                                                style="object-fit: cover;">

                                        <?php else: ?>

                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                                 style="width:45px;height:45px;">
                                                <i class="bi bi-person fs-4 text-secondary"></i>
                                            </div>

                                        <?php endif; ?>

                                        <div>
                                            <strong>
                                                <?= htmlspecialchars($coach['name'], ENT_QUOTES, 'UTF-8') ?>
                                            </strong>

                                            <div class="small text-muted">
                                                <?= !empty($coach['username'])
                                                    ? 'Username: ' . htmlspecialchars($coach['username'], ENT_QUOTES, 'UTF-8')
                                                    : 'Belum memiliki akun login' ?>
                                            </div>
                                        </div>

                                    </div>
                                </td>

                                <!-- Nomor HP -->
                                <td>
                                    <?= htmlspecialchars($coach['phone'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <!-- Spesialisasi -->
                                <td>
                                    <?= htmlspecialchars($coach['specialization'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <!-- Alamat -->
                                <td>
                                    <span title="<?= htmlspecialchars($coach['address'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                        <?= htmlspecialchars(
                                            $coach['address'] ?? '-',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </span>
                                </td>

                                <!-- Status -->
                                <td>
                                    <?php if ($coach['status'] === 'active'): ?>

                                        <span class="badge bg-success">
                                            Aktif
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-secondary">
                                            Tidak Aktif
                                        </span>

                                    <?php endif; ?>
                                </td>

                                <!-- Aksi -->
                                <?php if ($canManage): ?>

                                    <td>
                                        <div class="d-flex gap-2">

                                            <a href="<?= APP_URL ?>/coaches.php?action=edit&id=<?= (int) $coach['id'] ?>"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit pelatih">

                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <form method="POST"
                                                  action="<?= APP_URL ?>/coaches.php?action=status&id=<?= (int) $coach['id'] ?>"
                                                  onsubmit="return confirm('Yakin ingin mengubah status pelatih ini?')">

                                                <input type="hidden"
                                                       name="status"
                                                       value="<?= $coach['status'] === 'active' ? 'inactive' : 'active' ?>">

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-secondary"
                                                        title="Ubah status">

                                                    <i class="bi bi-arrow-repeat"></i>
                                                </button>

                                            </form>

                                            <form method="POST"
                                                  action="<?= APP_URL ?>/coaches.php?action=delete&id=<?= (int) $coach['id'] ?>"
                                                  onsubmit="return confirm('Yakin ingin menghapus data pelatih ini secara permanen?')">

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Hapus pelatih">

                                                    <i class="bi bi-trash"></i>
                                                </button>

                                            </form>

                                        </div>
                                    </td>

                                <?php endif; ?>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>
