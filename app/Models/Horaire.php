<?php

namespace App\Models;

class Horaire
{
    public int $id;
    public int $day_of_week;
    public ?string $lunch_start;
    public ?string $lunch_end;
    public ?string $dinner_start;
    public ?string $dinner_end;
    public bool $is_closed;

    private const DAY_NAMES = [
        1 => 'Lundi',
        2 => 'Mardi',
        3 => 'Mercredi',
        4 => 'Jeudi',
        5 => 'Vendredi',
        6 => 'Samedi',
        7 => 'Dimanche',
    ];

    public function getDayName(): string
    {
        return self::DAY_NAMES[$this->day_of_week] ?? '';
    }

    public static function fromArray(array $data): self
    {
        $oh = new self();
        $oh->id = (int) $data['id'];
        $oh->day_of_week = (int) $data['day_of_week'];
        $oh->lunch_start = $data['lunch_start'] ?? null;
        $oh->lunch_end = $data['lunch_end'] ?? null;
        $oh->dinner_start = $data['dinner_start'] ?? null;
        $oh->dinner_end = $data['dinner_end'] ?? null;
        $oh->is_closed = (bool) ($data['is_closed'] ?? false);
        return $oh;
    }
}
