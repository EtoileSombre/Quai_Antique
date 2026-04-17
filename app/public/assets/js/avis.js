// Avis — Sélecteur d'étoiles interactif
document.addEventListener('DOMContentLoaded', () => {
    const stars = document.querySelectorAll('#star-rating .star-btn');
    const input = document.getElementById('rating-input');
    if (!stars.length) return;

    stars.forEach(btn => {
        btn.addEventListener('click', () => {
            const val = parseInt(btn.dataset.value);
            input.value = val;
            stars.forEach((s, i) => {
                s.classList.toggle('text-or', i < val);
                s.classList.toggle('text-gray-300', i >= val);
            });
        });
    });
});
