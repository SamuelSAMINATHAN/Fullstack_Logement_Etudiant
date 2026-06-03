/**
 * Gestion des favoris en AJAX (Version adaptative POST)
 */
document.addEventListener('DOMContentLoaded', function() {
    const favorisButtons = document.querySelectorAll('.btn-toggle-favoris');

    favorisButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const idAnnonce = this.dataset.id;
            if (!idAnnonce) return;

            // On change de stratégie : on appelle juste l'action 'toggle'
            const url = `${URLROOT}/favoris/toggle`;

            // Envoi de la requête en JSON brut (plus moderne et robuste pour Fetch)
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ idAnnonce: idAnnonce })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Erreur réseau ou HTTP");
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Basculer l'apparence du bouton
                    const icon = this.querySelector('i');
                    const isAdded = data.action === 'added';

                    const textSpan = this.querySelector('.btn-text');

                        if (isAdded) {
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                            this.classList.add('active');
                            if (textSpan) textSpan.textContent = 'Dans mes favoris';
                        } else {
                            icon.classList.remove('fas');
                            icon.classList.add('far');
                            this.classList.remove('active');
                            if (textSpan) textSpan.textContent = 'Ajouter aux favoris';

                            // Si on est sur la page "Mes Favoris", on retire la carte
                            const card = this.closest('.col-md-6');
                            if (card && window.location.pathname.includes('/favoris')) {
                            card.style.transition = 'all 0.3s ease';
                            card.style.opacity = '0';
                            card.style.transform = 'scale(0.8)';
                            setTimeout(() => card.remove(), 300);
                        }
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Impossible de mettre à jour le favori. Vérifie ta connexion ou la route.');
            });
        });
    });
});