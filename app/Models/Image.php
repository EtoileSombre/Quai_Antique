<?php

namespace App\Models;

class Image
{
    public int $id;
    public ?string $title;
    public string $image_path;
    public int $sort_order;
    public string $created_at;

    public static function fromArray(array $data): self
    {
        $img = new self();
        $img->id = (int) $data['id'];
        $img->title = $data['title'] ?? null;
        $img->image_path = $data['image_path'];
        $img->sort_order = (int) ($data['sort_order'] ?? 0);
        $img->created_at = $data['created_at'] ?? '';
        return $img;
    }
}
