<?php

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $this->render('pages/home', [
            'title' => 'Quai Antique — Restaurant Savoyard',
        ]);
    }
}
