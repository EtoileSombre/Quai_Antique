<!-- Réservation -->
<section class="bg-nacre">
    <div class="max-w-3xl mx-auto px-6 pt-20 pb-12 md:pt-28 md:pb-16 text-center">
        <p class="font-ui text-or text-xs md:text-sm tracking-[0.3em] uppercase mb-6">Réservation</p>
        <h1 class="font-heading text-3xl md:text-5xl font-semibold mb-4 leading-tight text-or-dark">
            Réserver une Table
        </h1>
        <div class="separator"></div>
        <p class="text-base md:text-lg text-gris max-w-xl mx-auto leading-relaxed">
            Choisissez votre date, votre créneau et le nombre de convives.
        </p>
    </div>
</section>

<section class="max-w-2xl mx-auto px-6 pb-20">

    <?php if (\App\Core\Session::get('flash_success')): ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            <i class="bi bi-check-circle me-1"></i>
            <?= htmlspecialchars(\App\Core\Session::get('flash_success')) ?>
        </div>
        <?php \App\Core\Session::delete('flash_success'); ?>
    <?php endif; ?>

    <?php if (\App\Core\Session::get('flash_error')): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
            <i class="bi bi-exclamation-triangle me-1"></i>
            <?= htmlspecialchars(\App\Core\Session::get('flash_error')) ?>
        </div>
        <?php \App\Core\Session::delete('flash_error'); ?>
    <?php endif; ?>

    <!-- Horaires d'ouverture -->
    <div class="card p-6 mb-8">
        <h2 class="font-heading text-lg font-semibold mb-4 text-or-dark">
            <i class="bi bi-clock text-or"></i> Horaires d'ouverture
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-sm">
            <?php foreach ($horaires as $h): ?>
                <div class="flex justify-between gap-2 px-3 py-2 rounded <?= $h->is_closed ? 'bg-gray-100 text-gris' : 'bg-or/5' ?>">
                    <span class="font-semibold"><?= $h->getDayName() ?></span>
                    <span>
                        <?php if ($h->is_closed): ?>
                            Fermé
                        <?php else: ?>
                            <?= substr($h->lunch_start, 0, 5) ?>–<?= substr($h->lunch_end, 0, 5) ?>
                            / <?= substr($h->dinner_start, 0, 5) ?>–<?= substr($h->dinner_end, 0, 5) ?>
                        <?php endif; ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Formulaire -->
    <form method="POST" action="/reservation" class="card p-8 space-y-6" id="reservation-form">
        <?= \App\Core\Csrf::field() ?>

        <!-- Date -->
        <div>
            <label for="date" class="form-label">Date</label>
            <input type="date" id="date" name="date"
                   min="<?= date('Y-m-d') ?>"
                   class="form-input" required>
        </div>

        <!-- Message de disponibilité -->
        <div id="availability-msg" class="hidden p-4 rounded-lg text-sm"></div>

        <!-- Créneau horaire -->
        <div>
            <label for="time" class="form-label">Créneau horaire</label>
            <select id="time" name="time" class="form-input" required disabled>
                <option value="">← Choisissez d'abord une date</option>
            </select>
        </div>

        <!-- Nombre de convives -->
        <div>
            <label for="guests" class="form-label">Nombre de convives</label>
            <input type="number" id="guests" name="guests"
                   min="1" max="20"
                   value="<?= (int) ($defaultGuests ?? 1) ?>"
                   class="form-input" required>
        </div>

        <!-- Allergies -->
        <div>
            <label for="allergies" class="form-label">Allergies (optionnel)</label>
            <textarea id="allergies" name="allergies" rows="2"
                      class="form-input"
                      placeholder="Gluten, lactose, fruits à coque…"><?= htmlspecialchars($defaultAllergies ?? '') ?></textarea>
        </div>

        <!-- Bouton -->
        <?php if (\App\Core\Session::has('user_id')): ?>
            <button type="submit" class="btn-primary w-full" id="submit-btn" disabled>
                <i class="bi bi-calendar-check"></i> Confirmer la réservation
            </button>
        <?php else: ?>
            <div class="p-4 bg-or/10 border border-or/30 rounded-lg text-center text-sm">
                <p class="mb-3 text-gris">Vous devez être connecté pour réserver.</p>
                <a href="/connexion" class="btn-primary inline-block">
                    <i class="bi bi-person"></i> Se connecter
                </a>
                <span class="mx-2 text-gris">ou</span>
                <a href="/inscription" class="btn-secondary inline-block">
                    S'inscrire
                </a>
            </div>
        <?php endif; ?>
    </form>
