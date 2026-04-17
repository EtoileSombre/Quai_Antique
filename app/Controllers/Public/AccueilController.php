<?php

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Request;

class AccueilController extends Controller
{
    public function index(Request $request)
    {
        $this->render('pages/accueil', [
            'title' => 'Quai Antique — Restaurant Savoyard',
        ]);
    }
}
