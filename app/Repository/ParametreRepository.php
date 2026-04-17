<?php

namespace App\Repository;

use App\Core\Database;
use PDO;

class ParametreRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getMaxCapacity(): int
    {
        $stmt = $this->db->query('SELECT max_capacity FROM restaurant_settings LIMIT 1');
        $row = $stmt->fetch();
        return $row ? (int) $row['max_capacity'] : 50;
    }

    public function updateMaxCapacity(int $capacity): bool
    {
        $stmt = $this->db->prepare('UPDATE restaurant_settings SET max_capacity = :capacity WHERE id = 1');
        return $stmt->execute(['capacity' => $capacity]);
    }
}
