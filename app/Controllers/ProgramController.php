<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/Models/Program.php';

class ProgramController
{
    private Program $programModel;

    public function __construct(PDO $pdo)
    {
        $this->programModel = new Program($pdo);
    }

    // Menampilkan seluruh program
    public function index(): array
    {
        return $this->programModel->getAll();
    }

    // Mengambil detail program
    public function show(int $id): ?array
    {
        return $this->programModel->findById($id);
    }

    // Menambahkan program
    public function store(array $data): array
    {
        $validated = $this->validate($data);

        if (!empty($validated['errors'])) {
            return [
                'success' => false,
                'errors' => $validated['errors']
            ];
        }

        $success = $this->programModel->create($validated['data']);

        return [
            'success' => $success,
            'message' => $success
                ? 'Program berhasil ditambahkan.'
                : 'Program gagal ditambahkan.'
        ];
    }

    // Memperbarui program
    public function update(int $id, array $data): array
    {
        if (!$this->programModel->findById($id)) {
            return [
                'success' => false,
                'message' => 'Program tidak ditemukan.'
            ];
        }

        $validated = $this->validate($data);

        if (!empty($validated['errors'])) {
            return [
                'success' => false,
                'errors' => $validated['errors']
            ];
        }

        $success = $this->programModel->update(
            $id,
            $validated['data']
        );

        return [
            'success' => $success,
            'message' => $success
                ? 'Program berhasil diperbarui.'
                : 'Program gagal diperbarui.'
        ];
    }

    // Mengubah status program
    public function changeStatus(int $id, string $status): array
    {
        if (!$this->programModel->findById($id)) {
            return [
                'success' => false,
                'message' => 'Program tidak ditemukan.'
            ];
        }

        if (!in_array($status, ['active', 'inactive'], true)) {
            return [
                'success' => false,
                'message' => 'Status program tidak valid.'
            ];
        }

        $success = $this->programModel->updateStatus($id, $status);

        return [
            'success' => $success,
            'message' => $success
                ? 'Status program berhasil diperbarui.'
                : 'Status program gagal diperbarui.'
        ];
    }

    // Validasi data program
    private function validate(array $data): array
    {
        $errors = [];

        $name = trim($data['name'] ?? '');
        $description = trim($data['description'] ?? '');
        $level = trim($data['level'] ?? '');
        $ageRange = trim($data['age_range'] ?? '');
        $status = $data['status'] ?? 'active';

        if ($name === '') {
            $errors['name'] = 'Nama program wajib diisi.';
        } elseif (mb_strlen($name) > 150) {
            $errors['name'] = 'Nama program maksimal 150 karakter.';
        }

        if (mb_strlen($level) > 100) {
            $errors['level'] = 'Level maksimal 100 karakter.';
        }

        if (mb_strlen($ageRange) > 100) {
            $errors['age_range'] = 'Rentang usia maksimal 100 karakter.';
        }

        if (!in_array($status, ['active', 'inactive'], true)) {
            $errors['status'] = 'Status program tidak valid.';
        }

        return [
            'data' => [
                'name' => $name,
                'description' => $description !== '' ? $description : null,
                'level' => $level !== '' ? $level : null,
                'age_range' => $ageRange !== '' ? $ageRange : null,
                'status' => $status
            ],
            'errors' => $errors
        ];
    }

    public function filter(array $filters): array
    {
        return $this->programModel->filter($filters);
    }

    public function delete(int $id): array
    {
        if (!$this->programModel->findById($id)) {
            return [
                'success' => false,
                'message' => 'Program tidak ditemukan.'
            ];
        }

        return $this->programModel->deleteIfUnused($id);
    }
}
