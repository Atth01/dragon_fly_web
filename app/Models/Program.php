<?php

declare(strict_types=1);

class Program
{
    private PDO $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    // Mengambil seluruh program
    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT *
            FROM programs
            ORDER BY created_at DESC
        ");

        return $stmt->fetchAll();
    }

    // Mengambil program berdasarkan ID
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM programs
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute(['id' => $id]);

        $program = $stmt->fetch();

        return $program ?: null;
    }

    // Menambahkan program
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO programs (
                name,
                description,
                level,
                age_range,
                status
            ) VALUES (
                :name,
                :description,
                :level,
                :age_range,
                :status
            )
        ");

        return $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'],
            'level' => $data['level'],
            'age_range' => $data['age_range'],
            'status' => $data['status']
        ]);
    }

    // Memperbarui program
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE programs
            SET
                name = :name,
                description = :description,
                level = :level,
                age_range = :age_range,
                status = :status
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'],
            'level' => $data['level'],
            'age_range' => $data['age_range'],
            'status' => $data['status']
        ]);
    }

    // Mengubah status program
    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare("
            UPDATE programs
            SET status = :status
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'status' => $status
        ]);
    }


    // Filter dan pencarian program
    public function filter(array $filters = []): array
    {
        $sql = "SELECT * FROM programs WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND name LIKE :search";
            $params['search'] = '%' . $filters['search'] . '%';
        }

        if (
            !empty($filters['status']) &&
            in_array($filters['status'], ['active', 'inactive'], true)
        ) {
            $sql .= " AND status = :status";
            $params['status'] = $filters['status'];
        }

        if (
            !empty($filters['level']) &&
            in_array($filters['level'], [
                'Beginner',
                'Intermediate',
                'Advanced',
                'All Levels'
            ], true)
        ) {
            $sql .= " AND level = :level";
            $params['level'] = $filters['level'];
        }

        if (!empty($filters['age_range'])) {
            $sql .= " AND age_range = :age_range";
            $params['age_range'] = $filters['age_range'];
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    // Menghapus program jika belum memiliki data terkait
    public function deleteIfUnused(int $id): array
    {
        $relations = [
            'registrations' => 'program_id',
            'members' => 'program_id',
            'schedules' => 'program_id',
        ];

        foreach ($relations as $table => $column) {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM {$table} WHERE {$column} = :id"
            );

            $stmt->execute(['id' => $id]);

            if ((int) $stmt->fetchColumn() > 0) {
                return [
                    'success' => false,
                    'message' => 'Program tidak dapat dihapus karena sudah digunakan pada data ' . $table . '.'
                ];
            }
        }

        try {
            $stmt = $this->db->prepare("
                DELETE FROM programs
                WHERE id = :id
            ");

            $stmt->execute(['id' => $id]);

            if ($stmt->rowCount() === 0) {
                return [
                    'success' => false,
                    'message' => 'Program tidak ditemukan.'
                ];
            }

            return [
                'success' => true,
                'message' => 'Program berhasil dihapus permanen.'
            ];

        } catch (PDOException $e) {
            // Foreign key tetap menjadi perlindungan terakhir
            return [
                'success' => false,
                'message' => 'Program tidak dapat dihapus karena masih memiliki data terkait.'
            ];
        }
    }

}
