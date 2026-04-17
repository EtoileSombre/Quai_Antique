<?php

namespace App\Models;

class Utilisateur
{
    public int $id;
    public string $email;
    public string $password;
    public string $firstname;
    public string $lastname;
    public ?string $phone;
    public int $default_guests;
    public ?string $allergies;
    public string $role;
    public ?string $created_at;
    public ?string $updated_at;

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public static function fromArray(array $data): self
    {
        $user = new self();
        $user->id = (int) $data['id'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];
        $user->phone = $data['phone'] ?? null;
        $user->default_guests = (int) ($data['default_guests'] ?? 1);
        $user->allergies = $data['allergies'] ?? null;
        $user->role = $data['role'];
        $user->created_at = $data['created_at'] ?? null;
        $user->updated_at = $data['updated_at'] ?? null;
        return $user;
    }
}
