<?php
$flash_error = \App\Core\Session::get('flash_error');
\App\Core\Session::delete('flash_error');
?>

<!-- En-tête -->
<section class="bg-nacre">
    <div class="max-w-3xl mx-auto px-6 pt-20 pb-12 md:pt-28 md:pb-16 text-center">
        <p class="font-ui text-or text-xs md:text-sm tracking-[0.3em] uppercase mb-6">Mon espace</p>
        <h1 class="font-heading text-2xl md:text-3xl font-semibold mb-4 leading-tight text-or-dark">
            Modifier ma réservation
        </h1>
        <div class="separator"></div>
    </div>
</section>

<section class="max-w-2xl mx-auto px-6 pb-20">

    <?php if ($flash_error): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
            <i class="bi bi-exclamation-triangle me-1"></i> <?= htmlspecialchars($flash_error) ?>
        </div>
    <?php endif; ?>

    <div class="card p-6">
        <form method="POST" action="/profil/reservations/modifier" class="space-y-4">
            <?= \App\Core\Csrf::field() ?>
            <input type="hidden" name="id" value="<?= $reservation->id ?>">

            <div>
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-input"
                       min="<?= date('Y-m-d') ?>"
                       value="<?= htmlspecialchars($reservation->reservation_date) ?>" required>
            </div>

            <div>
                <label class="form-label">Heure</label>
                <input type="time" name="time" class="form-input" step="900"
                       value="<?= substr($reservation->reservation_time, 0, 5) ?>" required>
            </div>

            <div>
                <label class="form-label">Nombre de convives</label>
                <input type="number" name="guests" class="form-input"
                       min="1" max="20" value="<?= $reservation->guests ?>" required>
            </div>

            <div>
                <label class="form-label">Allergies</label>
                <textarea name="allergies" rows="2" class="form-input"><?= htmlspecialchars($reservation->allergies ?? '') ?></textarea>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="btn-primary">
                    <i class="bi bi-check-lg"></i> Enregistrer
                </button>
                <a href="/profil" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</section>
