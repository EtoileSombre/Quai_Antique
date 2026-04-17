<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Session;
use App\Repository\HoraireRepository;
use App\Repository\ParametreRepository;

class AdminParametresController extends Controller
{
    private HoraireRepository $hoursRepo;
    private ParametreRepository $settingsRepo;

    public function __construct()
    {
        parent::__construct();
        $this->checkAdmin();
        $this->hoursRepo = new HoraireRepository();
        $this->settingsRepo = new ParametreRepository();
    }

    private function checkAdmin(): void
    {
        if (!Session::has('user_id') || Session::get('user_role') !== 'admin') {
            Session::set('error', 'Accès non autorisé.');
            $this->redirect('/connexion');
        }
    }

    public function index(Request $request)
    {
        $hours = $this->hoursRepo->findAll();
        $maxCapacity = $this->settingsRepo->getMaxCapacity();

        $this->render('pages/admin/parametres', [
            'title' => 'Paramètres du restaurant — Admin',
            'hours' => $hours,
            'maxCapacity' => $maxCapacity,
        ]);
    }

    public function updateHours(Request $request)
    {
        if (!Csrf::verify()) {
            Session::set('error', 'Session expirée.');
            $this->redirect('/admin/parametres');
        }

        // Jours ouverts : mardi (2) à dimanche (7)
        for ($day = 2; $day <= 7; $day++) {
            $lunchStart = $request->post("lunch_start_$day");
            $dinnerStart = $request->post("dinner_start_$day");

            // Valider le format HH:MM
            $lunchStart = $this->validateTime($lunchStart);
            $dinnerStart = $this->validateTime($dinnerStart);

            $this->hoursRepo->updateHours($day, $lunchStart, $dinnerStart);
        }

        Session::set('success', 'Horaires mis à jour.');
        $this->redirect('/admin/parametres');
    }

    public function updateCapacity(Request $request)
    {
        if (!Csrf::verify()) {
            Session::set('error', 'Session expirée.');
            $this->redirect('/admin/parametres');
        }

        $capacity = (int) $request->post('max_capacity');

        if ($capacity < 1 || $capacity > 500) {
            Session::set('error', 'La capacité doit être entre 1 et 500.');
            $this->redirect('/admin/parametres');
        }

        $this->settingsRepo->updateMaxCapacity($capacity);

        Session::set('success', 'Capacité mise à jour.');
        $this->redirect('/admin/parametres');
    }

    private function validateTime(?string $time): ?string
    {
        if (empty($time)) {
            return null;
        }
        if (preg_match('/^([01]\d|2[0-3]):([0-5]\d)$/', $time)) {
            return $time;
        }
        return null;
    }
}
