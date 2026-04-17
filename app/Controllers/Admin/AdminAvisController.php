<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Session;
use App\Repository\AvisRepository;

class AdminAvisController extends Controller
{
    private AvisRepository $avisRepo;

    public function __construct()
    {
        parent::__construct();
        $this->avisRepo = new AvisRepository();
    }

    public function index(Request $request)
    {
        if (Session::get('user_role') !== 'admin') {
            $this->redirect('/connexion');
            return;
        }

        $avis = $this->avisRepo->findAll();

        $this->render('pages/admin/avis', [
            'title' => 'Administration — Avis',
            'avis' => $avis,
        ]);
    }

    public function approve(Request $request)
    {
        if (Session::get('user_role') !== 'admin') {
            $this->redirect('/connexion');
            return;
        }

        if (!Csrf::verify()) {
            Session::set('flash_error', 'Token de sécurité invalide.');
            $this->redirect('/admin/avis');
            return;
        }

        $id = $request->post('id', '');
        if ($id) {
            $this->avisRepo->approve($id);
            Session::set('flash_success', 'Avis approuvé.');
        }

        $this->redirect('/admin/avis');
    }

    public function reject(Request $request)
    {
        if (Session::get('user_role') !== 'admin') {
            $this->redirect('/connexion');
            return;
        }

        if (!Csrf::verify()) {
            Session::set('flash_error', 'Token de sécurité invalide.');
            $this->redirect('/admin/avis');
            return;
        }

        $id = $request->post('id', '');
        if ($id) {
            $this->avisRepo->reject($id);
            Session::set('flash_success', 'Avis rejeté.');
        }

        $this->redirect('/admin/avis');
    }

    public function delete(Request $request)
    {
        if (Session::get('user_role') !== 'admin') {
            $this->redirect('/connexion');
            return;
        }

        if (!Csrf::verify()) {
            Session::set('flash_error', 'Token de sécurité invalide.');
            $this->redirect('/admin/avis');
            return;
        }

        $id = $request->post('id', '');
        if ($id) {
            $this->avisRepo->delete($id);
            Session::set('flash_success', 'Avis supprimé.');
        }

        $this->redirect('/admin/avis');
    }
}
