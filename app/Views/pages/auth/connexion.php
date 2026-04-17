<?php
$error = \App\Core\Session::get('error');
$success = \App\Core\Session::get('success');
\App\Core\Session::delete('error');
\App\Core\Session::delete('success');
?>

<section class="bg-nacre">
    <div class="max-w-3xl mx-auto px-6 pt-20 pb-12 md:pt-28 md:pb-16 text-center">
        <p class="font-ui text-or text-xs md:text-sm tracking-[0.3em] uppercase mb-6">Espace membre</p>
        <h1 class="font-heading text-3xl md:text-5xl font-semibold mb-4 leading-tight text-or-dark">Connexion</h1>
        <div class="separator"></div>
    </div>
</section>

<section class="max-w-md mx-auto px-6 pb-20">

    <?php if ($error): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
            <i class="bi bi-exclamation-triangle me-1"></i> <?= $error ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            <i class="bi bi-check-circle me-1"></i> <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/connexion" class="card p-8 space-y-6">
        <?= csrf_field() ?>

        <div>
            <label for="email" class="form-label">Adresse email</label>
            <input type="email" id="email" name="email" required autocomplete="email"
                   class="form-input">
        </div>

        <div>
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" id="password" name="password" required autocomplete="current-password"
                   class="form-input">
        </div>

        <button type="submit" class="btn-primary w-full">
            Se connecter
        </button>
    </form>

    <p class="text-center text-sm text-gris mt-6">
        Pas encore de compte ?
        <a href="/inscription" class="text-or hover:text-or-dark font-medium">Créer un compte</a>
    </p>
</section>
