<?php

declare(strict_types=1);

?>

<div class="mb-4">
    <h4 class="mb-1">Tambah Pelatih</h4>
    <p class="text-muted mb-0">
        Tambahkan informasi pelatih DragonFly Swimming Club.
    </p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <form action="<?= APP_URL ?>/coaches.php?action=store"
              method="POST"
              enctype="multipart/form-data">

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           placeholder="Masukkan nama pelatih"
                           maxlength="150"
                           required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nomor HP</label>
                    <input type="text"
                           name="phone"
                           class="form-control"
                           placeholder="Contoh: 081234567890">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Spesialisasi</label>
                    <input type="text"
                           name="specialization"
                           class="form-control"
                           placeholder="Contoh: Renang Gaya Bebas">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="address"
                              class="form-control"
                              rows="3"
                              placeholder="Masukkan alamat pelatih"></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Foto Pelatih</label>
                    <input type="file"
                           name="photo"
                           class="form-control"
                           accept="image/jpeg,image/png,image/webp">

                    <small class="text-muted">
                        Format JPG, PNG, WEBP. Maksimal 2 MB.
                    </small>
                </div>

            </div>

            <hr class="my-4">

            <div class="d-flex gap-2">

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>
                    Simpan Pelatih
                </button>

                <a href="<?= APP_URL ?>/coaches.php"
                   class="btn btn-light border">
                    Batal
                </a>

            </div>

        </form>

    </div>
</div>
