<?php

namespace App\Controllers\Auth;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Session;
use App\Repository\UtilisateurRepository;

class AuthentificationController extends Controller
{
    private UtilisateurRepository $userRepo;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = new UtilisateurRepository();
    }

    public function loginForm(Request $request)
    {
        if (Session::has('user_id')) {
            $this->redirect('/');
        }

        $this->render('pages/auth/connexion', [
            'title' => 'Connexion — Quai Antique',
        ]);
    }

    public function login(Request $request)
    {
        if (!Csrf::verify()) {
            Session::set('error', 'Session expirée, veuillez réessayer.');
            $this->redirect('/connexion');
        }

        $email = trim($request->post('email'));
        $password = $request->post('password');

        if (empty($email) || empty($password)) {
            Session::set('error', 'Veuillez remplir tous les champs.');
            $this->redirect('/connexion');
        }

        $user = $this->userRepo->findByEmail($email);

        if (!$user || !password_verify($password, $user->password)) {
            Session::set('error', 'Identifiants incorrects.');
            $this->redirect('/connexion');
        }

        Session::set('user_id', $user->id);
        Session::set('user_role', $user->role);
        Session::set('user_firstname', $user->firstname);
        Session::set('user_default_guests', $user->default_guests);
        Session::set('user_allergies', $user->allergies);

        $this->redirect($user->isAdmin() ? '/admin' : '/');
    }

    public function registerForm(Request $request)
    {
        if (Session::has('user_id')) {
            $this->redirect('/');
        }

        $this->render('pages/auth/inscription', [
            'title' => 'Inscription — Quai Antique',
        ]);
    }

    public function register(Request $request)
    {
        if (!Csrf::verify()) {
            Session::set('error', 'Session expirée, veuillez réessayer.');
            $this->redirect('/inscription');
        }

        $data = [
            'firstname' => trim($request->post('firstname')),
            'lastname' => trim($request->post('lastname')),
            'email' => trim($request->post('email')),
            'password' => $request->post('password'),
            'password_confirm' => $request->post('password_confirm'),
            'phone' => trim($request->post('phone')),
            'default_guests' => (int) ($request->post('default_guests') ?: 1),
            'allergies' => trim($request->post('allergies')),
        ];

        // Validation
        $errors = [];

        if (empty($data['firstname']) || empty($data['lastname'])) {
            $errors[] = 'Le prénom et le nom sont obligatoires.';
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Adresse email invalide.';
        }

        if (strlen($data['password']) < 8
            || !preg_match('/[A-Z]/', $data['password'])
            || !preg_match('/[a-z]/', $data['password'])
            || !preg_match('/[0-9]/', $data['password'])
            || !preg_match('/[^A-Za-z0-9]/', $data['password'])
        ) {
            $errors[] = 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.';
        }

        if ($data['password'] !== $data['password_confirm']) {
            $errors[] = 'Les mots de passe ne correspondent pas.';
        }

        if ($this->userRepo->emailExists($data['email'])) {
            $errors[] = 'Cette adresse email est déjà utilisée.';
        }

        if (!empty($errors)) {
            Session::set('error', implode('<br>', $errors));
            Session::set('old', $data);
            $this->redirect('/inscription');
        }

        $this->userRepo->create($data);

        Session::set('success', 'Votre compte a été créé. Vous pouvez vous connecter.');
        $this->redirect('/connexion');
    }

    public function logout(Request $request)
    {
        Session::destroy();
        session_start();
        Session::set('success', 'Vous êtes déconnecté.');
        $this->redirect('/');
    }
}
