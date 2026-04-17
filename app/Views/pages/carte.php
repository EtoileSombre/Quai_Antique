<!-- Hero carte -->
<section class="bg-nacre">
    <div class="max-w-3xl mx-auto px-6 pt-20 pb-8 md:pt-28 md:pb-12 text-center">
        <p class="font-ui text-or text-xs tracking-[0.3em] uppercase mb-4">Restaurant gastronomique</p>
        <h1 class="font-heading text-3xl md:text-5xl font-semibold mb-4 text-or-dark">La Carte</h1>
        <div class="separator"></div>
        <p class="text-gris max-w-xl mx-auto">
            Découvrez notre sélection de plats préparés avec des produits frais et locaux du terroir savoyard.
        </p>
    </div>
</section>

<section class="max-w-5xl mx-auto px-6 py-12">
    <div class="grid lg:grid-cols-3 gap-12">

        <!-- Plats par catégorie (2/3) -->
        <div class="lg:col-span-2 space-y-12">
            <?php if (empty($dishesByCategory)): ?>
                <p class="text-gris text-center italic">La carte est en cours de préparation.</p>
            <?php else: ?>
                <?php foreach ($dishesByCategory as $categoryName => $dishes): ?>
                    <div>
                        <h2 class="font-heading text-2xl font-semibold text-or-dark mb-6 pb-2 border-b border-or/30">
                            <?= htmlspecialchars($categoryName) ?>
                        </h2>
                        <div class="space-y-4">
                            <?php foreach ($dishes as $dish): ?>
                                <div class="flex justify-between items-start gap-4">
                                    <div class="flex-1">
                                        <h3 class="font-heading text-lg font-medium"><?= htmlspecialchars($dish->title) ?></h3>
                                        <?php if ($dish->description): ?>
                                            <p class="text-gris text-sm mt-1"><?= htmlspecialchars($dish->description) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <span class="font-heading text-or font-semibold whitespace-nowrap">
                                        <?= number_format($dish->price, 2, ',', ' ') ?> €
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Menus (1/3) -->
        <aside class="space-y-6">
            <h2 class="font-heading text-2xl font-semibold text-or-dark mb-2">Nos Menus</h2>
            <div class="separator !mx-0"></div>

            <?php if (empty($menus)): ?>
                <p class="text-gris italic text-sm">Les menus seront bientôt disponibles.</p>
            <?php else: ?>
                <?php foreach ($menus as $menu): ?>
                    <div class="card p-6">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-heading text-lg font-semibold"><?= htmlspecialchars($menu->title) ?></h3>
                            <span class="font-heading text-or font-bold text-lg">
                                <?= number_format($menu->price, 2, ',', ' ') ?> €
                            </span>
                        </div>
                        <?php if ($menu->description): ?>
                            <p class="text-gris text-sm leading-relaxed"><?= nl2br(htmlspecialchars($menu->description)) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- CTA Réservation -->
            <div class="card p-6 bg-or/5 border-or/30 text-center mt-8">
                <p class="font-heading text-lg font-semibold mb-2">Envie de réserver ?</p>
                <p class="text-gris text-sm mb-4">Réservez votre table en quelques clics.</p>
                <a href="/reservation" class="btn-primary inline-block text-sm">
                    <i class="bi bi-calendar-event"></i> Réserver
                </a>
            </div>
        </aside>
    </div>
</section>
