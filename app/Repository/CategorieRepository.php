<?php

namespace App\Repository;

use App\Core\Database;
use App\Models\Categorie;
use PDO;

class CategorieRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM categories ORDER BY sort_order ASC, id ASC');
        $rows = $stmt->fetchAll();
        return array_map([Categorie::class, 'fromArray'], $rows);
    }

    public function findById(int $id): ?Categorie
    {
        $stmt = $this->db->prepare('SELECT * FROM categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        return $data ? Categorie::fromArray($data) : null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO categories (name, sort_order) VALUES (:name, :sort_order)'
        );
        $stmt->execute([
            'name' => $data['name'],
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE categories SET name = :name, sort_order = :sort_order WHERE id = :id'
        );
        return $stmt->execute([
            'name' => $data['name'],
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM categories WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
