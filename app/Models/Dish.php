<?php

namespace App\Models;

class Dish
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
        $dish = new self();
        $dish->id = (int) $data['id'];
        $dish->category_id = (int) $data['category_id'];
        $dish->title = $data['title'];
        $dish->description = $data['description'] ?? null;
        $dish->price = (float) $data['price'];
        $dish->created_at = $data['created_at'] ?? null;
        $dish->category_name = $data['category_name'] ?? null;
        return $dish;
    }
}
