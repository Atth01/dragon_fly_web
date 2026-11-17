<?php

/** @var array $programs */
$programs = $programs ?? [];

$canManage = in_array(
    currentUser()['role'] ?? '',
    ['admin', 'management'],
    true
);

$message = $_GET['message'] ?? '';

$notifications = [
    'created' => 'Program berhasil ditambahkan.',
    'updated' => 'Program berhasil diperbarui.',
    'status_updated' => 'Status program berhasil diperbarui.',
    'deleted' => 'Program berhasil dihapus secara permanen.',
];

?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

    <div>
        <h4 class="mb-1">Manajemen Program Latihan</h4>
        <p class="text-muted mb-0">
            Kelola program latihan DragonFly Swimming Club.
        </p>
    </div>

    <?php if ($canManage): ?>
        <a href="<?= APP_URL ?>/programs.php?action=create"
           class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Program
        </a>
    <?php endif; ?>

</div>

<?php if (isset($notifications[$message])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= htmlspecialchars($notifications[$message]) ?>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>
    </div>
<?php elseif ($message === 'error'): ?>
    <div class="alert alert-danger">
        Gagal memperbarui status program.
    </div>

<?php elseif ($message === 'delete_error'): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= htmlspecialchars(
            $_GET['detail'] ?? 'Program gagal dihapus.',
            ENT_QUOTES,
            'UTF-8'
        ) ?>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="GET" action="<?= APP_URL ?>/programs.php"
      class="card border-0 shadow-sm mb-4">

    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-3">
                <label class="form-label">Cari Program</label>
                <input type="text"
                       name="search"
                       class="form-control"
                       placeholder="Nama program..."
                       value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
            </div>

            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Aktif</option>
                    <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Level</label>
                <select name="level" class="form-select">
                    <option value="">Semua Level</option>
                    <option value="Beginner" <?= ($filters['level'] ?? '') === 'Beginner' ? 'selected' : '' ?>>Beginner</option>
                    <option value="Intermediate" <?= ($filters['level'] ?? '') === 'Intermediate' ? 'selected' : '' ?>>Intermediate</option>
                    <option value="Advanced" <?= ($filters['level'] ?? '') === 'Advanced' ? 'selected' : '' ?>>Advanced</option>
                    <option value="All Levels" <?= ($filters['level'] ?? '') === 'All Levels' ? 'selected' : '' ?>>All Levels</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Rentang Usia</label>
                <select name="age_range" class="form-select">
                    <option value="">Semua Usia</option>
                    <option value="3-5 tahun" <?= ($filters['age_range'] ?? '') === '3-5 tahun' ? 'selected' : '' ?>>3–5 tahun</option>
                    <option value="6-8 tahun" <?= ($filters['age_range'] ?? '') === '6-8 tahun' ? 'selected' : '' ?>>6–8 tahun</option>
                    <option value="9-12 tahun" <?= ($filters['age_range'] ?? '') === '9-12 tahun' ? 'selected' : '' ?>>9–12 tahun</option>
                    <option value="13-17 tahun" <?= ($filters['age_range'] ?? '') === '13-17 tahun' ? 'selected' : '' ?>>13–17 tahun</option>
                    <option value="18 tahun ke atas" <?= ($filters['age_range'] ?? '') === '18 tahun ke atas' ? 'selected' : '' ?>>18 tahun ke atas</option>
                    <option value="Semua Usia" <?= ($filters['age_range'] ?? '') === 'Semua Usia' ? 'selected' : '' ?>>Semua Usia</option>
                </select>
            </div>

        </div>

        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel me-1"></i> Terapkan Filter
            </button>

            <a href="<?= APP_URL ?>/programs.php"
               class="btn btn-light border">
                Reset
            </a>
        </div>
    </div>
</form>

<div class="card border-0 shadow-sm">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Daftar Program</h5>
            <span class="badge bg-primary">
                <?= count($programs) ?> Program
            </span>
        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th width="55">No.</th>
                        <th>Nama Program</th>
                        <th>Level</th>
                        <th>Rentang Usia</th>
                        <th>Status</th>

                        <?php if ($canManage): ?>
                            <th width="180">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody>

                    <?php if (empty($programs)): ?>

                        <tr>
                            <td colspan="<?= $canManage ? 6 : 5 ?>"
                                class="text-center text-muted py-5">
                                <i class="bi bi-water fs-2 d-block mb-2"></i>
                                Belum ada program latihan.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($programs as $index => $program): ?>

                            <tr>

                                <td><?= $index + 1 ?></td>

                                <td>
                                    <strong>
                                        <?= htmlspecialchars($program['name']) ?>
                                    </strong>

                                    <div class="small text-muted">
                                        <?= htmlspecialchars(
                                            $program['description'] ?? ''
                                        ) ?>
                                    </div>
                                </td>

                                <td>
                                    <?= htmlspecialchars($program['level'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($program['age_range'] ?? '-') ?>
                                </td>

                                <td>
                                    <?php if ($program['status'] === 'active'): ?>
                                        <span class="badge bg-success">
                                            Aktif
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">
                                            Tidak Aktif
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <?php if ($canManage): ?>
                                    <td>
                                        <div class="d-flex gap-2">

                                            <a href="<?= APP_URL ?>/programs.php?action=edit&id=<?= (int) $program['id'] ?>"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit program">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <form method="POST"
                                                  action="<?= APP_URL ?>/programs.php?action=status&id=<?= (int) $program['id'] ?>"
                                                  onsubmit="return confirm('Yakin ingin mengubah status program ini?')">

                                                <input type="hidden"
                                                       name="status"
                                                       value="<?= $program['status'] === 'active' ? 'inactive' : 'active' ?>">

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-secondary"
                                                        title="Ubah status">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </button>

                                            </form>

                                            <form method="POST"
                                                action="<?= APP_URL ?>/programs.php?action=delete&id=<?= (int) $program['id'] ?>"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus program ini secara permanen?')">

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Hapus permanen">
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