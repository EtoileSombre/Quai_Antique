<?php require __DIR__ . '/../../partials/admin-nav.php'; ?>

<div class="max-w-lg mx-auto px-6 pb-16">
    <a href="/admin/carte" class="text-or hover:text-or-dark text-sm mb-4 inline-block">
        <i class="bi bi-arrow-left"></i> Retour à la carte
    </a>

    <h1 class="font-heading text-2xl font-semibold text-or-dark mb-6">
        <?= $menu ? 'Modifier le menu' : 'Ajouter un menu' ?>
    </h1>

    <?php if (\App\Core\Session::has('error')): ?>
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-6">
            <?= htmlspecialchars(\App\Core\Session::get('error')) ?>
        </div>
        <?php \App\Core\Session::delete('error'); ?>
    <?php endif; ?>

    <form action="<?= $menu ? '/admin/carte/menus/modifier' : '/admin/carte/menus/ajouter' ?>" method="POST" class="card p-6 space-y-4">
        <?= \App\Core\Csrf::field() ?>
        <?php if ($menu): ?>
            <input type="hidden" name="id" value="<?= $menu->id ?>">
        <?php endif; ?>

        <div>
            <label for="title" class="block text-sm font-medium mb-1">Titre</label>
            <input type="text" name="title" id="title" required
                value="<?= htmlspecialchars($menu->title ?? '') ?>"
                class="w-full px-3 py-2 border border-gray-200 rounded focus:border-or focus:outline-none">
        </div>

        <div>
            <label for="description" class="block text-sm font-medium mb-1">Description</label>
            <textarea name="description" id="description" rows="4"
                placeholder="Entrée + Plat + Dessert&#10;ou détail du menu..."
                class="w-full px-3 py-2 border border-gray-200 rounded focus:border-or focus:outline-none"><?= htmlspecialchars($menu->description ?? '') ?></textarea>
        </div>

        <div>
            <label for="price" class="block text-sm font-medium mb-1">Prix (€)</label>
            <input type="number" name="price" id="price" step="0.01" min="0" required
                value="<?= $menu ? number_format($menu->price, 2, '.', '') : '' ?>"
                class="w-full px-3 py-2 border border-gray-200 rounded focus:border-or focus:outline-none">
        </div>

        <button type="submit" class="btn-primary w-full">
            <i class="bi bi-check-lg"></i> <?= $menu ? 'Enregistrer' : 'Ajouter' ?>
        </button>
    </form>
</div>
