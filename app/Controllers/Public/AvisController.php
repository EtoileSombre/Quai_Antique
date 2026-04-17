<?php

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Session;
use App\Repository\AvisRepository;

class AvisController extends Controller
{
    private AvisRepository $avisRepo;

    public function __construct()
    {
        parent::__construct();
        $this->avisRepo = new AvisRepository();
    }

    public function index(Request $request)
    {
        $avis = $this->avisRepo->findApproved();
        $average = $this->avisRepo->getAverageRating();

        $this->render('pages/avis', [
            'title' => 'Avis — Quai Antique',
            'avis' => $avis,
            'average' => $average,
            'pageJs' => 'avis.js',
        ]);
    }

    public function store(Request $request)
    {
        if (!Session::get('user_id')) {
            $this->redirect('/connexion');
            return;
        }

        if (!Csrf::verify()) {
            Session::set('flash_error', 'Token de sécurité invalide.');
            $this->redirect('/avis');
            return;
        }

        $rating = (int) $request->post('rating', 0);
        $comment = trim($request->post('comment', ''));

        if ($rating < 1 || $rating > 5 || empty($comment)) {
            Session::set('flash_error', 'Veuillez renseigner une note (1-5) et un commentaire.');
            $this->redirect('/avis');
            return;
        }

        $userName = Session::get('user_firstname') . ' ' . mb_substr(Session::get('user_lastname'), 0, 1) . '.';

        $this->avisRepo->create($userName, $rating, $comment);
        Session::set('flash_success', 'Merci pour votre avis ! Il sera visible après validation.');
        $this->redirect('/avis');
    }
}
