<!-- Galerie -->
<section class="bg-nacre">
    <div class="max-w-3xl mx-auto px-6 pt-20 pb-12 md:pt-28 md:pb-16 text-center">
        <p class="font-ui text-or text-xs md:text-sm tracking-[0.3em] uppercase mb-6">L'univers du Quai Antique</p>
        <h1 class="font-heading text-3xl md:text-5xl font-semibold mb-4 leading-tight text-or-dark">
            Notre Galerie
        </h1>
        <div class="separator"></div>
        <p class="text-base md:text-lg text-gris max-w-xl mx-auto leading-relaxed">
            Découvrez en images les créations du Chef Arnaud Michant.
        </p>
    </div>
</section>

<section class="max-w-6xl mx-auto px-6 pb-16">
    <?php if (empty($images)): ?>
        <p class="text-center text-gris py-12">Aucune photo pour le moment.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($images as $img): ?>
                <div class="group relative overflow-hidden rounded-lg shadow-md aspect-[4/3]">
                    <img src="<?= htmlspecialchars($img->image_path) ?>"
                         alt="<?= htmlspecialchars($img->title ?? 'Photo du restaurant') ?>"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                         loading="lazy">
                    <?php if ($img->title): ?>
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all duration-300 flex items-end">
                            <p class="text-white font-heading text-lg px-4 py-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 translate-y-2 group-hover:translate-y-0">
                                <?= htmlspecialchars($img->title) ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<!-- CTA Réservation (CDC : bouton d'appel à l'action après la galerie) -->
<section class="max-w-6xl mx-auto px-6 pb-20 text-center">
    <div class="card p-12">
        <p class="font-ui text-or text-xs tracking-[0.25em] uppercase mb-3">Envie de goûter ?</p>
        <h2 class="font-heading text-2xl md:text-3xl font-semibold mb-4 text-or-dark">Réservez Votre Table</h2>
        <div class="separator"></div>
        <p class="text-gris max-w-md mx-auto mb-8">
            Laissez-vous tenter par une expérience gastronomique au cœur de la Savoie.
        </p>
        <a href="/reservation" class="btn-primary text-base">
            <i class="bi bi-calendar-event"></i> Réserver maintenant
        </a>
    </div>
</section>
