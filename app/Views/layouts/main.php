<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Quai Antique') ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="min-h-screen bg-stone-50 text-stone-800">

    <!-- Header -->
    <header class="bg-stone-900 text-white">
        <nav class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold tracking-wide">Quai Antique</a>
            <ul class="flex gap-6 text-sm">
                <li><a href="/" class="hover:text-amber-400 transition">Accueil</a></li>
                <li><a href="/carte" class="hover:text-amber-400 transition">La Carte</a></li>
                <li><a href="/galerie" class="hover:text-amber-400 transition">Galerie</a></li>
                <li><a href="/reservation" class="hover:text-amber-400 transition">Réservation</a></li>
                <li><a href="/connexion" class="hover:text-amber-400 transition">Connexion</a></li>
            </ul>
        </nav>
    </header>

    <!-- Contenu principal -->
    <main class="container mx-auto px-4 py-8">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-stone-900 text-stone-400 text-center py-6 mt-12">
        <p>&copy; <?= date('Y') ?> Quai Antique — Tous droits réservés</p>
    </footer>

    <script src="/assets/js/app.js"></script>
</body>
</html>
