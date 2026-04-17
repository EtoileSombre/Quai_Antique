<?php
$flash_success = \App\Core\Session::get('flash_success');
$flash_error = \App\Core\Session::get('flash_error');
\App\Core\Session::delete('flash_success');
\App\Core\Session::delete('flash_error');
?>

<!-- En-tête -->
<section class="bg-nacre">
    <div class="max-w-3xl mx-auto px-6 pt-20 pb-12 md:pt-28 md:pb-16 text-center">
        <p class="font-ui text-or text-xs md:text-sm tracking-[0.3em] uppercase mb-6">Mon espace</p>
        <h1 class="font-heading text-3xl md:text-5xl font-semibold mb-4 leading-tight text-or-dark">
            Bonjour, <?= htmlspecialchars($user->firstname) ?>
        </h1>
        <div class="separator"></div>
    </div>
</section>

<section class="max-w-4xl mx-auto px-6 pb-20 space-y-8">

    <?php if ($flash_success): ?>
        <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            <i class="bi bi-check-circle me-1"></i> <?= htmlspecialchars($flash_success) ?>
        </div>
    <?php endif; ?>
    <?php if ($flash_error): ?>
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
            <i class="bi bi-exclamation-triangle me-1"></i> <?= htmlspecialchars($flash_error) ?>
        </div>
    <?php endif; ?>

    <!-- ==================== MES RÉSERVATIONS ==================== -->
    <div class="card p-6">
        <h2 class="font-heading text-lg font-semibold mb-4 text-or-dark">
            <i class="bi bi-calendar-check text-or"></i> Mes réservations
        </h2>

        <?php if (empty($reservations)): ?>
            <p class="text-gris text-sm">Aucune réservation.</p>
            <a href="/reservation" class="btn-primary inline-block mt-3 text-sm">
                <i class="bi bi-plus-circle"></i> Réserver une table
            </a>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-or/5 border-b border-or/20">
                        <tr>
                            <th class="px-4 py-3 text-left font-ui text-xs uppercase tracking-wider text-gris">Date</th>
                            <th class="px-4 py-3 text-left font-ui text-xs uppercase tracking-wider text-gris">Heure</th>
                            <th class="px-4 py-3 text-center font-ui text-xs uppercase tracking-wider text-gris">Couverts</th>
                            <th class="px-4 py-3 text-left font-ui text-xs uppercase tracking-wider text-gris">Allergies</th>
                            <th class="px-4 py-3 text-right font-ui text-xs uppercase tracking-wider text-gris">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($reservations as $r): ?>
                            <?php
                            $isPast = strtotime($r->reservation_date) < strtotime(date('Y-m-d'));
                            ?>
                            <tr class="<?= $isPast ? 'opacity-50' : 'hover:bg-or/5' ?> transition">
                                <td class="px-4 py-3 font-semibold">
                                    <?= date('d/m/Y', strtotime($r->reservation_date)) ?>
                                </td>
                                <td class="px-4 py-3"><?= substr($r->reservation_time, 0, 5) ?></td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-or/10 text-or text-xs font-semibold">
                                        <?= $r->guests ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gris">
                                    <?= $r->allergies ? htmlspecialchars($r->allergies) : '<span class="italic">Aucune</span>' ?>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <?php if (!$isPast): ?>
                                        <div class="flex gap-2 justify-end">
                                            <a href="/profil/reservations/modifier?id=<?= $r->id ?>" class="text-xs text-or hover:text-or-dark">
                                                <i class="bi bi-pencil"></i> Modifier
                                            </a>
                                            <form method="POST" action="/profil/reservations/supprimer" class="inline"
                                                  onsubmit="return confirm('Annuler cette réservation ?')">
                                                <?= \App\Core\Csrf::field() ?>
                                                <input type="hidden" name="id" value="<?= $r->id ?>">
                                                <button type="submit" class="text-xs text-red-500 hover:text-red-700">
                                                    <i class="bi bi-trash"></i> Annuler
                                                </button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-xs text-gris italic">Passée</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <a href="/reservation" class="btn-secondary inline-block mt-4 text-sm">
                <i class="bi bi-plus-circle"></i> Nouvelle réservation
            </a>
        <?php endif; ?>
    </div>

    <!-- ==================== INFORMATIONS PERSONNELLES ==================== -->
    <div class="card p-6">
        <h2 class="font-heading text-lg font-semibold mb-4 text-or-dark">
            <i class="bi bi-person text-or"></i> Informations personnelles
        </h2>

        <form method="POST" action="/profil" class="grid sm:grid-cols-2 gap-4">
            <?= \App\Core\Csrf::field() ?>

            <div>
                <label class="form-label">Prénom</label>
                <input type="text" name="firstname" class="form-input"
                       value="<?= htmlspecialchars($user->firstname) ?>" required>
            </div>
            <div>
                <label class="form-label">Nom</label>
                <input type="text" name="lastname" class="form-input"
                       value="<?= htmlspecialchars($user->lastname) ?>" required>
            </div>
            <div>
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input"
                       value="<?= htmlspecialchars($user->email) ?>" required>
            </div>
            <div>
                <label class="form-label">Téléphone</label>
                <input type="tel" name="phone" class="form-input"
                       value="<?= htmlspecialchars($user->phone ?? '') ?>">
            </div>
            <div>
                <label class="form-label">Convives par défaut</label>
                <input type="number" name="default_guests" class="form-input"
                       min="1" max="20" value="<?= $user->default_guests ?>">
            </div>
            <div>
                <label class="form-label">Allergies</label>
                <input type="text" name="allergies" class="form-input"
                       value="<?= htmlspecialchars($user->allergies ?? '') ?>">
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="btn-primary">
                    <i class="bi bi-check-lg"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>

    <!-- ==================== MOT DE PASSE ==================== -->
    <div class="card p-6">
        <h2 class="font-heading text-lg font-semibold mb-4 text-or-dark">
            <i class="bi bi-lock text-or"></i> Changer le mot de passe
        </h2>

        <form method="POST" action="/profil/mot-de-passe" class="space-y-4 max-w-md">
            <?= \App\Core\Csrf::field() ?>

            <div>
                <label class="form-label">Mot de passe actuel</label>
                <input type="password" name="current_password" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Nouveau mot de passe</label>
                <input type="password" name="new_password" class="form-input" required
                       minlength="8" placeholder="Min. 8 car., maj, min, chiffre, spécial">
            </div>
            <div>
                <label class="form-label">Confirmer</label>
                <input type="password" name="new_password_confirm" class="form-input" required>
            </div>
            <button type="submit" class="btn-primary">
                <i class="bi bi-key"></i> Modifier le mot de passe
            </button>
        </form>
    </div>

    <!-- ==================== SUPPRIMER LE COMPTE ==================== -->
    <div class="card p-6 border-red-200">
        <h2 class="font-heading text-lg font-semibold mb-4 text-red-600">
            <i class="bi bi-exclamation-triangle"></i> Zone dangereuse
        </h2>
        <p class="text-sm text-gris mb-4">
            La suppression de votre compte est irréversible. Toutes vos réservations seront également supprimées.
        </p>
        <form method="POST" action="/profil/supprimer"
              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.')">
            <?= \App\Core\Csrf::field() ?>
            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm">
                <i class="bi bi-trash"></i> Supprimer mon compte
            </button>
        </form>
    </div>
</section>
