<?php

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Request;
use App\Repository\GalerieRepository;

class GalerieController extends Controller
{
    private GalerieRepository $galerieRepo;

    public function __construct()
    {
        parent::__construct();
        $this->galerieRepo = new GalerieRepository();
    }

    public function index(Request $request)
    {
        $images = $this->galerieRepo->findAll();

        $this->render('pages/galerie', [
            'title' => 'Galerie — Quai Antique',
            'images' => $images,
        ]);
    }
}
