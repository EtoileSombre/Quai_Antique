<?php

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Session;
use App\Repository\HoraireRepository;
use App\Repository\ParametreRepository;
use App\Repository\ReservationRepository;

class ReservationController extends Controller
{
    private HoraireRepository $horaireRepo;
    private ParametreRepository $parametreRepo;
    private ReservationRepository $reservationRepo;

    public function __construct()
    {
        parent::__construct();
        $this->horaireRepo = new HoraireRepository();
        $this->parametreRepo = new ParametreRepository();
        $this->reservationRepo = new ReservationRepository();
    }

    /**
     * Affiche le formulaire de réservation.
     */
    public function index(Request $request)
    {
        $horaires = $this->horaireRepo->findAll();

        // Pré-remplissage si connecté
        $defaultGuests = Session::get('user_default_guests', 1);
        $defaultAllergies = Session::get('user_allergies', '');

        $this->render('pages/reservation', [
            'title' => 'Réserver — Quai Antique',
            'horaires' => $horaires,
            'defaultGuests' => $defaultGuests,
            'defaultAllergies' => $defaultAllergies,
            'pageJs' => 'reservation.js',
        ]);
    }

    /**
     * API AJAX — Retourne la disponibilité pour une date donnée.
     * GET /api/reservation/disponibilite?date=YYYY-MM-DD
     */
    public function disponibilite(Request $request)
    {
        $date = $_GET['date'] ?? '';

        if (!$date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $this->json(['error' => 'Date invalide'], 400);
            return;
        }

        // Quel jour de la semaine ? (1=Lundi … 7=Dimanche, ISO-8601)
        $dayOfWeek = (int) date('N', strtotime($date));
        $horaire = $this->horaireRepo->findByDay($dayOfWeek);

        if (!$horaire || $horaire->is_closed) {
            $this->json([
                'available' => false,
                'message' => 'Le restaurant est fermé ce jour.',
                'slots' => [],
            ]);
            return;
        }

        $maxCapacity = $this->parametreRepo->getMaxCapacity();
        $services = [];

        // Service midi
        if ($horaire->lunch_start && $horaire->lunch_end) {
            $services[] = [
                'label' => 'Déjeuner',
                'start' => $horaire->lunch_start,
                'end' => $horaire->lunch_end,
            ];
        }

        // Service soir
        if ($horaire->dinner_start && $horaire->dinner_end) {
            $services[] = [
                'label' => 'Dîner',
                'start' => $horaire->dinner_start,
                'end' => $horaire->dinner_end,
            ];
        }

        $slots = [];
        foreach ($services as $service) {
            $guestsBooked = $this->reservationRepo->countGuestsForService(
                $date, $service['start'], $service['end']
            );
            $remaining = $maxCapacity - $guestsBooked;

            // Créneaux toutes les 15 min
            $current = strtotime($service['start']);
            $end = strtotime($service['end']);

            while ($current <= $end) {
                $slots[] = [
                    'time' => date('H:i', $current),
                    'service' => $service['label'],
                    'remaining' => max(0, $remaining),
                ];
                $current += 15 * 60;
            }
        }

        $this->json([
            'available' => true,
            'maxCapacity' => $maxCapacity,
            'slots' => $slots,
        ]);
    }

    /**
     * POST /reservation — Valider la réservation.
     */
    public function store(Request $request)
    {
        // Connexion obligatoire
        if (!Session::has('user_id')) {
            Session::set('flash_error', 'Veuillez vous connecter pour réserver.');
            Session::set('redirect_after_login', '/reservation');
            $this->redirect('/connexion');
            return;
        }

        if (!Csrf::verify()) {
            Session::set('flash_error', 'Token de sécurité invalide.');
            $this->redirect('/reservation');
            return;
        }

        $date = trim($_POST['date'] ?? '');
        $time = trim($_POST['time'] ?? '');
        $guests = (int) ($_POST['guests'] ?? 0);
        $allergies = trim($_POST['allergies'] ?? '');

        // Validation basique
        $errors = [];
        if (!$date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $errors[] = 'Date invalide.';
        }
        if (!$time || !preg_match('/^\d{2}:\d{2}$/', $time)) {
            $errors[] = 'Heure invalide.';
        }
        if ($guests < 1 || $guests > 20) {
            $errors[] = 'Le nombre de convives doit être entre 1 et 20.';
        }

        // Vérifier que la date n'est pas passée
        if ($date && strtotime($date) < strtotime(date('Y-m-d'))) {
            $errors[] = 'La date ne peut pas être dans le passé.';
        }

        if (!empty($errors)) {
            Session::set('flash_error', implode(' ', $errors));
            $this->redirect('/reservation');
            return;
        }

        // Vérifier que le restaurant est ouvert ce jour
        $dayOfWeek = (int) date('N', strtotime($date));
        $horaire = $this->horaireRepo->findByDay($dayOfWeek);

        if (!$horaire || $horaire->is_closed) {
            Session::set('flash_error', 'Le restaurant est fermé ce jour.');
            $this->redirect('/reservation');
            return;
        }

        // Déterminer le service correspondant à l'heure choisie
        $serviceStart = null;
        $serviceEnd = null;

        if ($horaire->lunch_start && $time >= $horaire->lunch_start && $time <= $horaire->lunch_end) {
            $serviceStart = $horaire->lunch_start;
            $serviceEnd = $horaire->lunch_end;
        } elseif ($horaire->dinner_start && $time >= $horaire->dinner_start && $time <= $horaire->dinner_end) {
            $serviceStart = $horaire->dinner_start;
            $serviceEnd = $horaire->dinner_end;
        } else {
            Session::set('flash_error', 'Cet horaire ne correspond à aucun service.');
            $this->redirect('/reservation');
            return;
        }

        // Vérifier la capacité
        $maxCapacity = $this->parametreRepo->getMaxCapacity();
        $guestsBooked = $this->reservationRepo->countGuestsForService($date, $serviceStart, $serviceEnd);

        if (($guestsBooked + $guests) > $maxCapacity) {
            Session::set('flash_error', 'Désolé, il n\'y a plus assez de places pour ce service (' . ($maxCapacity - $guestsBooked) . ' places restantes).');
            $this->redirect('/reservation');
            return;
        }

        // Créer la réservation
        $this->reservationRepo->create(
            (int) Session::get('user_id'),
            $date,
            $time . ':00',
            $guests,
            $allergies ?: null
        );

        Session::set('flash_success', 'Votre réservation a bien été enregistrée !');
        $this->redirect('/reservation');
    }
}
