<?php

namespace App\Models;

class Plat
{
    public int $id;
    public int $category_id;
    public string $title;
    public ?string $description;
    public float $price;
    public ?string $created_at;

    // Champ jointure
    public ?string $category_name = null;

    public static function fromArray(array $data): self
    {
        $plat = new self();
        $plat->id = (int) $data['id'];
        $plat->category_id = (int) $data['category_id'];
        $plat->title = $data['title'];
        $plat->description = $data['description'] ?? null;
        $plat->price = (float) $data['price'];
        $plat->created_at = $data['created_at'] ?? null;
        $plat->category_name = $data['category_name'] ?? null;
        return $plat;
    }
}
