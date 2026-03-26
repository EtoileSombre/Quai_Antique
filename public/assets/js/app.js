// JavaScript principal — Quai Antique
document.addEventListener('DOMContentLoaded', () => {

    // Header scroll
    const header = document.getElementById('main-header');
    if (header) {
        const updateHeader = () => {
            if (window.scrollY > 50) {
                header.classList.add('shadow-md');
                header.querySelector('nav').classList.remove('py-5');
                header.querySelector('nav').classList.add('py-3');
            } else {
                header.classList.remove('shadow-md');
                header.querySelector('nav').classList.remove('py-3');
                header.querySelector('nav').classList.add('py-5');
            }
        };
        window.addEventListener('scroll', updateHeader, { passive: true });
        updateHeader();
    }

    // Mobile menu toggle
    const menuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener('click', () => {
            const isOpen = !mobileMenu.classList.contains('hidden');
            mobileMenu.classList.toggle('hidden');
            menuBtn.setAttribute('aria-expanded', String(!isOpen));
            menuBtn.querySelector('i').className = isOpen ? 'bi bi-list' : 'bi bi-x-lg';
        });
    }
});
