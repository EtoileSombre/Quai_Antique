<?php require __DIR__ . '/../../partials/admin-nav.php'; ?>

<div class="max-w-lg mx-auto px-6 pb-16">
    <a href="/admin/carte" class="text-or hover:text-or-dark text-sm mb-4 inline-block">
        <i class="bi bi-arrow-left"></i> Retour à la carte
    </a>

    <h1 class="font-heading text-2xl font-semibold text-or-dark mb-6">
        <?= $dish ? 'Modifier le plat' : 'Ajouter un plat' ?>
    </h1>

    <?php if (\App\Core\Session::has('error')): ?>
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-6">
            <?= htmlspecialchars(\App\Core\Session::get('error')) ?>
        </div>
        <?php \App\Core\Session::delete('error'); ?>
    <?php endif; ?>

    <form action="<?= $dish ? '/admin/carte/plats/modifier' : '/admin/carte/plats/ajouter' ?>" method="POST" class="card p-6 space-y-4">
        <?= \App\Core\Csrf::field() ?>
        <?php if ($dish): ?>
            <input type="hidden" name="id" value="<?= $dish->id ?>">
        <?php endif; ?>

        <div>
            <label for="title" class="block text-sm font-medium mb-1">Titre</label>
            <input type="text" name="title" id="title" required
                value="<?= htmlspecialchars($dish->title ?? '') ?>"
                class="w-full px-3 py-2 border border-gray-200 rounded focus:border-or focus:outline-none">
        </div>

        <div>
            <label for="description" class="block text-sm font-medium mb-1">Description</label>
            <textarea name="description" id="description" rows="3"
                class="w-full px-3 py-2 border border-gray-200 rounded focus:border-or focus:outline-none"><?= htmlspecialchars($dish->description ?? '') ?></textarea>
        </div>

        <div>
            <label for="price" class="block text-sm font-medium mb-1">Prix (€)</label>
            <input type="number" name="price" id="price" step="0.01" min="0" required
                value="<?= $dish ? number_format($dish->price, 2, '.', '') : '' ?>"
                class="w-full px-3 py-2 border border-gray-200 rounded focus:border-or focus:outline-none">
        </div>

        <div>
            <label for="category_id" class="block text-sm font-medium mb-1">Catégorie</label>
            <select name="category_id" id="category_id" required
                class="w-full px-3 py-2 border border-gray-200 rounded focus:border-or focus:outline-none bg-white">
                <option value="">— Choisir —</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat->id ?>"
                        <?= ($dish && $dish->category_id === $cat->id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn-primary w-full">
            <i class="bi bi-check-lg"></i> <?= $dish ? 'Enregistrer' : 'Ajouter' ?>
        </button>
    </form>
</div>
