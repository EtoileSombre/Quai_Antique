// Réservation — Vérification de disponibilité en temps réel (Fetch API)
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
