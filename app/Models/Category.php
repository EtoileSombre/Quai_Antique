<?php

namespace App\Models;

class Category
{
    public int $id;
    public string $name;
    public int $sort_order;

    public static function fromArray(array $data): self
    {
        $cat = new self();
        $cat->id = (int) $data['id'];
        $cat->name = $data['name'];
        $cat->sort_order = (int) $data['sort_order'];
        return $cat;
    }
}
