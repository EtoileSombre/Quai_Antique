<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Quai Antique') ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/assets/images/favicon.svg">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="min-h-screen bg-nacre">

    <!-- Header -->
    <header class="fixed top-0 left-0 w-full z-50 transition-all duration-300 bg-nacre/95 backdrop-blur-sm border-b border-or/20" id="main-header">
        <nav class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Logo texte -->
            <a href="/" class="font-heading text-xl font-semibold tracking-widest uppercase text-or no-underline">
                Quai Antique
            </a>

            <!-- Navigation desktop -->
            <ul class="hidden md:flex gap-8 items-center" role="menubar">
                <li role="none"><a href="/" class="nav-link" role="menuitem">Accueil</a></li>
                <li role="none"><a href="/carte" class="nav-link" role="menuitem">La Carte</a></li>
                <li role="none"><a href="/galerie" class="nav-link" role="menuitem">Galerie</a></li>
                <li role="none"><a href="/reservation" class="nav-link" role="menuitem"><i class="bi bi-calendar-event me-1"></i> Réservation</a></li>
                <li role="none"><a href="/connexion" class="nav-link" role="menuitem"><i class="bi bi-person"></i></a></li>
            </ul>

            <!-- Menu hamburger mobile -->
            <button class="md:hidden text-or text-2xl" id="mobile-menu-btn" aria-label="Ouvrir le menu" aria-expanded="false">
                <i class="bi bi-list"></i>
            </button>
        </nav>

        <!-- Navigation mobile -->
        <div class="hidden md:hidden bg-white/95 backdrop-blur-sm border-b border-or/20" id="mobile-menu">
            <ul class="flex flex-col items-center gap-6 py-8">
                <li><a href="/" class="nav-link text-base">Accueil</a></li>
                <li><a href="/carte" class="nav-link text-base">La Carte</a></li>
                <li><a href="/galerie" class="nav-link text-base">Galerie</a></li>
                <li><a href="/reservation" class="nav-link text-base"><i class="bi bi-calendar-event"></i> Réservation</a></li>
                <li><a href="/connexion" class="nav-link text-base"><i class="bi bi-person"></i> Connexion</a></li>
            </ul>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="pt-16">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-or/20 mt-24">
        <div class="max-w-6xl mx-auto px-6 py-16">
            <div class="grid md:grid-cols-3 gap-12">
                <!-- Infos restaurant -->
                <div>
                    <h3 class="font-heading text-lg font-semibold mb-4 text-or">Quai Antique</h3>
                    <p class="text-gris text-sm leading-relaxed">
                        Restaurant gastronomique savoyard<br>
                        par le Chef Arnaud Michant
                    </p>
                </div>

                <!-- Horaires -->
                <div>
                    <h3 class="font-heading text-lg font-semibold mb-4 text-or">Horaires</h3>
                    <ul class="text-gris text-sm space-y-1">
                        <li>Lundi — Vendredi : 12h–14h / 19h–22h</li>
                        <li>Samedi : 19h–23h</li>
                        <li>Dimanche : Fermé</li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="font-heading text-lg font-semibold mb-4 text-or">Contact</h3>
                    <ul class="text-gris text-sm space-y-2">
                        <li><i class="bi bi-geo-alt text-or"></i> Chambéry, Savoie</li>
                        <li><i class="bi bi-telephone text-or"></i> 04 79 00 00 00</li>
                        <li><i class="bi bi-envelope text-or"></i> contact@quai-antique.fr</li>
                    </ul>
                </div>
            </div>

            <!-- Séparateur -->
            <div class="border-t border-or/20 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gris text-sm">&copy; <?= date('Y') ?> Quai Antique — Tous droits réservés</p>
                <div class="flex gap-4">
                    <a href="#" class="text-gris hover:text-or transition" aria-label="Instagram"><i class="bi bi-instagram text-lg"></i></a>
                    <a href="#" class="text-gris hover:text-or transition" aria-label="Facebook"><i class="bi bi-facebook text-lg"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="/assets/js/app.js"></script>
</body>
</html>
