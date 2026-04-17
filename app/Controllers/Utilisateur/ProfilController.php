<?php

namespace App\Controllers\Utilisateur;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Session;
use App\Repository\ReservationRepository;
use App\Repository\HoraireRepository;
use App\Repository\UtilisateurRepository;

class ProfilController extends Controller
{
    private UtilisateurRepository $userRepo;
    private ReservationRepository $reservationRepo;
    private HoraireRepository $horaireRepo;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = new UtilisateurRepository();
        $this->reservationRepo = new ReservationRepository();
        $this->horaireRepo = new HoraireRepository();
    }

    /**
     * Page profil : infos + réservations.
     */
    public function index(Request $request)
    {
        $this->requireAuth();

        $user = $this->userRepo->findById((int) Session::get('user_id'));
        $reservations = $this->reservationRepo->findByUser($user->id);

        $this->render('pages/utilisateur/profil', [
            'title' => 'Mon profil — Quai Antique',
            'user' => $user,
            'reservations' => $reservations,
        ]);
    }

    /**
     * POST /profil — Mettre à jour les infos personnelles.
     */
    public function update(Request $request)
    {
        $this->requireAuth();

        if (!Csrf::verify()) {
            Session::set('flash_error', 'Token de sécurité invalide.');
            $this->redirect('/profil');
            return;
        }

        $userId = (int) Session::get('user_id');
        $data = [
            'firstname' => trim($_POST['firstname'] ?? ''),
            'lastname' => trim($_POST['lastname'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'default_guests' => (int) ($_POST['default_guests'] ?? 1),
            'allergies' => trim($_POST['allergies'] ?? ''),
        ];

        $errors = [];

        if (empty($data['firstname']) || empty($data['lastname'])) {
            $errors[] = 'Le prénom et le nom sont obligatoires.';
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Adresse email invalide.';
        }

        if ($this->userRepo->emailExistsForOther($data['email'], $userId)) {
            $errors[] = 'Cette adresse email est déjà utilisée.';
        }

        if (!empty($errors)) {
            Session::set('flash_error', implode(' ', $errors));
            $this->redirect('/profil');
            return;
        }

        $this->userRepo->updateProfile($userId, $data);

        // Mettre à jour la session
        Session::set('user_firstname', $data['firstname']);
        Session::set('user_default_guests', $data['default_guests']);
        Session::set('user_allergies', $data['allergies']);

        Session::set('flash_success', 'Profil mis à jour.');
        $this->redirect('/profil');
    }

    /**
     * POST /profil/mot-de-passe — Changer le mot de passe.
     */
    public function updatePassword(Request $request)
    {
        $this->requireAuth();

        if (!Csrf::verify()) {
            Session::set('flash_error', 'Token de sécurité invalide.');
            $this->redirect('/profil');
            return;
        }

        $userId = (int) Session::get('user_id');
        $user = $this->userRepo->findById($userId);

        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['new_password_confirm'] ?? '';

        if (!password_verify($current, $user->password)) {
            Session::set('flash_error', 'Mot de passe actuel incorrect.');
            $this->redirect('/profil');
            return;
        }

        if (strlen($new) < 8
            || !preg_match('/[A-Z]/', $new)
            || !preg_match('/[a-z]/', $new)
            || !preg_match('/[0-9]/', $new)
            || !preg_match('/[^A-Za-z0-9]/', $new)
        ) {
            Session::set('flash_error', 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.');
            $this->redirect('/profil');
            return;
        }

        if ($new !== $confirm) {
            Session::set('flash_error', 'Les mots de passe ne correspondent pas.');
            $this->redirect('/profil');
            return;
        }

        $this->userRepo->updatePassword($userId, $new);
        Session::set('flash_success', 'Mot de passe modifié.');
        $this->redirect('/profil');
    }

    /**
     * POST /profil/supprimer — Supprimer le compte.
     */
    public function deleteAccount(Request $request)
    {
        $this->requireAuth();

        if (!Csrf::verify()) {
            Session::set('flash_error', 'Token de sécurité invalide.');
            $this->redirect('/profil');
            return;
        }

        $userId = (int) Session::get('user_id');
        $this->userRepo->delete($userId);

        Session::destroy();
        session_start();
        Session::set('flash_success', 'Votre compte a été supprimé.');
        $this->redirect('/');
    }

    /**
     * Formulaire de modification d'une réservation client.
     */
    public function editReservation(Request $request)
    {
        $this->requireAuth();

        $id = (int) ($_GET['id'] ?? 0);
        $reservation = $this->reservationRepo->findById($id);

        if (!$reservation || $reservation->user_id !== (int) Session::get('user_id')) {
            $this->redirect('/profil');
            return;
        }

        $horaires = $this->horaireRepo->findAll();

        $this->render('pages/utilisateur/reservation-modifier', [
            'title' => 'Modifier ma réservation — Quai Antique',
            'reservation' => $reservation,
            'horaires' => $horaires,
        ]);
    }

    /**
     * POST /profil/reservations/modifier — Mettre à jour une réservation.
     */
    public function updateReservation(Request $request)
    {
        $this->requireAuth();

        if (!Csrf::verify()) {
            Session::set('flash_error', 'Token de sécurité invalide.');
            $this->redirect('/profil');
            return;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $reservation = $this->reservationRepo->findById($id);

        if (!$reservation || $reservation->user_id !== (int) Session::get('user_id')) {
            $this->redirect('/profil');
            return;
        }

        $date = trim($_POST['date'] ?? '');
        $time = trim($_POST['time'] ?? '');
        $guests = (int) ($_POST['guests'] ?? 0);
        $allergies = trim($_POST['allergies'] ?? '');

        if (!$date || !$time || $guests < 1) {
            Session::set('flash_error', 'Données invalides.');
            $this->redirect('/profil');
            return;
        }

        $this->reservationRepo->update($id, $date, $time . ':00', $guests, $allergies ?: null);
        Session::set('flash_success', 'Réservation modifiée.');
        $this->redirect('/profil');
    }

    /**
     * POST /profil/reservations/supprimer — Supprimer une réservation.
     */
    public function deleteReservation(Request $request)
    {
        $this->requireAuth();

        if (!Csrf::verify()) {
            Session::set('flash_error', 'Token de sécurité invalide.');
            $this->redirect('/profil');
            return;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $reservation = $this->reservationRepo->findById($id);

        if (!$reservation || $reservation->user_id !== (int) Session::get('user_id')) {
            $this->redirect('/profil');
            return;
        }

        $this->reservationRepo->delete($id);
        Session::set('flash_success', 'Réservation annulée.');
        $this->redirect('/profil');
    }

    private function requireAuth(): void
    {
        if (!Session::has('user_id')) {
            $this->redirect('/connexion');
            exit;
        }
    }
}
