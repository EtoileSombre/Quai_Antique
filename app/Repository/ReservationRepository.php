<?php

namespace App\Repository;

use App\Core\Database;
use App\Models\Reservation;
use PDO;

class ReservationRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Nombre total de convives pour un service donné (date + plage horaire du service).
     */
    public function countGuestsForService(string $date, string $serviceStart, string $serviceEnd): int
    {
        $stmt = $this->db->prepare(
            'SELECT COALESCE(SUM(guests), 0) AS total
             FROM reservations
             WHERE reservation_date = :date
               AND reservation_time >= :start
               AND reservation_time <= :end
               AND status = "confirmed"'
        );
        $stmt->execute([
            'date' => $date,
            'start' => $serviceStart,
            'end' => $serviceEnd,
        ]);
        return (int) $stmt->fetchColumn();
    }

    public function create(int $userId, string $date, string $time, int $guests, ?string $allergies): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO reservations (user_id, reservation_date, reservation_time, guests, allergies)
             VALUES (:user_id, :date, :time, :guests, :allergies)'
        );
        return $stmt->execute([
            'user_id' => $userId,
            'date' => $date,
            'time' => $time,
            'guests' => $guests,
            'allergies' => $allergies,
        ]);
    }

    public function findById(int $id): ?Reservation
    {
        $stmt = $this->db->prepare(
            'SELECT r.*, u.firstname AS user_firstname, u.lastname AS user_lastname, u.email AS user_email
             FROM reservations r
             JOIN users u ON r.user_id = u.id
             WHERE r.id = :id'
        );
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        return $data ? Reservation::fromArray($data) : null;
    }

    public function findByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT r.*, u.firstname AS user_firstname, u.lastname AS user_lastname, u.email AS user_email
             FROM reservations r
             JOIN users u ON r.user_id = u.id
             WHERE r.user_id = :uid
             ORDER BY r.reservation_date DESC, r.reservation_time DESC'
        );
        $stmt->execute(['uid' => $userId]);
        return array_map([Reservation::class, 'fromArray'], $stmt->fetchAll());
    }

    public function findByDate(string $date): array
    {
        $stmt = $this->db->prepare(
            'SELECT r.*, u.firstname AS user_firstname, u.lastname AS user_lastname, u.email AS user_email
             FROM reservations r
             JOIN users u ON r.user_id = u.id
             WHERE r.reservation_date = :date
             ORDER BY r.reservation_time ASC'
        );
        $stmt->execute(['date' => $date]);
        return array_map([Reservation::class, 'fromArray'], $stmt->fetchAll());
    }

    public function update(int $id, string $date, string $time, int $guests, ?string $allergies): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE reservations
             SET reservation_date = :date, reservation_time = :time,
                 guests = :guests, allergies = :allergies
             WHERE id = :id'
        );
        return $stmt->execute([
            'date' => $date,
            'time' => $time,
            'guests' => $guests,
            'allergies' => $allergies,
            'id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM reservations WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
