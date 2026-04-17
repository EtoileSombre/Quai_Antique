<?php

namespace App\Repository;

use App\Core\Database;
use App\Models\Horaire;
use PDO;

class HoraireRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM opening_hours ORDER BY day_of_week ASC');
        $rows = $stmt->fetchAll();
        return array_map([Horaire::class, 'fromArray'], $rows);
    }

    public function findByDay(int $dayOfWeek): ?Horaire
    {
        $stmt = $this->db->prepare('SELECT * FROM opening_hours WHERE day_of_week = :day');
        $stmt->execute(['day' => $dayOfWeek]);
        $data = $stmt->fetch();
        return $data ? Horaire::fromArray($data) : null;
    }

    public function findOpenDays(): array
    {
        $stmt = $this->db->query('SELECT * FROM opening_hours WHERE is_closed = 0 ORDER BY day_of_week ASC');
        $rows = $stmt->fetchAll();
        return array_map([OpeningHour::class, 'fromArray'], $rows);
    }

    public function updateHours(int $dayOfWeek, ?string $lunchStart, ?string $dinnerStart): bool
    {
        // Chaque service dure 2h (CDC)
        $lunchEnd = $lunchStart ? date('H:i', strtotime($lunchStart . ' +2 hours')) : null;
        $dinnerEnd = $dinnerStart ? date('H:i', strtotime($dinnerStart . ' +2 hours')) : null;

        $stmt = $this->db->prepare(
            'UPDATE opening_hours
             SET lunch_start = :lunch_start, lunch_end = :lunch_end,
                 dinner_start = :dinner_start, dinner_end = :dinner_end
             WHERE day_of_week = :day'
        );
        return $stmt->execute([
            'lunch_start' => $lunchStart,
            'lunch_end' => $lunchEnd,
            'dinner_start' => $dinnerStart,
            'dinner_end' => $dinnerEnd,
            'day' => $dayOfWeek,
        ]);
    }
}
