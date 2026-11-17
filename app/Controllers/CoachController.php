<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/Models/Coach.php';

class CoachController
{
    private Coach $coachModel;

    public function __construct(PDO $db)
    {
        $this->coachModel = new Coach($db);
    }

    /**
     * Menampilkan seluruh data pelatih.
     */
    public function index(): array
    {
        return $this->coachModel->getAll();
    }

    /**
     * Mengambil detail pelatih.
     */
    public function show(int $id): ?array
    {
        return $this->coachModel->findById($id);
    }

    /**
     * Menambahkan pelatih baru.
     */
    public function store(array $data, array $file = []): array
    {
        $validation = $this->validate($data);

        if (!$validation['success']) {
            return $validation;
        }

        $photo = null;

        if (!empty($file) && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $upload = $this->uploadPhoto($file);

            if (!$upload['success']) {
                return $upload;
            }

            $photo = $upload['path'];
        }

        $result = $this->coachModel->create([
            'name' => trim($data['name']),
            'phone' => trim($data['phone'] ?? ''),
            'address' => trim($data['address'] ?? ''),
            'specialization' => trim($data['specialization'] ?? ''),
            'photo' => $photo,
            'status' => $data['status'] ?? 'active',
        ]);

        return [
            'success' => $result,
            'message' => $result
                ? 'Data pelatih berhasil ditambahkan.'
                : 'Data pelatih gagal ditambahkan.'
        ];
    }

    /**
     * Memperbarui data pelatih.
     */
    public function update(int $id, array $data, array $file = []): array
    {
        $coach = $this->coachModel->findById($id);

        if (!$coach) {
            return [
                'success' => false,
                'message' => 'Data pelatih tidak ditemukan.'
            ];
        }

        $validation = $this->validate($data);

        if (!$validation['success']) {
            return $validation;
        }

        // Pertahankan foto lama jika tidak ada foto baru.
        $photo = $coach['photo'];

        if (!empty($file) && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $upload = $this->uploadPhoto($file);

            if (!$upload['success']) {
                return $upload;
            }

            $photo = $upload['path'];
        }

        $result = $this->coachModel->update($id, [
            'name' => trim($data['name']),
            'phone' => trim($data['phone'] ?? ''),
            'address' => trim($data['address'] ?? ''),
            'specialization' => trim($data['specialization'] ?? ''),
            'photo' => $photo,
            'status' => $data['status'] ?? $coach['status'],
        ]);

        if ($result && $photo !== $coach['photo']) {
            $this->deletePhotoFile($coach['photo']);
        }

        return [
            'success' => $result,
            'message' => $result
                ? 'Data pelatih berhasil diperbarui.'
                : 'Data pelatih gagal diperbarui.'
        ];
    }

    /**
     * Mengubah status pelatih.
     */
    public function changeStatus(int $id, string $status): array
    {
        if (!in_array($status, ['active', 'inactive'], true)) {
            return [
                'success' => false,
                'message' => 'Status pelatih tidak valid.'
            ];
        }

        if (!$this->coachModel->findById($id)) {
            return [
                'success' => false,
                'message' => 'Data pelatih tidak ditemukan.'
            ];
        }

        $result = $this->coachModel->updateStatus($id, $status);

        return [
            'success' => $result,
            'message' => $result
                ? 'Status pelatih berhasil diperbarui.'
                : 'Status pelatih gagal diperbarui.'
        ];
    }

    /**
     * Menghapus pelatih.
     */
    public function delete(int $id): array
    {
        $coach = $this->coachModel->findById($id);

        if (!$coach) {
            return [
                'success' => false,
                'message' => 'Data pelatih tidak ditemukan.'
            ];
        }

        $result = $this->coachModel->deleteIfUnused($id);

        if ($result['success']) {
            $this->deletePhotoFile($coach['photo']);
        }

        return $result;
    }

    /**
     * Validasi input pelatih.
     */
    private function validate(array $data): array
    {
        $name = trim($data['name'] ?? '');

        if ($name === '') {
            return [
                'success' => false,
                'message' => 'Nama pelatih wajib diisi.'
            ];
        }

        if (mb_strlen($name) > 150) {
            return [
                'success' => false,
                'message' => 'Nama pelatih maksimal 150 karakter.'
            ];
        }

        if (!in_array($data['status'] ?? 'active', ['active', 'inactive'], true)) {
            return [
                'success' => false,
                'message' => 'Status pelatih tidak valid.'
            ];
        }

        return ['success' => true];
    }

    /**
     * Upload foto pelatih.
     */
    private function uploadPhoto(array $file): array
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengunggah foto.'
            ];
        }

        if (($file['size'] ?? 0) > 2 * 1024 * 1024) {
            return [
                'success' => false,
                'message' => 'Ukuran foto maksimal 2 MB.'
            ];
        }

        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        $mimeType = mime_content_type($file['tmp_name']);

        if (!isset($allowedTypes[$mimeType])) {
            return [
                'success' => false,
                'message' => 'Format foto harus JPG, PNG, atau WEBP.'
            ];
        }

        $uploadDirectory = APP_ROOT . '/public/assets/uploads/coaches';

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        $fileName = bin2hex(random_bytes(16)) . '.' . $allowedTypes[$mimeType];
        $destination = $uploadDirectory . '/' . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return [
                'success' => false,
                'message' => 'Foto gagal disimpan ke server.'
            ];
        }

        return [
            'success' => true,
            'path' => 'assets/uploads/coaches/' . $fileName,
            'message' => 'Foto berhasil diunggah.'
        ];
    }

    /**
     * Menghapus file foto lama.
     */
    private function deletePhotoFile(?string $photo): void
    {
        if (!$photo) {
            return;
        }

        // Hanya izinkan penghapusan dari folder foto pelatih.
        $prefix = 'assets/uploads/coaches/';

        if (!str_starts_with($photo, $prefix)) {
            return;
        }

        $filePath = APP_ROOT . '/public/' . $photo;

        if (is_file($filePath)) {
            unlink($filePath);
        }
    }
}