<?php
$flash_success = \App\Core\Session::get('flash_success') ?: \App\Core\Session::get('success');
$flash_error = \App\Core\Session::get('flash_error') ?: \App\Core\Session::get('error');
\App\Core\Session::delete('flash_success');
\App\Core\Session::delete('flash_error');
\App\Core\Session::delete('success');
\App\Core\Session::delete('error');
?>

<!-- En-tête admin -->
<section class="bg-nacre">
    <div class="max-w-5xl mx-auto px-6 pt-20 pb-6 md:pt-28">
        <h1 class="font-heading text-2xl md:text-3xl font-semibold text-or-dark">
            <i class="bi bi-card-list text-or"></i> Gestion de la Carte
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

    <div class="grid lg:grid-cols-3 gap-8">

        <!-- ═══ CATÉGORIES ═══ -->
        <div>
            <div class="card p-6">
                <h2 class="font-heading text-lg font-semibold mb-4">Catégories</h2>

                <!-- Formulaire ajout -->
                <form action="/admin/carte/categories/ajouter" method="POST" class="mb-6">
                    <?= \App\Core\Csrf::field() ?>
                    <div class="space-y-3">
                        <input type="text" name="name" placeholder="Nom de la catégorie" required
                            class="form-input text-sm">
                        <input type="number" name="sort_order" placeholder="Ordre (0, 1, 2...)" value="0"
                            class="form-input text-sm">
                        <button type="submit" class="btn-primary text-sm w-full">
                            <i class="bi bi-plus-lg"></i> Ajouter
                        </button>
                    </div>
                </form>

                <!-- Liste -->
                <?php if (empty($categories)): ?>
                    <p class="text-gris text-sm italic">Aucune catégorie.</p>
                <?php else: ?>
                    <ul class="space-y-2">
                        <?php foreach ($categories as $cat): ?>
                            <li class="flex items-center justify-between p-2 bg-nacre rounded">
                                <div>
                                    <span class="text-sm font-medium"><?= htmlspecialchars($cat->name) ?></span>
                                    <span class="text-xs text-gris ml-1">(#<?= $cat->sort_order ?>)</span>
                                </div>
                                <div class="flex gap-1">
                                    <a href="/admin/carte/categories/modifier?id=<?= $cat->id ?>"
                                       class="text-or hover:text-or-dark text-sm" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="/admin/carte/categories/supprimer" method="POST" class="inline"
                                          onsubmit="return confirm('Supprimer cette catégorie et tous ses plats ?')">
                                        <?= \App\Core\Csrf::field() ?>
                                        <input type="hidden" name="id" value="<?= $cat->id ?>">
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <!-- ═══ PLATS ═══ -->
        <div class="lg:col-span-2">
            <div class="card p-6 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-heading text-lg font-semibold">Plats</h2>
                    <a href="/admin/carte/plats/ajouter" class="btn-primary text-sm">
                        <i class="bi bi-plus-lg"></i> Ajouter un plat
                    </a>
                </div>

                <?php if (empty($dishes)): ?>
                    <p class="text-gris text-sm italic">Aucun plat dans la carte.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-or/5 border-b border-or/20">
                                <tr>
                                    <th class="px-4 py-3 text-left font-ui text-xs uppercase tracking-wider text-gris">Plat</th>
                                    <th class="px-4 py-3 text-left font-ui text-xs uppercase tracking-wider text-gris">Catégorie</th>
                                    <th class="px-4 py-3 text-right font-ui text-xs uppercase tracking-wider text-gris">Prix</th>
                                    <th class="px-4 py-3 text-right font-ui text-xs uppercase tracking-wider text-gris">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dishes as $dish): ?>
                                    <tr class="hover:bg-or/5 transition border-b border-gray-100">
                                        <td class="px-4 py-3">
                                            <div class="font-medium"><?= htmlspecialchars($dish->title) ?></div>
                                            <?php if ($dish->description): ?>
                                                <div class="text-gris text-xs mt-0.5"><?= htmlspecialchars(mb_strimwidth($dish->description, 0, 60, '...')) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-gris"><?= htmlspecialchars($dish->category_name ?? '') ?></td>
                                        <td class="px-4 py-3 text-right font-medium text-or"><?= number_format($dish->price, 2, ',', ' ') ?> €</td>
                                        <td class="px-4 py-3 text-right">
                                            <div class="flex gap-2 justify-end">
                                                <a href="/admin/carte/plats/modifier?id=<?= $dish->id ?>"
                                                   class="text-or hover:text-or-dark" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="/admin/carte/plats/supprimer" method="POST" class="inline"
                                                      onsubmit="return confirm('Supprimer ce plat ?')">
                                                    <?= \App\Core\Csrf::field() ?>
                                                    <input type="hidden" name="id" value="<?= $dish->id ?>">
                                                    <button type="submit" class="text-red-500 hover:text-red-700" title="Supprimer">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- ═══ MENUS ═══ -->
            <div class="card p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-heading text-lg font-semibold">Menus</h2>
                    <a href="/admin/carte/menus/ajouter" class="btn-primary text-sm">
                        <i class="bi bi-plus-lg"></i> Ajouter un menu
                    </a>
                </div>

                <?php if (empty($menus)): ?>
                    <p class="text-gris text-sm italic">Aucun menu.</p>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($menus as $menu): ?>
                            <div class="flex justify-between items-start p-4 bg-nacre rounded">
                                <div class="flex-1">
                                    <h3 class="font-medium"><?= htmlspecialchars($menu->title) ?></h3>
                                    <?php if ($menu->description): ?>
                                        <p class="text-gris text-xs mt-1"><?= htmlspecialchars(mb_strimwidth($menu->description, 0, 100, '...')) ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center gap-4 ml-4">
                                    <span class="font-heading font-semibold text-or"><?= number_format($menu->price, 2, ',', ' ') ?> €</span>
                                    <div class="flex gap-2">
                                        <a href="/admin/carte/menus/modifier?id=<?= $menu->id ?>"
                                           class="text-or hover:text-or-dark" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="/admin/carte/menus/supprimer" method="POST" class="inline"
                                              onsubmit="return confirm('Supprimer ce menu ?')">
                                            <?= \App\Core\Csrf::field() ?>
                                            <input type="hidden" name="id" value="<?= $menu->id ?>">
                                            <button type="submit" class="text-red-500 hover:text-red-700" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        </div>
</section>
