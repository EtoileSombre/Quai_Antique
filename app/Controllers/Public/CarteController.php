<?php

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Request;
use App\Repository\PlatRepository;
use App\Repository\MenuRepository;

class CarteController extends Controller
{
    private PlatRepository $platRepo;
    private MenuRepository $menuRepo;

    public function __construct()
    {
        parent::__construct();
        $this->platRepo = new PlatRepository();
        $this->menuRepo = new MenuRepository();
    }

    public function index(Request $request)
    {
        $dishesByCategory = $this->platRepo->findGroupedByCategory();
        $menus = $this->menuRepo->findAll();

        $this->render('pages/carte', [
            'title' => 'La Carte — Quai Antique',
            'dishesByCategory' => $dishesByCategory,
            'menus' => $menus,
        ]);
    }
}
