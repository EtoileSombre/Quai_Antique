<?php
$flash_error = \App\Core\Session::get('flash_error');
\App\Core\Session::delete('flash_error');
?>

<!-- En-tête -->
<section class="bg-nacre">
    <div class="max-w-5xl mx-auto px-6 pt-20 pb-6 md:pt-28">
        <h1 class="font-heading text-2xl md:text-3xl font-semibold text-or-dark">
            <i class="bi bi-pencil text-or"></i> Modifier l'image
        </h1>
    </div>
</section>

<?php include __DIR__ . '/../../partials/admin-nav.php'; ?>

<section class="max-w-2xl mx-auto px-6 pb-20">

    <?php if ($flash_error): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
            <i class="bi bi-exclamation-triangle me-1"></i> <?= htmlspecialchars($flash_error) ?>
        </div>
    <?php endif; ?>

    <div class="card p-6">
        <!-- Aperçu -->
        <div class="aspect-[4/3] bg-gray-100 rounded-lg overflow-hidden mb-6">
            <img src="<?= htmlspecialchars($image->image_path) ?>"
                 alt="<?= htmlspecialchars($image->title ?? '') ?>"
                 class="w-full h-full object-cover">
        </div>

        <form method="POST" action="/admin/galerie/modifier" enctype="multipart/form-data" class="space-y-4">
            <?= \App\Core\Csrf::field() ?>
            <input type="hidden" name="id" value="<?= $image->id ?>">

            <div>
                <label class="form-label">Titre</label>
                <input type="text" name="title" class="form-input"
                       value="<?= htmlspecialchars($image->title ?? '') ?>" required>
            </div>

            <div>
                <label class="form-label">Ordre d'affichage</label>
                <input type="number" name="sort_order" class="form-input"
                       value="<?= $image->sort_order ?>" min="0">
            </div>

            <div>
                <label class="form-label">Nouvelle image (optionnel)</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif" class="form-input">
                <p class="text-xs text-gris mt-1">Laissez vide pour conserver l'image actuelle.</p>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="btn-primary">
                    <i class="bi bi-check-lg"></i> Enregistrer
                </button>
                <a href="/admin/galerie" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</section>
