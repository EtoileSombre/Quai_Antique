<!-- Hero Section -->
<section class="bg-nacre">
    <div class="max-w-3xl mx-auto px-6 pt-20 pb-12 md:pt-28 md:pb-16 text-center">
        <p class="font-ui text-or text-xs md:text-sm tracking-[0.3em] uppercase mb-6">Témoignages</p>
        <h1 class="font-heading text-3xl md:text-5xl font-semibold mb-4 leading-tight text-or-dark">
            Avis de nos <span class="text-or">Clients</span>
        </h1>
        <div class="separator"></div>
        <?php if ($average): ?>
            <p class="text-base md:text-lg text-gris max-w-xl mx-auto leading-relaxed">
                Note moyenne :
                <span class="text-or font-semibold"><?= number_format($average, 1) ?>/5</span>
                <span class="text-or ml-1">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="bi bi-star<?= $i <= round($average) ? '-fill' : '' ?>"></i>
                    <?php endfor; ?>
                </span>
            </p>
        <?php else: ?>
            <p class="text-base md:text-lg text-gris max-w-xl mx-auto leading-relaxed">
                Soyez le premier à donner votre avis !
            </p>
        <?php endif; ?>
    </div>
</section>

<!-- Formulaire d'avis (connecté uniquement) -->
<section class="max-w-2xl mx-auto px-6 py-12">
    <?php $flash_success = \App\Core\Session::get('flash_success'); ?>
    <?php $flash_error = \App\Core\Session::get('flash_error'); ?>

    <?php if ($flash_success): ?>
        <?php \App\Core\Session::delete('flash_success'); ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center gap-3">
            <i class="bi bi-check-circle-fill text-green-600"></i>
            <?= htmlspecialchars($flash_success) ?>
        </div>
    <?php endif; ?>

    <?php if ($flash_error): ?>
        <?php \App\Core\Session::delete('flash_error'); ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg flex items-center gap-3">
            <i class="bi bi-exclamation-circle-fill text-red-600"></i>
            <?= htmlspecialchars($flash_error) ?>
        </div>
    <?php endif; ?>

    <?php if (\App\Core\Session::get('user_id')): ?>
        <div class="card p-8">
            <h2 class="font-heading text-xl font-semibold mb-6 text-or-dark">Laisser un avis</h2>
            <form action="/avis" method="POST">
                <?= \App\Core\Csrf::field() ?>

                <div class="mb-5">
                    <label class="form-label">Note <span class="text-red-500">*</span></label>
                    <div class="flex gap-2 mt-1" id="star-rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <button type="button" data-value="<?= $i ?>" class="star-btn text-2xl text-gray-300 hover:text-or transition cursor-pointer">
                                <i class="bi bi-star-fill"></i>
                            </button>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="0" required>
                </div>

                <div class="mb-5">
                    <label for="comment" class="form-label">Commentaire <span class="text-red-500">*</span></label>
                    <textarea name="comment" id="comment" rows="4" class="form-input" placeholder="Partagez votre expérience..." required></textarea>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="bi bi-send"></i> Envoyer mon avis
                </button>
            </form>
        </div>
    <?php else: ?>
        <div class="card p-8 text-center">
            <p class="text-gris mb-4">Connectez-vous pour laisser un avis.</p>
            <a href="/connexion" class="btn-primary">
                <i class="bi bi-box-arrow-in-right"></i> Se connecter
            </a>
        </div>
    <?php endif; ?>
</section>

<!-- Liste des avis -->
<section class="max-w-3xl mx-auto px-6 pb-20">
    <?php if (empty($avis)): ?>
        <p class="text-center text-gris py-12">Aucun avis pour le moment.</p>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach ($avis as $a): ?>
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <span class="font-semibold text-or-dark"><?= htmlspecialchars($a['user_name']) ?></span>
                            <span class="text-gris text-sm ml-3"><?= htmlspecialchars($a['created_at']) ?></span>
                        </div>
                        <div class="text-or">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="bi bi-star<?= $i <= $a['rating'] ? '-fill' : '' ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <p class="text-gris leading-relaxed"><?= nl2br(htmlspecialchars($a['comment'])) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
