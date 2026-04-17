<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Session;
use App\Repository\CategorieRepository;
use App\Repository\PlatRepository;
use App\Repository\MenuRepository;

class AdminCarteController extends Controller
{
    private CategorieRepository $categoryRepo;
    private PlatRepository $platRepo;
    private MenuRepository $menuRepo;

    public function __construct()
    {
        parent::__construct();
        $this->checkAdmin();
        $this->categoryRepo = new CategorieRepository();
        $this->platRepo = new PlatRepository();
        $this->menuRepo = new MenuRepository();
    }

    private function checkAdmin(): void
    {
        if (!Session::has('user_id') || Session::get('user_role') !== 'admin') {
            Session::set('error', 'Accès non autorisé.');
            $this->redirect('/connexion');
        }
    }

    // ── Dashboard admin ──────────────────────────────────────

    public function index(Request $request)
    {
        $categories = $this->categoryRepo->findAll();
        $dishes = $this->platRepo->findAll();
        $menus = $this->menuRepo->findAll();

        $this->render('pages/admin/carte', [
            'title' => 'Gestion de la carte — Admin',
            'categories' => $categories,
            'dishes' => $dishes,
            'menus' => $menus,
        ]);
    }

    // ── Catégories ───────────────────────────────────────────

    public function createCategory(Request $request)
    {
        if (!Csrf::verify()) {
            Session::set('error', 'Session expirée.');
            $this->redirect('/admin/carte');
        }

        $name = trim($request->post('name'));
        $sortOrder = (int) $request->post('sort_order');

        if (empty($name)) {
            Session::set('error', 'Le nom de la catégorie est requis.');
            $this->redirect('/admin/carte');
        }

        $this->categoryRepo->create([
            'name' => $name,
            'sort_order' => $sortOrder,
        ]);

        Session::set('success', 'Catégorie ajoutée.');
        $this->redirect('/admin/carte');
    }

    public function editCategoryForm(Request $request)
    {
        $id = (int) $request->get('id');
        $category = $this->categoryRepo->findById($id);

        if (!$category) {
            Session::set('error', 'Catégorie introuvable.');
            $this->redirect('/admin/carte');
        }

        $this->render('pages/admin/categorie-modifier', [
            'title' => 'Modifier catégorie — Admin',
            'category' => $category,
        ]);
    }

    public function updateCategory(Request $request)
    {
        if (!Csrf::verify()) {
            Session::set('error', 'Session expirée.');
            $this->redirect('/admin/carte');
        }

        $id = (int) $request->post('id');
        $name = trim($request->post('name'));
        $sortOrder = (int) $request->post('sort_order');

        if (empty($name)) {
            Session::set('error', 'Le nom est requis.');
            $this->redirect('/admin/carte/categories/modifier?id=' . $id);
        }

        $this->categoryRepo->update($id, [
            'name' => $name,
            'sort_order' => $sortOrder,
        ]);

        Session::set('success', 'Catégorie mise à jour.');
        $this->redirect('/admin/carte');
    }

    public function deleteCategory(Request $request)
    {
        if (!Csrf::verify()) {
            Session::set('error', 'Session expirée.');
            $this->redirect('/admin/carte');
        }

        $id = (int) $request->post('id');
        $this->categoryRepo->delete($id);

        Session::set('success', 'Catégorie supprimée.');
        $this->redirect('/admin/carte');
    }

    // ── Plats ────────────────────────────────────────────────

    public function createDishForm(Request $request)
    {
        $categories = $this->categoryRepo->findAll();
        $this->render('pages/admin/plat-formulaire', [
            'title' => 'Ajouter un plat — Admin',
            'categories' => $categories,
            'dish' => null,
        ]);
    }

    public function createDish(Request $request)
    {
        if (!Csrf::verify()) {
            Session::set('error', 'Session expirée.');
            $this->redirect('/admin/carte');
        }

        $data = $this->validateDish($request);

        if ($data === false) {
            $this->redirect('/admin/carte/plats/ajouter');
        }

        $this->platRepo->create($data);
        Session::set('success', 'Plat ajouté.');
        $this->redirect('/admin/carte');
    }

