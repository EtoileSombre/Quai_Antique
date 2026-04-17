<?php
$error = \App\Core\Session::get('error');
$success = \App\Core\Session::get('success');
\App\Core\Session::delete('error');
\App\Core\Session::delete('success');
?>

<section class="max-w-md mx-auto px-6 py-24">
    <h1 class="section-title text-center mb-8">Connexion</h1>

    <?php if ($error): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6 text-sm">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6 text-sm">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/connexion" class="card p-8 space-y-6">
        <?= csrf_field() ?>

        <div>
            <label for="email" class="block text-sm font-ui font-medium text-gray-700 mb-1">Adresse email</label>
            <input type="email" id="email" name="email" required autocomplete="email"
                   class="w-full px-4 py-2.5 border border-gris-light rounded focus:outline-none focus:border-or transition text-sm">
        </div>

        <div>
            <label for="password" class="block text-sm font-ui font-medium text-gray-700 mb-1">Mot de passe</label>
            <input type="password" id="password" name="password" required autocomplete="current-password"
                   class="w-full px-4 py-2.5 border border-gris-light rounded focus:outline-none focus:border-or transition text-sm">
        </div>

        <button type="submit" class="btn-primary w-full py-3">
            Se connecter
        </button>
    </form>

    <p class="text-center text-sm text-gris mt-6">
        Pas encore de compte ?
        <a href="/inscription" class="text-or hover:text-or-dark font-medium">Créer un compte</a>
    </p>
</section>
