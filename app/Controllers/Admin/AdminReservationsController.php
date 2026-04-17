<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Session;
use App\Repository\ReservationRepository;
use App\Repository\HoraireRepository;

class AdminReservationsController extends Controller
{
    private ReservationRepository $reservationRepo;
    private HoraireRepository $horaireRepo;

    public function __construct()
    {
        parent::__construct();
        $this->reservationRepo = new ReservationRepository();
        $this->horaireRepo = new HoraireRepository();
    }

    public function index(Request $request)
    {
        if (Session::get('user_role') !== 'admin') {
            $this->redirect('/connexion');
            return;
        }

        $date = $_GET['date'] ?? date('Y-m-d');
        $reservations = $this->reservationRepo->findByDate($date);

        $this->render('pages/admin/reservations', [
            'title' => 'Administration — Réservations',
            'reservations' => $reservations,
            'selectedDate' => $date,
        ]);
    }

    public function editForm(Request $request)
    {
        if (Session::get('user_role') !== 'admin') {
            $this->redirect('/connexion');
            return;
        }

        $id = (int) ($_GET['id'] ?? 0);
        $reservation = $this->reservationRepo->findById($id);

        if (!$reservation) {
            $this->redirect('/admin/reservations');
            return;
        }

        $horaires = $this->horaireRepo->findAll();

        $this->render('pages/admin/reservation-modifier', [
            'title' => 'Modifier la réservation — Administration',
            'reservation' => $reservation,
            'horaires' => $horaires,
        ]);
    }

    public function update(Request $request)
    {
        if (Session::get('user_role') !== 'admin') {
            $this->redirect('/connexion');
            return;
        }

        if (!Csrf::verify()) {
            Session::set('flash_error', 'Token de sécurité invalide.');
            $this->redirect('/admin/reservations');
            return;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $date = trim($_POST['date'] ?? '');
        $time = trim($_POST['time'] ?? '');
        $guests = (int) ($_POST['guests'] ?? 0);
        $allergies = trim($_POST['allergies'] ?? '');

        if (!$id || !$date || !$time || $guests < 1) {
            Session::set('flash_error', 'Données invalides.');
            $this->redirect('/admin/reservations');
            return;
        }

        $this->reservationRepo->update($id, $date, $time . ':00', $guests, $allergies ?: null);
        Session::set('flash_success', 'Réservation modifiée.');
        $this->redirect('/admin/reservations?date=' . $date);
    }

    public function delete(Request $request)
    {
        if (Session::get('user_role') !== 'admin') {
            $this->redirect('/connexion');
            return;
        }

        if (!Csrf::verify()) {
            Session::set('flash_error', 'Token de sécurité invalide.');
            $this->redirect('/admin/reservations');
            return;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $reservation = $this->reservationRepo->findById($id);

        if ($reservation) {
            $this->reservationRepo->delete($id);
            Session::set('flash_success', 'Réservation supprimée.');
            $this->redirect('/admin/reservations?date=' . $reservation->reservation_date);
            return;
        }

        $this->redirect('/admin/reservations');
    }
}
