<?php
$old = $_POST ?? [];

function oldValue(string $key, array $old): string
{
    return htmlspecialchars($old[$key] ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<div class="mb-4">
    <h4>Tambah Program Latihan</h4>
    <p class="text-muted">
        Masukkan informasi program latihan baru.
    </p>
</div>

<div class="card border-0 shadow-sm">

    <div class="card-body p-4">

        <form method="POST"
              action="<?= APP_URL ?>/programs.php?action=store">

            <div class="mb-3">
                <label class="form-label">
                    Nama Program <span class="text-danger">*</span>
                </label>

                <input type="text"
                       name="name"
                       class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                       value="<?= oldValue('name', $old) ?>"
                       maxlength="150"
                       required>

                <?php if (isset($errors['name'])): ?>
                    <div class="invalid-feedback">
                        <?= htmlspecialchars($errors['name']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi Program</label>

                <textarea name="description"
                          class="form-control"
                          rows="4"><?= oldValue('description', $old) ?></textarea>
            </div>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Level Program</label>

                    <select name="level" class="form-select">
                        <option value="">Pilih Level</option>

                        <?php
                        $levels = [
                            'Beginner' => 'Beginner (Pemula)',
                            'Intermediate' => 'Intermediate (Menengah)',
                            'Advanced' => 'Advanced (Lanjutan)',
                            'All Levels' => 'All Levels (Semua Tingkatan)'
                        ];
                        ?>

                        <?php foreach ($levels as $value => $label): ?>
                            <option value="<?= htmlspecialchars($value) ?>"
                                <?= ($old['level'] ?? '') === $value ? 'selected' : '' ?>>
                                <?= htmlspecialchars($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Rentang Usia</label>

                    <select name="age_range" class="form-select">
                        <option value="">Pilih Rentang Usia</option>

                        <?php
                        $ageRanges = [
                            '3-5 tahun',
                            '6-8 tahun',
                            '9-12 tahun',
                            '13-17 tahun',
                            '18 tahun ke atas',
                            'Semua Usia'
                        ];
                        ?>

                        <?php foreach ($ageRanges as $age): ?>
                            <option value="<?= htmlspecialchars($age) ?>"
                                <?= ($old['age_range'] ?? '') === $age ? 'selected' : '' ?>>
                                <?= htmlspecialchars($age) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

            <div class="mb-4">
                <label class="form-label">Status Program</label>

                <select name="status" class="form-select">
                    <option value="active"
                        <?= ($old['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>
                        Aktif
                    </option>

                    <option value="inactive"
                        <?= ($old['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>
                        Tidak Aktif
                    </option>
                </select>
            </div>

            <div class="d-flex gap-2">

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>
                    Simpan Program
                </button>

                <a href="<?= APP_URL ?>/programs.php"
                   class="btn btn-light border">
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>