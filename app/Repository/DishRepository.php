<?php

namespace App\Repository;

use App\Core\Database;
use App\Models\Dish;
use PDO;

class DishRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query(
            'SELECT d.*, c.name AS category_name
             FROM dishes d
             JOIN categories c ON d.category_id = c.id
             ORDER BY c.sort_order ASC, d.title ASC'
        );
        $rows = $stmt->fetchAll();
        return array_map([Dish::class, 'fromArray'], $rows);
    }

    public function findGroupedByCategory(): array
    {
        $stmt = $this->db->query(
            'SELECT d.*, c.name AS category_name, c.sort_order AS cat_sort
             FROM dishes d
             JOIN categories c ON d.category_id = c.id
             ORDER BY c.sort_order ASC, d.title ASC'
        );
        $rows = $stmt->fetchAll();

        $grouped = [];
        foreach ($rows as $row) {
            $catName = $row['category_name'];
            if (!isset($grouped[$catName])) {
                $grouped[$catName] = [];
            }
            $grouped[$catName][] = Dish::fromArray($row);
        }
        return $grouped;
    }

    public function findById(int $id): ?Dish
    {
        $stmt = $this->db->prepare(
            'SELECT d.*, c.name AS category_name
             FROM dishes d
             JOIN categories c ON d.category_id = c.id
             WHERE d.id = :id'
        );
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        return $data ? Dish::fromArray($data) : null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO dishes (category_id, title, description, price)
             VALUES (:category_id, :title, :description, :price)'
        );
        $stmt->execute([
            'category_id' => (int) $data['category_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'price' => (float) $data['price'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE dishes SET category_id = :category_id, title = :title,
             description = :description, price = :price WHERE id = :id'
        );
        return $stmt->execute([
            'category_id' => (int) $data['category_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'price' => (float) $data['price'],
            'id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM dishes WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
