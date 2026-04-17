<?php

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Request;
use App\Repository\AvisRepository;

class AccueilController extends Controller
{
    private AvisRepository $avisRepo;

    public function __construct()
    {
        parent::__construct();
        $this->avisRepo = new AvisRepository();
    }

    public function index(Request $request)
    {
        $avis = $this->avisRepo->findLatestApproved(3);
        $average = $this->avisRepo->getAverageRating();

        $this->render('pages/accueil', [
            'title' => 'Quai Antique — Restaurant Savoyard',
            'avis' => $avis,
            'average' => $average,
        ]);
    }
}