    public function editDishForm(Request $request)
    {
        $id = (int) $request->get('id');
        $dish = $this->platRepo->findById($id);

        if (!$dish) {
            Session::set('error', 'Plat introuvable.');
            $this->redirect('/admin/carte');
        }

        $categories = $this->categoryRepo->findAll();

        $this->render('pages/admin/plat-formulaire', [
            'title' => 'Modifier un plat — Admin',
            'categories' => $categories,
            'dish' => $dish,
        ]);
    }

    public function updateDish(Request $request)
    {
        if (!Csrf::verify()) {
            Session::set('error', 'Session expirée.');
            $this->redirect('/admin/carte');
        }

        $id = (int) $request->post('id');
        $data = $this->validateDish($request);

        if ($data === false) {
            $this->redirect('/admin/carte/plats/modifier?id=' . $id);
        }

        $this->platRepo->update($id, $data);
        Session::set('success', 'Plat mis à jour.');
        $this->redirect('/admin/carte');
    }

    public function deleteDish(Request $request)
    {
        if (!Csrf::verify()) {
            Session::set('error', 'Session expirée.');
            $this->redirect('/admin/carte');
        }

        $id = (int) $request->post('id');
        $this->platRepo->delete($id);

        Session::set('success', 'Plat supprimé.');
        $this->redirect('/admin/carte');
    }

    private function validateDish(Request $request): array|false
    {
        $title = trim($request->post('title'));
        $description = trim($request->post('description'));
        $price = $request->post('price');
        $categoryId = (int) $request->post('category_id');

        if (empty($title) || empty($price) || $categoryId <= 0) {
            Session::set('error', 'Titre, prix et catégorie sont requis.');
            return false;
        }

        return [
            'title' => $title,
            'description' => $description ?: null,
            'price' => (float) $price,
            'category_id' => $categoryId,
        ];
    }

    // ── Menus ────────────────────────────────────────────────

    public function createMenuForm(Request $request)
    {
        $this->render('pages/admin/menu-formulaire', [
            'title' => 'Ajouter un menu — Admin',
            'menu' => null,
        ]);
    }

    public function createMenu(Request $request)
    {
        if (!Csrf::verify()) {
            Session::set('error', 'Session expirée.');
            $this->redirect('/admin/carte');
        }

        $data = $this->validateMenu($request);

        if ($data === false) {
            $this->redirect('/admin/carte/menus/ajouter');
        }

        $this->menuRepo->create($data);
        Session::set('success', 'Menu ajouté.');
        $this->redirect('/admin/carte');
    }

    public function editMenuForm(Request $request)
    {
        $id = (int) $request->get('id');
        $menu = $this->menuRepo->findById($id);

        if (!$menu) {
            Session::set('error', 'Menu introuvable.');
            $this->redirect('/admin/carte');
        }

        $this->render('pages/admin/menu-formulaire', [
            'title' => 'Modifier un menu — Admin',
            'menu' => $menu,
        ]);
    }

    public function updateMenu(Request $request)
    {
        if (!Csrf::verify()) {
            Session::set('error', 'Session expirée.');
            $this->redirect('/admin/carte');
        }

        $id = (int) $request->post('id');
        $data = $this->validateMenu($request);

        if ($data === false) {
            $this->redirect('/admin/carte/menus/modifier?id=' . $id);
        }

        $this->menuRepo->update($id, $data);
        Session::set('success', 'Menu mis à jour.');
        $this->redirect('/admin/carte');
    }

    public function deleteMenu(Request $request)
    {
        if (!Csrf::verify()) {
            Session::set('error', 'Session expirée.');
            $this->redirect('/admin/carte');
        }

        $id = (int) $request->post('id');
        $this->menuRepo->delete($id);

        Session::set('success', 'Menu supprimé.');
        $this->redirect('/admin/carte');
    }

    private function validateMenu(Request $request): array|false
    {
        $title = trim($request->post('title'));
        $description = trim($request->post('description'));
        $price = $request->post('price');

        if (empty($title) || empty($price)) {
            Session::set('error', 'Titre et prix sont requis.');
            return false;
        }

        return [
            'title' => $title,
            'description' => $description ?: null,
            'price' => (float) $price,
        ];
    }
}