</section>

<!-- Données horaires pour le JS -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const dateInput = document.getElementById('date');
    const timeSelect = document.getElementById('time');
    const guestsInput = document.getElementById('guests');
    const availMsg = document.getElementById('availability-msg');
    const submitBtn = document.getElementById('submit-btn');

    if (!dateInput || !timeSelect) return;

    dateInput.addEventListener('change', async () => {
        const date = dateInput.value;
        if (!date) return;

        // Reset
        timeSelect.innerHTML = '<option value="">Chargement…</option>';
        timeSelect.disabled = true;
        if (submitBtn) submitBtn.disabled = true;
        availMsg.classList.add('hidden');

        try {
            const resp = await fetch('/api/reservation/disponibilite?date=' + encodeURIComponent(date));
            const data = await resp.json();

            if (!data.available) {
                availMsg.className = 'p-4 rounded-lg text-sm bg-red-50 border border-red-200 text-red-800';
                availMsg.innerHTML = '<i class="bi bi-x-circle me-1"></i> ' + data.message;
                availMsg.classList.remove('hidden');
                timeSelect.innerHTML = '<option value="">Aucun créneau disponible</option>';
                return;
            }

            // Remplir les créneaux
            timeSelect.innerHTML = '<option value="">Choisir un créneau</option>';
            let currentService = '';

            data.slots.forEach(slot => {
                if (slot.service !== currentService) {
                    const optgroup = document.createElement('optgroup');
                    optgroup.label = slot.service;
                    timeSelect.appendChild(optgroup);
                    currentService = slot.service;
                }

                const option = document.createElement('option');
                option.value = slot.time;
                option.textContent = slot.time + ' (' + slot.remaining + ' places restantes)';

                if (slot.remaining <= 0) {
                    option.disabled = true;
                    option.textContent = slot.time + ' — Complet';
                }

                // Ajouter dans le dernier optgroup
                const groups = timeSelect.querySelectorAll('optgroup');
                if (groups.length > 0) {
                    groups[groups.length - 1].appendChild(option);
                } else {
                    timeSelect.appendChild(option);
                }
            });

            timeSelect.disabled = false;
            availMsg.className = 'p-4 rounded-lg text-sm bg-green-50 border border-green-200 text-green-800';
            availMsg.innerHTML = '<i class="bi bi-check-circle me-1"></i> Des créneaux sont disponibles (capacité : ' + data.maxCapacity + ' couverts).';
            availMsg.classList.remove('hidden');

        } catch (err) {
            availMsg.className = 'p-4 rounded-lg text-sm bg-red-50 border border-red-200 text-red-800';
            availMsg.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i> Erreur lors de la vérification.';
            availMsg.classList.remove('hidden');
        }
    });

    // Activer le bouton quand un créneau est choisi
    timeSelect.addEventListener('change', () => {
        if (submitBtn) {
            submitBtn.disabled = !timeSelect.value;
        }
    });

    // Vérification côté client avant soumission
    const form = document.getElementById('reservation-form');
    if (form && submitBtn) {
        form.addEventListener('submit', (e) => {
            const guests = parseInt(guestsInput.value, 10);
            const selected = timeSelect.options[timeSelect.selectedIndex];

            if (!selected || !selected.value) {
                e.preventDefault();
                return;
            }

            // Extraire les places restantes du texte
            const match = selected.textContent.match(/(\d+) places restantes/);
            if (match) {
                const remaining = parseInt(match[1], 10);
                if (guests > remaining) {
                    e.preventDefault();
                    alert('Il ne reste que ' + remaining + ' places pour ce créneau. Veuillez réduire le nombre de convives.');
                }
            }
        });
    }
});
</script>
