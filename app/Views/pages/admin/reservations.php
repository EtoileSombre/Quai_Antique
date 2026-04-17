<?php
$flash_success = \App\Core\Session::get('flash_success');
$flash_error = \App\Core\Session::get('flash_error');
\App\Core\Session::delete('flash_success');
\App\Core\Session::delete('flash_error');

$dayNames = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'];
$dayOfWeek = (int) date('N', strtotime($selectedDate));
$dayLabel = $dayNames[$dayOfWeek] ?? '';
?>

<!-- En-tête admin -->
<section class="bg-nacre">
    <div class="max-w-5xl mx-auto px-6 pt-20 pb-6 md:pt-28">
        <h1 class="font-heading text-2xl md:text-3xl font-semibold text-or-dark">
            <i class="bi bi-calendar-check text-or"></i> Réservations
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

    <!-- Filtre par date -->
    <div class="card p-6 mb-8">
        <form method="GET" action="/admin/reservations" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="form-label">Filtrer par date</label>
                <input type="date" name="date" value="<?= htmlspecialchars($selectedDate) ?>" class="form-input">
            </div>
            <button type="submit" class="btn-primary">
                <i class="bi bi-search"></i> Afficher
            </button>
        </form>
    </div>

    <!-- Résumé -->
    <div class="mb-6">
        <h2 class="font-heading text-lg font-semibold text-or-dark">
            <?= $dayLabel ?> <?= date('d/m/Y', strtotime($selectedDate)) ?>
            <span class="text-gris font-normal text-base">— <?= count($reservations) ?> réservation<?= count($reservations) > 1 ? 's' : '' ?></span>
        </h2>
        <?php
        $totalGuests = array_sum(array_map(fn($r) => $r->guests, $reservations));
        ?>
        <p class="text-sm text-gris mt-1"><?= $totalGuests ?> convive<?= $totalGuests > 1 ? 's' : '' ?> au total</p>
    </div>

    <!-- Tableau -->
    <?php if (empty($reservations)): ?>
        <div class="card p-8 text-center text-gris">
            <i class="bi bi-calendar-x text-3xl text-or/50 mb-3 block"></i>
            Aucune réservation pour cette date.
        </div>
    <?php else: ?>
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-or/5 border-b border-or/20">
                        <tr>
                            <th class="px-4 py-3 text-left font-ui text-xs uppercase tracking-wider text-gris">Heure</th>
                            <th class="px-4 py-3 text-left font-ui text-xs uppercase tracking-wider text-gris">Client</th>
                            <th class="px-4 py-3 text-center font-ui text-xs uppercase tracking-wider text-gris">Couverts</th>
                            <th class="px-4 py-3 text-left font-ui text-xs uppercase tracking-wider text-gris">Allergies</th>
                            <th class="px-4 py-3 text-right font-ui text-xs uppercase tracking-wider text-gris">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($reservations as $r): ?>
                            <tr class="hover:bg-or/5 transition">
                                <td class="px-4 py-3 font-semibold">
                                    <?= substr($r->reservation_time, 0, 5) ?>
                                </td>
                                <td class="px-4 py-3">
                                    <?= htmlspecialchars($r->user_firstname . ' ' . $r->user_lastname) ?>
                                    <span class="text-gris text-xs block"><?= htmlspecialchars($r->user_email) ?></span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-or/10 text-or text-xs font-semibold">
                                        <?= $r->guests ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gris">
                                    <?= $r->allergies ? htmlspecialchars($r->allergies) : '<span class="italic">Aucune</span>' ?>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex gap-2 justify-end">
                                        <a href="/admin/reservations/modifier?id=<?= $r->id ?>" class="text-xs text-or hover:text-or-dark">
                                            <i class="bi bi-pencil"></i> Modifier
                                        </a>
                                        <form method="POST" action="/admin/reservations/supprimer" class="inline"
                                              onsubmit="return confirm('Supprimer cette réservation ?')">
                                            <?= \App\Core\Csrf::field() ?>
                                            <input type="hidden" name="id" value="<?= $r->id ?>">
                                            <button type="submit" class="text-xs text-red-500 hover:text-red-700">
                                                <i class="bi bi-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</section>
