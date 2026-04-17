<?php

namespace App\Repository;

use App\Core\Database;
use App\Models\Utilisateur;
use PDO;

class UtilisateurRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findByEmail(string $email): ?Utilisateur
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch();

        return $data ? Utilisateur::fromArray($data) : null;
    }

    public function findById(int $id): ?Utilisateur
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        return $data ? Utilisateur::fromArray($data) : null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (email, password, firstname, lastname, phone, default_guests, allergies, role)
             VALUES (:email, :password, :firstname, :lastname, :phone, :default_guests, :allergies, :role)'
        );

        $stmt->execute([
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'phone' => $data['phone'] ?? null,
            'default_guests' => (int) ($data['default_guests'] ?? 1),
            'allergies' => $data['allergies'] ?? null,
            'role' => 'client',
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function emailExistsForOther(string $email, int $excludeId): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = :email AND id != :id');
        $stmt->execute(['email' => $email, 'id' => $excludeId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function updateProfile(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET firstname = :firstname, lastname = :lastname,
                    email = :email, phone = :phone,
                    default_guests = :default_guests, allergies = :allergies
             WHERE id = :id'
        );
        return $stmt->execute([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'default_guests' => (int) ($data['default_guests'] ?? 1),
            'allergies' => $data['allergies'] ?? null,
            'id' => $id,
        ]);
    }

    public function updatePassword(int $id, string $password): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET password = :password WHERE id = :id');
        return $stmt->execute([
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
