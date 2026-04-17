<?php require __DIR__ . '/../../partials/admin-nav.php'; ?>

<div class="max-w-4xl mx-auto px-6 pb-16">
    <h1 class="font-heading text-2xl font-semibold text-or-dark mb-8">Paramètres du restaurant</h1>

    <!-- Messages flash -->
    <?php if (\App\Core\Session::has('success')): ?>
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mb-6">
            <?= htmlspecialchars(\App\Core\Session::get('success')) ?>
        </div>
        <?php \App\Core\Session::delete('success'); ?>
    <?php endif; ?>

    <?php if (\App\Core\Session::has('error')): ?>
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-6">
            <?= htmlspecialchars(\App\Core\Session::get('error')) ?>
        </div>
        <?php \App\Core\Session::delete('error'); ?>
    <?php endif; ?>

    <div class="grid lg:grid-cols-3 gap-8">

        <!-- ═══ HORAIRES ═══ -->
        <div class="lg:col-span-2">
            <div class="card p-6">
                <h2 class="font-heading text-lg font-semibold mb-2">Horaires d'ouverture</h2>
                <p class="text-gris text-sm mb-6">
                    Le restaurant est ouvert du mardi au dimanche. Chaque service dure 2 heures.
                    Indiquez l'heure de début de chaque service.
                </p>

                <form action="/admin/parametres/horaires" method="POST">
                    <?= \App\Core\Csrf::field() ?>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200 text-left">
                                    <th class="pb-3 font-ui text-gris">Jour</th>
                                    <th class="pb-3 font-ui text-gris text-center">Midi (début)</th>
                                    <th class="pb-3 font-ui text-gris text-center">Midi (fin)</th>
                                    <th class="pb-3 font-ui text-gris text-center">Soir (début)</th>
                                    <th class="pb-3 font-ui text-gris text-center">Soir (fin)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($hours as $hour): ?>
                                    <tr class="border-b border-gray-100 <?= $hour->is_closed ? 'opacity-40' : '' ?>">
                                        <td class="py-3 font-medium">
                                            <?= htmlspecialchars($hour->getDayName()) ?>
                                            <?php if ($hour->is_closed): ?>
                                                <span class="text-xs text-red-500 ml-1">Fermé</span>
                                            <?php endif; ?>
                                        </td>
                                        <?php if ($hour->is_closed): ?>
                                            <td colspan="4" class="py-3 text-center text-gris text-xs">—</td>
                                        <?php else: ?>
                                            <td class="py-3 text-center">
                                                <input type="time" name="lunch_start_<?= $hour->day_of_week ?>"
                                                    value="<?= htmlspecialchars($hour->lunch_start ? substr($hour->lunch_start, 0, 5) : '') ?>"
                                                    class="px-2 py-1 border border-gray-200 rounded text-sm text-center focus:border-or focus:outline-none">
                                            </td>
                                            <td class="py-3 text-center text-gris text-xs">
                                                <?php if ($hour->lunch_start): ?>
                                                    <?= substr($hour->lunch_end, 0, 5) ?>
                                                <?php else: ?>
                                                    —
                                                <?php endif; ?>
                                                <span class="text-xs text-gris/60">(auto +2h)</span>
                                            </td>
                                            <td class="py-3 text-center">
                                                <input type="time" name="dinner_start_<?= $hour->day_of_week ?>"
                                                    value="<?= htmlspecialchars($hour->dinner_start ? substr($hour->dinner_start, 0, 5) : '') ?>"
                                                    class="px-2 py-1 border border-gray-200 rounded text-sm text-center focus:border-or focus:outline-none">
                                            </td>
                                            <td class="py-3 text-center text-gris text-xs">
                                                <?php if ($hour->dinner_start): ?>
                                                    <?= substr($hour->dinner_end, 0, 5) ?>
                                                <?php else: ?>
                                                    —
                                                <?php endif; ?>
                                                <span class="text-xs text-gris/60">(auto +2h)</span>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="btn-primary text-sm">
                            <i class="bi bi-check-lg"></i> Enregistrer les horaires
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ═══ CAPACITÉ ═══ -->
        <div>
            <div class="card p-6">
                <h2 class="font-heading text-lg font-semibold mb-2">Capacité</h2>
                <p class="text-gris text-sm mb-6">
                    Nombre maximum de convives pour un service.
                </p>

                <form action="/admin/parametres/capacite" method="POST" class="space-y-4">
                    <?= \App\Core\Csrf::field() ?>

                    <div>
                        <label for="max_capacity" class="block text-sm font-medium mb-1">
                            Convives max
                        </label>
                        <input type="number" name="max_capacity" id="max_capacity"
                            min="1" max="500" required
                            value="<?= (int) $maxCapacity ?>"
                            class="w-full px-3 py-2 border border-gray-200 rounded focus:border-or focus:outline-none text-2xl text-center font-heading font-semibold text-or-dark">
                    </div>

                    <button type="submit" class="btn-primary text-sm w-full">
                        <i class="bi bi-check-lg"></i> Mettre à jour
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
