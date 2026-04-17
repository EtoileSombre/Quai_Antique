<?php

namespace App\Repository;

use App\Core\Database;
use App\Models\Image;
use PDO;

class GalerieRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM gallery ORDER BY sort_order ASC, id DESC');
        return array_map([Image::class, 'fromArray'], $stmt->fetchAll());
    }

    public function findById(int $id): ?Image
    {
        $stmt = $this->db->prepare('SELECT * FROM gallery WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        return $data ? Image::fromArray($data) : null;
    }

    public function create(string $title, string $imagePath, int $sortOrder = 0): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO gallery (title, image_path, sort_order) VALUES (:title, :path, :sort)'
        );
        return $stmt->execute([
            'title' => $title,
            'path' => $imagePath,
            'sort' => $sortOrder,
        ]);
    }

    public function update(int $id, string $title, int $sortOrder): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE gallery SET title = :title, sort_order = :sort WHERE id = :id'
        );
        return $stmt->execute([
            'title' => $title,
            'sort' => $sortOrder,
            'id' => $id,
        ]);
    }

    public function updateImagePath(int $id, string $imagePath): bool
    {
        $stmt = $this->db->prepare('UPDATE gallery SET image_path = :path WHERE id = :id');
        return $stmt->execute(['path' => $imagePath, 'id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM gallery WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
