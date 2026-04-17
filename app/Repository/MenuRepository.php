<?php

namespace App\Repository;

use App\Core\Database;
use App\Models\Menu;
use PDO;

class MenuRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM menus ORDER BY price ASC');
        $rows = $stmt->fetchAll();
        return array_map([Menu::class, 'fromArray'], $rows);
    }

    public function findById(int $id): ?Menu
    {
        $stmt = $this->db->prepare('SELECT * FROM menus WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        return $data ? Menu::fromArray($data) : null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO menus (title, description, price) VALUES (:title, :description, :price)'
        );
        $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'price' => (float) $data['price'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE menus SET title = :title, description = :description, price = :price WHERE id = :id'
        );
        return $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'price' => (float) $data['price'],
            'id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM menus WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
