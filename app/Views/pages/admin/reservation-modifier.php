<?php
$flash_error = \App\Core\Session::get('flash_error');
\App\Core\Session::delete('flash_error');
?>

<!-- En-tête -->
<section class="bg-nacre">
    <div class="max-w-5xl mx-auto px-6 pt-20 pb-6 md:pt-28">
        <h1 class="font-heading text-2xl md:text-3xl font-semibold text-or-dark">
            <i class="bi bi-pencil text-or"></i> Modifier la réservation
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

    <div class="card p-6 mb-4">
        <p class="text-sm text-gris">
            Client : <strong><?= htmlspecialchars($reservation->user_firstname . ' ' . $reservation->user_lastname) ?></strong>
            (<?= htmlspecialchars($reservation->user_email) ?>)
        </p>
    </div>

    <div class="card p-6">
        <form method="POST" action="/admin/reservations/modifier" class="space-y-4">
            <?= \App\Core\Csrf::field() ?>
            <input type="hidden" name="id" value="<?= $reservation->id ?>">

            <div>
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-input"
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
                <a href="/admin/reservations?date=<?= htmlspecialchars($reservation->reservation_date) ?>" class="btn-secondary">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</section>
