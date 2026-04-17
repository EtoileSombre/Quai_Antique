<?php require __DIR__ . '/../../partials/admin-nav.php'; ?>

<div class="max-w-lg mx-auto px-6 pb-16">
    <a href="/admin/carte" class="text-or hover:text-or-dark text-sm mb-4 inline-block">
        <i class="bi bi-arrow-left"></i> Retour à la carte
    </a>

    <h1 class="font-heading text-2xl font-semibold text-or-dark mb-6">Modifier la catégorie</h1>

    <?php if (\App\Core\Session::has('error')): ?>
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-6">
            <?= htmlspecialchars(\App\Core\Session::get('error')) ?>
        </div>
        <?php \App\Core\Session::delete('error'); ?>
    <?php endif; ?>

    <form action="/admin/carte/categories/modifier" method="POST" class="card p-6 space-y-4">
        <?= \App\Core\Csrf::field() ?>
        <input type="hidden" name="id" value="<?= $category->id ?>">

        <div>
            <label for="name" class="block text-sm font-medium mb-1">Nom</label>
            <input type="text" name="name" id="name" required
                value="<?= htmlspecialchars($category->name) ?>"
                class="w-full px-3 py-2 border border-gray-200 rounded focus:border-or focus:outline-none">
        </div>

        <div>
            <label for="sort_order" class="block text-sm font-medium mb-1">Ordre d'affichage</label>
            <input type="number" name="sort_order" id="sort_order"
                value="<?= $category->sort_order ?>"
                class="w-full px-3 py-2 border border-gray-200 rounded focus:border-or focus:outline-none">
        </div>

        <button type="submit" class="btn-primary w-full">
            <i class="bi bi-check-lg"></i> Enregistrer
        </button>
    </form>
</div>
