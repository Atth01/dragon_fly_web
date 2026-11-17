<?php

/** @var array $program */
$program = $program ?? [];

$old = $_POST ?? [];

function editValue(string $key, array $program, array $old): string
{
    $value = array_key_exists($key, $old)
        ? $old[$key]
        : ($program[$key] ?? '');

    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}
?>

<div class="mb-4">
    <h4>Edit Program Latihan</h4>
    <p class="text-muted">
        Perbarui informasi program latihan.
    </p>
</div>

<div class="card border-0 shadow-sm">

    <div class="card-body p-4">

        <form method="POST"
              action="<?= APP_URL ?>/programs.php?action=update&id=<?= (int) $program['id'] ?>">

            <div class="mb-3">
                <label class="form-label">
                    Nama Program <span class="text-danger">*</span>
                </label>

                <input type="text"
                       name="name"
                       class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                       value="<?= editValue('name', $program, $old) ?>"
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
                          rows="4"><?= editValue('description', $program, $old) ?></textarea>
            </div>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Level Program</label>

                    <select name="level" class="form-select">
                        <?php
                        $levels = [
                            'Beginner' => 'Beginner (Pemula)',
                            'Intermediate' => 'Intermediate (Menengah)',
                            'Advanced' => 'Advanced (Lanjutan)',
                            'All Levels' => 'All Levels (Semua Tingkatan)'
                        ];

                        $selectedLevel = $old['level'] ?? $program['level'];
                        ?>

                        <option value="">Pilih Level</option>

                        <?php foreach ($levels as $value => $label): ?>
                            <option value="<?= htmlspecialchars($value) ?>"
                                <?= $selectedLevel === $value ? 'selected' : '' ?>>
                                <?= htmlspecialchars($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Rentang Usia</label>

                    <select name="age_range" class="form-select">
                            <?php
                            $ageRanges = [
                                '3-5 tahun',
                                '6-8 tahun',
                                '9-12 tahun',
                                '13-17 tahun',
                                '18 tahun ke atas',
                                'Semua Usia'
                            ];

                            $selectedAge = $old['age_range'] ?? $program['age_range'];
                            ?>

                            <option value="">Pilih Rentang Usia</option>

                            <?php foreach ($ageRanges as $age): ?>
                                <option value="<?= htmlspecialchars($age) ?>"
                                    <?= $selectedAge === $age ? 'selected' : '' ?>>
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
                        <?= ($old['status'] ?? $program['status']) === 'active' ? 'selected' : '' ?>>
                        Aktif
                    </option>

                    <option value="inactive"
                        <?= ($old['status'] ?? $program['status']) === 'inactive' ? 'selected' : '' ?>>
                        Tidak Aktif
                    </option>
                </select>
            </div>

            <div class="d-flex gap-2">

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>
                    Simpan Perubahan
                </button>

                <a href="<?= APP_URL ?>/programs.php"
                   class="btn btn-light border">
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>