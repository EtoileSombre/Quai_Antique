<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Session;
use App\Repository\GalerieRepository;

class AdminGalerieController extends Controller
{
    private GalerieRepository $galerieRepo;

    public function __construct()
    {
        parent::__construct();
        $this->galerieRepo = new GalerieRepository();
    }

    public function index(Request $request)
    {
        if (Session::get('user_role') !== 'admin') {
            $this->redirect('/connexion');
            return;
        }

        $images = $this->galerieRepo->findAll();

        $this->render('pages/admin/galerie', [
            'title' => 'Administration — Galerie',
            'images' => $images,
        ]);
    }

    public function create(Request $request)
    {
        if (Session::get('user_role') !== 'admin') {
            $this->redirect('/connexion');
            return;
        }

        if (!Csrf::verify()) {
            Session::set('flash_error', 'Token de sécurité invalide.');
            $this->redirect('/admin/galerie');
            return;
        }

        $title = trim($_POST['title'] ?? '');
        $sortOrder = (int) ($_POST['sort_order'] ?? 0);

        if (empty($title)) {
            Session::set('flash_error', 'Le titre est obligatoire.');
            $this->redirect('/admin/galerie');
            return;
        }

        // Upload de l'image
        $uploadedFile = $request->file('image');
        if (!$uploadedFile || $uploadedFile['error'] !== UPLOAD_ERR_OK) {
            Session::set('flash_error', 'Veuillez sélectionner une image.');
            $this->redirect('/admin/galerie');
            return;
        }

        $imagePath = $this->handleUpload($uploadedFile);

        if (!$imagePath) {
            Session::set('flash_error', 'Format d\'image non supporté. Utilisez JPG, PNG, WebP ou GIF.');
            $this->redirect('/admin/galerie');
            return;
        }

        $this->galerieRepo->create($title, $imagePath, $sortOrder);
        Session::set('flash_success', 'Image ajoutée avec succès.');
        $this->redirect('/admin/galerie');
    }

    public function editForm(Request $request)
    {
        if (Session::get('user_role') !== 'admin') {
            $this->redirect('/connexion');
            return;
        }

        $id = (int) $request->get('id', 0);
        $image = $this->galerieRepo->findById($id);

        if (!$image) {
            $this->redirect('/admin/galerie');
            return;
        }

        $this->render('pages/admin/galerie-modifier', [
            'title' => 'Modifier l\'image — Administration',
            'image' => $image,
        ]);
    }

    public function update(Request $request)
    {
        if (Session::get('user_role') !== 'admin') {
            $this->redirect('/connexion');
            return;
        }

        if (!Csrf::verify()) {
            Session::set('flash_error', 'Token de sécurité invalide.');
            $this->redirect('/admin/galerie');
            return;
        }

        $id = (int) $request->post('id', 0);
        $title = trim($request->post('title', ''));
        $sortOrder = (int) $request->post('sort_order', 0);

        if (!$id || empty($title)) {
            Session::set('flash_error', 'Données invalides.');
            $this->redirect('/admin/galerie');
            return;
        }

        $this->galerieRepo->update($id, $title, $sortOrder);

        // Nouvelle image uploadée ?
        $uploadedFile = $request->file('image');
        if ($uploadedFile && $uploadedFile['error'] === UPLOAD_ERR_OK) {
            $oldImage = $this->galerieRepo->findById($id);
            $imagePath = $this->handleUpload($uploadedFile);

            if ($imagePath) {
                // Supprimer l'ancienne image
                if ($oldImage) {
                    $this->deleteFile($oldImage->image_path);
                }
                $this->galerieRepo->updateImagePath($id, $imagePath);
            }
        }

        Session::set('flash_success', 'Image modifiée avec succès.');
        $this->redirect('/admin/galerie');
    }

    public function delete(Request $request)
    {
        if (Session::get('user_role') !== 'admin') {
            $this->redirect('/connexion');
            return;
        }

        if (!Csrf::verify()) {
            Session::set('flash_error', 'Token de sécurité invalide.');
            $this->redirect('/admin/galerie');
            return;
        }

        $id = (int) $request->post('id', 0);
        $image = $this->galerieRepo->findById($id);

        if ($image) {
            $this->deleteFile($image->image_path);
            $this->galerieRepo->delete($id);
            Session::set('flash_success', 'Image supprimée.');
        }

        $this->redirect('/admin/galerie');
    }

    private function handleUpload(array $file): ?string
    {
        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowed, true)) {
            return null;
        }

        $ext = match ($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            default => 'jpg',
        };

        $filename = uniqid('gallery_', true) . '.' . $ext;
        $uploadDir = __DIR__ . '/../../public/assets/images/gallery';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $destination = $uploadDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return '/assets/images/gallery/' . $filename;
        }

        return null;
    }

    private function deleteFile(string $path): void
    {
        $fullPath = __DIR__ . '/../../public' . $path;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}
