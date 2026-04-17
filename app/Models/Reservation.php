<?php

namespace App\Models;

class Reservation
{
    public int $id;
    public int $user_id;
    public string $reservation_date;
    public string $reservation_time;
    public int $guests;
    public ?string $allergies;
    public string $status;
    public string $created_at;

    // Propriétés jointes (pour affichage admin/client)
    public ?string $user_firstname = null;
    public ?string $user_lastname = null;
    public ?string $user_email = null;

    public static function fromArray(array $data): self
    {
        $r = new self();
        $r->id = (int) $data['id'];
        $r->user_id = (int) $data['user_id'];
        $r->reservation_date = $data['reservation_date'];
        $r->reservation_time = $data['reservation_time'];
        $r->guests = (int) $data['guests'];
        $r->allergies = $data['allergies'] ?? null;
        $r->status = $data['status'] ?? 'confirmed';
        $r->created_at = $data['created_at'] ?? '';
        $r->user_firstname = $data['user_firstname'] ?? null;
        $r->user_lastname = $data['user_lastname'] ?? null;
        $r->user_email = $data['user_email'] ?? null;
        return $r;
    }
}
