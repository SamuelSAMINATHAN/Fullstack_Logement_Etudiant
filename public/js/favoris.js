/**
 * Gestion des favoris en AJAX
 */
document.addEventListener('DOMContentLoaded', function() {
    const favorisButtons = document.querySelectorAll('.btn-toggle-favoris');

    favorisButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const idAnnonce = this.dataset.id;
            const url = `${URLROOT}/favoris/toggle/${idAnnonce}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Basculer l'apparence du bouton
                        const icon = this.querySelector('i');
                        if (data.action === 'added') {
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                            this.classList.add('active');
                        } else {
                            icon.classList.remove('fas');
                            icon.classList.add('far');
                            this.classList.remove('active');
                        }
                        
                        // Optionnel : Notification Toast ou Alert
                        console.log(data.message);
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors de la mise à jour des favoris.');
                });
        });
    });
});
