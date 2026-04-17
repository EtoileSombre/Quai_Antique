<?php
$error = \App\Core\Session::get('error');
$old = \App\Core\Session::get('old', []);
\App\Core\Session::delete('error');
\App\Core\Session::delete('old');
?>

<section class="bg-nacre">
    <div class="max-w-3xl mx-auto px-6 pt-20 pb-12 md:pt-28 md:pb-16 text-center">
        <p class="font-ui text-or text-xs md:text-sm tracking-[0.3em] uppercase mb-6">Espace membre</p>
        <h1 class="font-heading text-3xl md:text-5xl font-semibold mb-4 leading-tight text-or-dark">Inscription</h1>
        <div class="separator"></div>
    </div>
</section>

<section class="max-w-lg mx-auto px-6 pb-20">

    <?php if ($error): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
            <i class="bi bi-exclamation-triangle me-1"></i> <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/inscription" class="card p-8 space-y-5">
        <?= csrf_field() ?>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="firstname" class="form-label">Prénom</label>
                <input type="text" id="firstname" name="firstname" required
                       value="<?= htmlspecialchars($old['firstname'] ?? '') ?>"
                       class="form-input">
            </div>
            <div>
                <label for="lastname" class="form-label">Nom</label>
                <input type="text" id="lastname" name="lastname" required
                       value="<?= htmlspecialchars($old['lastname'] ?? '') ?>"
                       class="form-input">
            </div>
        </div>

        <div>
            <label for="email" class="form-label">Adresse email</label>
            <input type="email" id="email" name="email" required autocomplete="email"
                   value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                   class="form-input">
        </div>

        <div>
            <label for="phone" class="form-label">Téléphone <span class="text-gris text-xs font-normal normal-case tracking-normal">(optionnel)</span></label>
            <input type="tel" id="phone" name="phone"
                   value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                   class="form-input">
        </div>

        <div>
            <label for="default_guests" class="form-label">Nombre de convives par défaut</label>
            <input type="number" id="default_guests" name="default_guests" min="1" max="20"
                   value="<?= htmlspecialchars($old['default_guests'] ?? '1') ?>"
                   class="form-input">
        </div>

        <div>
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" id="password" name="password" required autocomplete="new-password"
                   class="form-input">
            <p class="text-xs text-gris mt-1">Min. 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial</p>
        </div>

        <div>
            <label for="password_confirm" class="form-label">Confirmer le mot de passe</label>
            <input type="password" id="password_confirm" name="password_confirm" required autocomplete="new-password"
                   class="form-input">
        </div>

        <div>
            <label for="allergies" class="form-label">Allergies <span class="text-gris text-xs font-normal normal-case tracking-normal">(optionnel)</span></label>
            <textarea id="allergies" name="allergies" rows="2"
                      class="form-input resize-none"
                      placeholder="Ex: Gluten, fruits à coque..."><?= htmlspecialchars($old['allergies'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn-primary w-full">
            Créer mon compte
        </button>
    </form>

    <p class="text-center text-sm text-gris mt-6">
        Déjà un compte ?
        <a href="/connexion" class="text-or hover:text-or-dark font-medium">Se connecter</a>
    </p>
</section>
