<?php

declare(strict_types=1);

class Coach
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Mengambil seluruh data pelatih.
     */
    public function getAll(): array
    {
        $sql = "
            SELECT
                c.*,
                u.username,
                u.email,
                u.status AS account_status
            FROM coaches c
            LEFT JOIN users u ON c.user_id = u.id
            ORDER BY c.created_at DESC
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil data pelatih berdasarkan ID.
     */
    public function findById(int $id): ?array
    {
        $sql = "
            SELECT
                c.*,
                u.username,
                u.email,
                u.status AS account_status
            FROM coaches c
            LEFT JOIN users u ON c.user_id = u.id
            WHERE c.id = :id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        $coach = $stmt->fetch(PDO::FETCH_ASSOC);

        return $coach ?: null;
    }

    /**
     * Menambahkan data pelatih.
     */
    public function create(array $data): bool
    {
        $sql = "
            INSERT INTO coaches (
                user_id,
                name,
                phone,
                address,
                specialization,
                photo,
                status
            ) VALUES (
                :user_id,
                :name,
                :phone,
                :address,
                :specialization,
                :photo,
                :status
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'user_id' => $data['user_id'] ?? null,
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'specialization' => $data['specialization'] ?? null,
            'photo' => $data['photo'] ?? null,
            'status' => $data['status'] ?? 'active',
        ]);
    }

    /**
     * Memperbarui data pelatih.
     */
    public function update(int $id, array $data): bool
    {
        $sql = "
            UPDATE coaches
            SET
                name = :name,
                phone = :phone,
                address = :address,
                specialization = :specialization,
                photo = :photo,
                status = :status,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'specialization' => $data['specialization'] ?? null,
            'photo' => $data['photo'] ?? null,
            'status' => $data['status'] ?? 'active',
        ]);
    }

    /**
     * Mengubah status pelatih.
     */
    public function updateStatus(int $id, string $status): bool
    {
        if (!in_array($status, ['active', 'inactive'], true)) {
            return false;
        }

        $sql = "
            UPDATE coaches
            SET
                status = :status,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'status' => $status,
        ]);
    }

    /**
     * Menghapus pelatih jika belum memiliki penugasan.
     */
    public function deleteIfUnused(int $id): array
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*)
            FROM coach_assignments
            WHERE coach_id = :id
        ");

        $stmt->execute(['id' => $id]);

        if ((int) $stmt->fetchColumn() > 0) {
            return [
                'success' => false,
                'message' => 'Pelatih tidak dapat dihapus karena sudah memiliki riwayat penugasan.'
            ];
        }

        try {
            $stmt = $this->db->prepare("
                DELETE FROM coaches
                WHERE id = :id
            ");

            $stmt->execute(['id' => $id]);

            if ($stmt->rowCount() === 0) {
                return [
                    'success' => false,
                    'message' => 'Data pelatih tidak ditemukan.'
                ];
            }

            return [
                'success' => true,
                'message' => 'Data pelatih berhasil dihapus.'
            ];

        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Pelatih tidak dapat dihapus karena masih terhubung dengan data lain.'
            ];
        }
    }
}