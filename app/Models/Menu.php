<?php

namespace App\Models;

class Menu
{
    public int $id;
    public string $title;
    public ?string $description;
    public float $price;
    public ?string $created_at;

    public static function fromArray(array $data): self
    {
        $menu = new self();
        $menu->id = (int) $data['id'];
        $menu->title = $data['title'];
        $menu->description = $data['description'] ?? null;
        $menu->price = (float) $data['price'];
        $menu->created_at = $data['created_at'] ?? null;
        return $menu;
    }
}
