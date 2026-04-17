<?php

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Request;
use App\Repository\DishRepository;
use App\Repository\MenuRepository;

class CarteController extends Controller
{
    private DishRepository $dishRepo;
    private MenuRepository $menuRepo;

    public function __construct()
    {
        parent::__construct();
        $this->dishRepo = new DishRepository();
        $this->menuRepo = new MenuRepository();
    }

    public function index(Request $request)
    {
        $dishesByCategory = $this->dishRepo->findGroupedByCategory();
        $menus = $this->menuRepo->findAll();

        $this->render('pages/carte', [
            'title' => 'La Carte — Quai Antique',
            'dishesByCategory' => $dishesByCategory,
            'menus' => $menus,
        ]);
    }
}
