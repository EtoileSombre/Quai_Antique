<?php
$flash_error = \App\Core\Session::get('flash_error') ?: \App\Core\Session::get('error');
\App\Core\Session::delete('flash_error');
\App\Core\Session::delete('error');
?>

<!-- En-tête -->
<section class="bg-nacre">
    <div class="max-w-5xl mx-auto px-6 pt-20 pb-6 md:pt-28">
        <h1 class="font-heading text-2xl md:text-3xl font-semibold text-or-dark">
            <i class="bi bi-pencil text-or"></i> Modifier la catégorie
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

    <a href="/admin/carte" class="text-or hover:text-or-dark text-sm mb-4 inline-block">
        <i class="bi bi-arrow-left"></i> Retour à la carte
    </a>

    <div class="card p-6">
        <form action="/admin/carte/categories/modifier" method="POST" class="space-y-4">
            <?= \App\Core\Csrf::field() ?>
            <input type="hidden" name="id" value="<?= $category->id ?>">

            <div>
                <label for="name" class="form-label">Nom</label>
                <input type="text" name="name" id="name" required
                    value="<?= htmlspecialchars($category->name) ?>"
                    class="form-input">
            </div>

            <div>
                <label for="sort_order" class="form-label">Ordre d'affichage</label>
                <input type="number" name="sort_order" id="sort_order"
                    value="<?= $category->sort_order ?>"
                    class="form-input">
            </div>

            <button type="submit" class="btn-primary w-full">
                <i class="bi bi-check-lg"></i> Enregistrer
            </button>
        </form>
    </div>
</section>
