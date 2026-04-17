<?php
$flash_success = \App\Core\Session::get('flash_success');
$flash_error = \App\Core\Session::get('flash_error');
\App\Core\Session::delete('flash_success');
\App\Core\Session::delete('flash_error');
?>

<!-- En-tête admin -->
<section class="bg-nacre">
    <div class="max-w-5xl mx-auto px-6 pt-20 pb-6 md:pt-28">
        <h1 class="font-heading text-2xl md:text-3xl font-semibold text-or-dark">
            <i class="bi bi-images text-or"></i> Gestion de la Galerie
        </h1>
    </div>
</section>

<?php include __DIR__ . '/../../partials/admin-nav.php'; ?>

<section class="max-w-5xl mx-auto px-6 pb-20">

    <?php if ($flash_success): ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            <i class="bi bi-check-circle me-1"></i> <?= htmlspecialchars($flash_success) ?>
        </div>
    <?php endif; ?>
    <?php if ($flash_error): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
            <i class="bi bi-exclamation-triangle me-1"></i> <?= htmlspecialchars($flash_error) ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire d'ajout -->
    <div class="card p-6 mb-8">
        <h2 class="font-heading text-lg font-semibold mb-4 text-or-dark">
            <i class="bi bi-plus-circle text-or"></i> Ajouter une image
        </h2>
        <form method="POST" action="/admin/galerie/ajouter" enctype="multipart/form-data" class="grid sm:grid-cols-2 gap-4">
            <?= \App\Core\Csrf::field() ?>
            <div>
                <label class="form-label">Titre</label>
                <input type="text" name="title" class="form-input" required placeholder="Nom du plat, salle…">
            </div>
            <div>
                <label class="form-label">Ordre d'affichage</label>
                <input type="number" name="sort_order" class="form-input" value="0" min="0">
            </div>
            <div class="sm:col-span-2">
                <label class="form-label">Image (JPG, PNG, WebP, GIF)</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif" class="form-input" required>
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="btn-primary">
                    <i class="bi bi-upload"></i> Ajouter
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des images -->
    <div class="card p-6">
        <h2 class="font-heading text-lg font-semibold mb-4 text-or-dark">
            <i class="bi bi-grid text-or"></i> Images (<?= count($images) ?>)
        </h2>

        <?php if (empty($images)): ?>
            <p class="text-gris text-sm">Aucune image dans la galerie.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($images as $img): ?>
                    <div class="border border-or/20 rounded-lg overflow-hidden bg-white">
                        <div class="aspect-[4/3] bg-gray-100">
                            <img src="<?= htmlspecialchars($img->image_path) ?>"
                                 alt="<?= htmlspecialchars($img->title ?? '') ?>"
                                 class="w-full h-full object-cover"
                                 loading="lazy">
                        </div>
                        <div class="p-3">
                            <p class="font-semibold text-sm truncate"><?= htmlspecialchars($img->title ?? 'Sans titre') ?></p>
                            <p class="text-xs text-gris">Ordre : <?= $img->sort_order ?></p>
                            <div class="flex gap-2 mt-2">
                                <a href="/admin/galerie/modifier?id=<?= $img->id ?>" class="text-xs text-or hover:text-or-dark">
                                    <i class="bi bi-pencil"></i> Modifier
                                </a>
                                <form method="POST" action="/admin/galerie/supprimer" class="inline"
                                      onsubmit="return confirm('Supprimer cette image ?')">
                                    <?= \App\Core\Csrf::field() ?>
                                    <input type="hidden" name="id" value="<?= $img->id ?>">
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-700">
                                        <i class="bi bi-trash"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
