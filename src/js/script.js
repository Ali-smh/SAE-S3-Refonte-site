document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('.tab');

    tabs.forEach(tab => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();

            const targetSection = document.querySelector(`#${tab.id.replace('-tab', '')}`);

            if (targetSection) {
                targetSection.scrollIntoView({
                    behavior: 'smooth'
                });
            }

            // Gérer l'activation de l'onglet
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
        });
    });
});
