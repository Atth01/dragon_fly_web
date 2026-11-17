<?php

declare(strict_types=1);

/** @var array $coach */
$coach = $coach ?? [];

?>

<div class="mb-4">
    <h4 class="mb-1">Edit Pelatih</h4>
    <p class="text-muted mb-0">
        Perbarui informasi pelatih DragonFly Swimming Club.
    </p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <form action="<?= APP_URL ?>/coaches.php?action=update&id=<?= (int) $coach['id'] ?>"
              method="POST"
              enctype="multipart/form-data">

            <div class="row g-3">

                <!-- Nama -->
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap *</label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           value="<?= htmlspecialchars($coach['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                           maxlength="150"
                           placeholder="Masukkan nama pelatih"
                           required>
                </div>

                <!-- Nomor HP -->
                <div class="col-md-6">
                    <label class="form-label">Nomor HP</label>

                    <input type="text"
                           name="phone"
                           class="form-control"
                           value="<?= htmlspecialchars($coach['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                           placeholder="Contoh: 081234567890">
                </div>

                <!-- Spesialisasi -->
                <div class="col-md-6">
                    <label class="form-label">Spesialisasi</label>

                    <input type="text"
                           name="specialization"
                           class="form-control"
                           value="<?= htmlspecialchars($coach['specialization'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                           placeholder="Contoh: Renang Gaya Bebas">
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <label class="form-label">Status</label>

                    <select name="status" class="form-select">

                        <option value="active"
                            <?= ($coach['status'] ?? '') === 'active' ? 'selected' : '' ?>>
                            Aktif
                        </option>

                        <option value="inactive"
                            <?= ($coach['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>
                            Tidak Aktif
                        </option>

                    </select>
                </div>

                <!-- Alamat -->
                <div class="col-12">
                    <label class="form-label">Alamat</label>

                    <textarea name="address"
                              class="form-control"
                              rows="3"
                              placeholder="Masukkan alamat pelatih"><?= htmlspecialchars($coach['address'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <!-- Foto -->
                <div class="col-12">

                    <label class="form-label">Foto Pelatih</label>

                    <div class="mb-3">

                        <?php if (!empty($coach['photo'])): ?>

                            <img src="<?= APP_URL . '/' . htmlspecialchars($coach['photo'], ENT_QUOTES, 'UTF-8') ?>"
                                 alt="Foto pelatih"
                                 width="110"
                                 height="110"
                                 class="rounded-3 border"
                                 style="object-fit: cover;">

                        <?php else: ?>

                            <div class="rounded-3 bg-light d-flex align-items-center justify-content-center border"
                                 style="width:110px;height:110px;">
                                <i class="bi bi-person fs-1 text-secondary"></i>
                            </div>

                        <?php endif; ?>

                    </div>

                    <input type="file"
                           name="photo"
                           class="form-control"
                           accept="image/jpeg,image/png,image/webp">

                    <small class="text-muted">
                        Kosongkan jika tidak ingin mengganti foto.
                        Format JPG, PNG, WEBP. Maksimal 2 MB.
                    </small>

                </div>

            </div>

            <hr class="my-4">

            <div class="d-flex gap-2">

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>
                    Simpan Perubahan
                </button>

                <a href="<?= APP_URL ?>/coaches.php"
                   class="btn btn-light border">
                    Batal
                </a>

            </div>

        </form>

    </div>
</div>
