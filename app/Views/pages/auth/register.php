<?php
$error = \App\Core\Session::get('error');
$old = \App\Core\Session::get('old', []);
\App\Core\Session::delete('error');
\App\Core\Session::delete('old');
?>

<section class="max-w-md mx-auto px-6 py-24">
    <h1 class="section-title text-center mb-8">Inscription</h1>

    <?php if ($error): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6 text-sm">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/inscription" class="card p-8 space-y-5">
        <?= csrf_field() ?>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="firstname" class="block text-sm font-ui font-medium text-gray-700 mb-1">Prénom</label>
                <input type="text" id="firstname" name="firstname" required
                       value="<?= htmlspecialchars($old['firstname'] ?? '') ?>"
                       class="w-full px-4 py-2.5 border border-gris-light rounded focus:outline-none focus:border-or transition text-sm">
            </div>
            <div>
                <label for="lastname" class="block text-sm font-ui font-medium text-gray-700 mb-1">Nom</label>
                <input type="text" id="lastname" name="lastname" required
                       value="<?= htmlspecialchars($old['lastname'] ?? '') ?>"
                       class="w-full px-4 py-2.5 border border-gris-light rounded focus:outline-none focus:border-or transition text-sm">
            </div>
        </div>

        <div>
            <label for="email" class="block text-sm font-ui font-medium text-gray-700 mb-1">Adresse email</label>
            <input type="email" id="email" name="email" required autocomplete="email"
                   value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                   class="w-full px-4 py-2.5 border border-gris-light rounded focus:outline-none focus:border-or transition text-sm">
        </div>

        <div>
            <label for="phone" class="block text-sm font-ui font-medium text-gray-700 mb-1">Téléphone <span class="text-gris">(optionnel)</span></label>
            <input type="tel" id="phone" name="phone"
                   value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                   class="w-full px-4 py-2.5 border border-gris-light rounded focus:outline-none focus:border-or transition text-sm">
        </div>

        <div>
            <label for="default_guests" class="block text-sm font-ui font-medium text-gray-700 mb-1">Nombre de convives par défaut</label>
            <input type="number" id="default_guests" name="default_guests" min="1" max="20"
                   value="<?= htmlspecialchars($old['default_guests'] ?? '1') ?>"
                   class="w-full px-4 py-2.5 border border-gris-light rounded focus:outline-none focus:border-or transition text-sm">
        </div>

        <div>
            <label for="password" class="block text-sm font-ui font-medium text-gray-700 mb-1">Mot de passe</label>
            <input type="password" id="password" name="password" required autocomplete="new-password"
                   class="w-full px-4 py-2.5 border border-gris-light rounded focus:outline-none focus:border-or transition text-sm">
            <p class="text-xs text-gris mt-1">Min. 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial</p>
        </div>

        <div>
            <label for="password_confirm" class="block text-sm font-ui font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
            <input type="password" id="password_confirm" name="password_confirm" required autocomplete="new-password"
                   class="w-full px-4 py-2.5 border border-gris-light rounded focus:outline-none focus:border-or transition text-sm">
        </div>

        <div>
            <label for="allergies" class="block text-sm font-ui font-medium text-gray-700 mb-1">Allergies <span class="text-gris">(optionnel)</span></label>
            <textarea id="allergies" name="allergies" rows="2"
                      class="w-full px-4 py-2.5 border border-gris-light rounded focus:outline-none focus:border-or transition text-sm resize-none"
                      placeholder="Ex: Gluten, fruits à coque..."><?= htmlspecialchars($old['allergies'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn-primary w-full py-3">
            Créer mon compte
        </button>
    </form>

    <p class="text-center text-sm text-gris mt-6">
        Déjà un compte ?
        <a href="/connexion" class="text-or hover:text-or-dark font-medium">Se connecter</a>
    </p>
</section>
