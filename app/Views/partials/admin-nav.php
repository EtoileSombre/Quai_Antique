<nav class="bg-white border-b border-or/20 mb-8">
    <div class="max-w-6xl mx-auto px-6 py-3 flex gap-6 overflow-x-auto text-sm">
        <a href="/admin/carte" class="font-ui text-gris hover:text-or transition whitespace-nowrap <?= (str_starts_with($_SERVER['REQUEST_URI'], '/admin/carte') || $_SERVER['REQUEST_URI'] === '/admin') ? 'text-or font-semibold' : '' ?>">
            <i class="bi bi-card-list"></i> Carte
        </a>
        <a href="/admin/parametres" class="font-ui text-gris hover:text-or transition whitespace-nowrap <?= str_starts_with($_SERVER['REQUEST_URI'], '/admin/parametres') ? 'text-or font-semibold' : '' ?>">
            <i class="bi bi-gear"></i> Paramètres
        </a>
    </div>
</nav>
