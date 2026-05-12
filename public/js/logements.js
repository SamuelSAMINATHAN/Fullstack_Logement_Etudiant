// Configuration - Le chemin vers mon fichier API
const API_URL = 'api/logements.php';    
let allListings = [];                   // Tous les logements chargés
let currentPage = 1;                    // Page où on se trouve (1 par défaut)
const itemsPerPage = 6;                 // 6 annonces par page
let timeoutId;                          
let moreFiltersVisible = false;         // Les filtres avancés sont cachés au début

// RÉCUPÉRATION DES ÉLÉMENTS DU HTML
const listingsGrid = document.getElementById('listingsGrid');       // La grille des annonces
const searchInput = document.getElementById('searchText');          // Champ recherche dans sidebar
const globalSearch = document.getElementById('globalSearch');       // Grande barre de recherche
const priceSlider = document.getElementById('priceSlider');         // Slider prix
const priceValue = document.getElementById('priceValue');           // Valeur prix affichée
const surfaceSlider = document.getElementById('surfaceSlider');     // Slider surface
const surfaceValue = document.getElementById('surfaceValue');       // Valeur surface affichée
const typeCheckboxes = document.querySelectorAll('.type-filter');   // Checkboxes (individuel, couple, colocation)
const petsFilter = document.getElementById('petsFilter');           // Filtre animaux
const furnishedFilter = document.getElementById('furnishedFilter'); // Filtre meublé
const elevatorFilter = document.getElementById('elevatorFilter');   // Filtre ascenseur
const parkingFilter = document.getElementById('parkingFilter');     // Filtre parking
const balconyFilter = document.getElementById('balconyFilter');     // Filtre balcon
const roomsSelect = document.getElementById('roomsSelect');         // Sélecteur nombre de pièces
const clearBtn = document.getElementById('clearFiltersBtn');        // Bouton pour tout effacer
const moreFiltersBtn = document.getElementById('moreFiltersBtn');   // Bouton "voir plus de filtres"
const moreFiltersDiv = document.getElementById('moreFilters');      // Filtres cachés
const sortSelect = document.getElementById('sortSelect');           // Menu déroulant tri
const toast = document.getElementById('toast');                     // Notification popup
const scrollTop = document.getElementById('scrollTop');             // Bouton remonter en haut
const pmrFilter = document.getElementById('pmrFilter');             // Filtre accessibilité PMR

// NOTIFICATIONS - Pour informer l'utilisateur
function showToast(message, isError = false) {
    const toastMsg = document.getElementById('toastMessage');
    if (toastMsg) {
        toastMsg.textContent = message;
        if (toast) {
            // La couleur change si c'est une erreur
            toast.style.backgroundColor = isError ? '#dc3545' : '#00887A';
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
            // Disparaît au bout de 3 sec
        }
    }
}

// BOUTON REMONTER EN HAUT
window.addEventListener('scroll', () => {
    if (scrollTop) {
        if (window.scrollY > 300) {             // Après 300px de défilement
            scrollTop.classList.add('show');
        } else {
            scrollTop.classList.remove('show');
        }
    }
});

if (scrollTop) {
    scrollTop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });         // Remonte en douceur
    });
}

// Filtre avancée
if (moreFiltersBtn && moreFiltersDiv) {
    moreFiltersBtn.addEventListener('click', function() {
        moreFiltersVisible = !moreFiltersVisible;
        moreFiltersDiv.style.display = moreFiltersVisible ? 'block' : 'none';
        // Change le texte du bouton en fonction de l'état
        moreFiltersBtn.innerHTML = moreFiltersVisible ? 
            '<i class="fas fa-minus-circle"></i> Voir moins de filtres' : 
            '<i class="fas fa-plus-circle"></i> Voir plus de filtres';
    });
}

// CHARGEMENT DES ANNONCES
async function loadListings() {
    try {
        showSkeleton(true);         // Affiche un écran de chargement
        
        // Récupère tous les filtres sélectionnés par l'utilisateur

        const searchTerm = searchInput ? searchInput.value : '';
        const maxPrice = priceSlider ? priceSlider.value : '';
        const minSurface = surfaceSlider ? surfaceSlider.value : '';
        const selectedTypes = Array.from(typeCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
        const selectedRooms = roomsSelect ? roomsSelect.value : '0';
        
        const params = new URLSearchParams();
        
        // Construis l'URL avec tous les paramètres
        if (searchTerm) params.append('search', searchTerm);
        if (maxPrice && maxPrice !== '3000') params.append('price_max', maxPrice);
        if (minSurface && minSurface !== '0') params.append('surface_min', minSurface);
        if (selectedTypes.length) params.append('types', selectedTypes.join(','));
        if (selectedRooms !== '0') params.append('rooms', selectedRooms);
        
        // Filtres équipements
        if (furnishedFilter && furnishedFilter.checked) params.append('meuble', 'true');
        if (elevatorFilter && elevatorFilter.checked) params.append('ascenseur', 'true');
        if (parkingFilter && parkingFilter.checked) params.append('parking', 'true');
        if (balconyFilter && balconyFilter.checked) params.append('balcony', 'true');
        if (petsFilter && petsFilter.checked) params.append('animaux', 'true');
        if (pmrFilter && pmrFilter.checked) params.append('pmr', 'true');
        
        console.log('Paramètres envoyés:', params.toString());
        
        const response = await fetch(`${API_URL}?${params.toString()}`);
        const result = await response.json();
        
        console.log('Résultat:', result);       // Pour déboguer
        
        if (result.success) {
            allListings = result.data;
            updateCounters(allListings);        // Met à jour les chiffres (Individuel, Couple, Colocation)
            applySorting();                     // Trie selon le choix de l'utilisateur
            const resultCount = document.getElementById('resultCount');
            if (resultCount) resultCount.textContent = allListings.length;
        } else {
            showError(result.error || 'Erreur');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showError('Erreur de connexion');
    } finally {
        showSkeleton(false);                    // Enlève l'écran de chargement

    }
}

// TRI - Pour ranger les annonces
function applySorting() {
    if (!sortSelect) return;
    const sortValue = sortSelect.value;
    let sorted = [...allListings];
    
    switch(sortValue) {
        case 'price_asc':
            sorted.sort((a, b) => a.prix - b.prix);         // Du moins cher au plus cher

            break;
        case 'price_desc':
            sorted.sort((a, b) => b.prix - a.prix);         // Du plus cher au moins cher
            break;
        case 'surface_desc':
            sorted.sort((a, b) => b.surface - a.surface);   // Le plus grand d'abord
            break;
    }
    
    displayListings(sorted);
}

// AFFICHAGE DES ANNONCE
function displayListings(listings) {
    if (!listingsGrid) return;
    
    // Pagination : coupe le tableau pour n'afficher que la page actuelle
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const paginatedListings = listings.slice(start, end);
    
    // Si y'a rien à afficher
    if (!paginatedListings || paginatedListings.length === 0) {
        listingsGrid.innerHTML = `
            <div class="no-results" style="grid-column: 1/-1; text-align: center; padding: 60px;">
                <i class="fas fa-search" style="font-size: 48px; color: #6c757d; margin-bottom: 20px;"></i>
                <h3>Aucun logement trouvé</h3>
                <p>Essayez de modifier vos critères de recherche</p>
            </div>
        `;
        const paginationDiv = document.getElementById('pagination');
        if (paginationDiv) paginationDiv.innerHTML = '';
        return;
    }

    // Crée le HTML pour chaque annonce
    listingsGrid.innerHTML = paginatedListings.map(listing => {
        // Détermine le type d'annonce (individuel, couple, colocation)
        let typeIcon = '🏠';
        let typeLabel = 'Individuel';
        
        if (listing.estColocation === 1) {
            typeIcon = '👥';
            typeLabel = 'Colocation';
        } else if (listing.nbPieces >= 2) {
            typeIcon = '💑';
            typeLabel = 'Couple';
        }
        
        // Les petits badges d'équipements
        let equipmentsHtml = '';
        if (listing.meuble) equipmentsHtml += '<span class="feature-tag"><i class="fas fa-couch"></i> Meublé</span>';
        if (listing.pmr) equipmentsHtml += '<span class="feature-tag"><i class="fas fa-wheelchair"></i> PMR</span>';
        if (listing.ascenseur) equipmentsHtml += '<span class="feature-tag"><i class="fas fa-arrow-up"></i> Ascenseur</span>';
        if (listing.animaux) equipmentsHtml += '<span class="feature-tag"><i class="fas fa-paw"></i> Animaux acceptés</span>';
        
        const photoUrl = listing.photo || 'https://picsum.photos/id/106/400/300';
        
        // Structure complète d'une carte
        return `
            <div class="card" data-id="${listing.idAnnonce}" onclick="window.viewDetails(${listing.idAnnonce})">
                <div class="card-image" style="background-image: url('${photoUrl}'); background-size: cover; background-position: center;">
                    <img src="${photoUrl}" alt="${escapeHtml(listing.titre)}" style="opacity: 0; width: 100%; height: 100%;">
                    <div class="card-badge">${typeIcon} ${typeLabel}</div>
                    <button class="heart-btn heart-empty" onclick="event.stopPropagation(); window.toggleHeart(this)">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
                <div class="card-content">
                    <div class="price">${parseInt(listing.prix).toLocaleString()} € <small>/mois</small></div>
                    <h3 class="card-title">${escapeHtml(listing.titre || 'Logement')}</h3>
                    <div class="details">
                        <span><i class="fas fa-door-open"></i> ${listing.nbPieces} pièce(s)</span>
                        <span><i class="fas fa-ruler-combined"></i> ${listing.surface} m²</span>
                    </div>
                    <div class="address">
                        <i class="fas fa-map-marker-alt"></i> ${escapeHtml(listing.localisation)}
                    </div>
                    <div class="features">${equipmentsHtml}</div>
                    <button class="btn-details" onclick="event.stopPropagation(); window.viewDetails(${listing.idAnnonce})">
                        <i class="fas fa-arrow-right"></i> Voir les détails
                    </button>
                </div>
            </div>
        `;
    }).join('');            // .join('') pour transformer le tableau en une seule chaîne
    
    setupPagination(listings.length);
}

// PAGINATION 
function setupPagination(totalItems) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const paginationDiv = document.getElementById('pagination');
    
    if (!paginationDiv) return;
    if (totalPages <= 1) {
        paginationDiv.innerHTML = '';   // Pas besoin de pagination si une seule page
        return;
    }
    
    let paginationHTML = `<button onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}><i class="fas fa-chevron-left"></i></button>`;
    
    // Affiche au maximum 5 pages
    for (let i = 1; i <= Math.min(totalPages, 5); i++) {
        paginationHTML += `<button onclick="changePage(${i})" class="${currentPage === i ? 'active' : ''}">${i}</button>`;
    }
    
    if (totalPages > 5) {
        paginationHTML += `<span style="padding: 0 10px;">...</span>`;
        paginationHTML += `<button onclick="changePage(${totalPages})">${totalPages}</button>`;
    }
    
    paginationHTML += `<button onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}><i class="fas fa-chevron-right"></i></button>`;
    
    paginationDiv.innerHTML = paginationHTML;
}

// Fonction pour changer de page
window.changePage = function(page) {
    currentPage = page;
    applySorting();
    window.scrollTo({ top: 0, behavior: 'smooth' });        // Remonte en haut

};

// COMPTEURS - Pour les types de logement
function updateCounters(listings) {
    let individuels = 0, couples = 0, coloc = 0;
    listings.forEach(l => {
        if (l.estColocation === 1) coloc++;
        else if (l.nbPieces >= 2) couples++;
        else individuels++;
    });
    
    const countIndividuel = document.getElementById('countIndividuel');
    const countCouple = document.getElementById('countCouple');
    const countColocation = document.getElementById('countColocation');
    
    if (countIndividuel) countIndividuel.textContent = individuels;
    if (countCouple) countCouple.textContent = couples;
    if (countColocation) countColocation.textContent = coloc;
}

// FONCTIONS UTILITAIRES
// Évite les failles XSS
function escapeHtml(str) {
    if (!str) return '';
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

// J'aime/Je n'aime pas
window.toggleHeart = function(btn) {
    const icon = btn.querySelector('i');
    if (icon.classList.contains('far')) {
        icon.classList.remove('far');
        icon.classList.add('fas');
        showToast('Ajouté aux favoris ❤️');
    } else {
        icon.classList.remove('fas');
        icon.classList.add('far');
        showToast('Retiré des favoris 💔');
    }
};

// Voir les détails d'une annonce
window.viewDetails = function(id) {
    showToast(`Chargement des détails du logement #${id}`);
};

// Écran de chargement (skeleton)
function showSkeleton(show) {
    if (!listingsGrid) return;
    if (show) {
        listingsGrid.innerHTML = '<div style="text-align:center; padding:40px;">⏳ Chargement des annonces...</div>';
    }
}

// Afficher une erreur à l'utilisateur
function showError(message) {
    if (!listingsGrid) return;
    listingsGrid.innerHTML = `<div class="no-results" style="text-align:center; padding:60px;"><i class="fas fa-exclamation-triangle" style="font-size:48px;color:#dc3545;"></i><h3>Erreur</h3><p>${message}</p></div>`;
}

// Recherche avec délai pour pas surcharger
function handleSearch() {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
        currentPage = 1;
        loadListings();
    }, 500);
}

// RÉINITIALISATION - Quand on clique sur "Effacer"
function clearFilters() {
    // Remets tous les filtres à leur valeur par défaut
    if (searchInput) searchInput.value = '';
    if (globalSearch) globalSearch.value = '';
    if (priceSlider) priceSlider.value = '3000';
    if (priceValue) priceValue.textContent = '3000';
    if (surfaceSlider) surfaceSlider.value = '0';
    if (surfaceValue) surfaceValue.textContent = '0';
    typeCheckboxes.forEach(cb => cb.checked = false);
    if (petsFilter) petsFilter.checked = false;
    if (furnishedFilter) furnishedFilter.checked = false;
    if (elevatorFilter) elevatorFilter.checked = false;
    if (parkingFilter) parkingFilter.checked = false;
    if (balconyFilter) balconyFilter.checked = false;
    if (pmrFilter) pmrFilter.checked = false;
    if (roomsSelect) roomsSelect.value = '0';
    // Efface aussi les filtres sauvegardés dans le navigateur
    localStorage.removeItem('dorocho_filters');
    showToast('Filtres réinitialisés');
    loadListings();
}

// MISE À JOUR DES SLIDERS (prix et surface)
function updatePriceDisplay() {
    if (priceValue) priceValue.textContent = priceSlider.value;
    handleSearch();
}

function updateSurfaceDisplay() {
    if (surfaceValue) surfaceValue.textContent = surfaceSlider.value;
    handleSearch();
}

// Sauvegarder tous les filtres dans localStorage
function saveFiltersState() {
    const filtersState = {
        // Filtres texte et sliders
        searchText: searchInput?.value || '',
        globalSearch: globalSearch?.value || '',
        priceMax: priceSlider?.value || '3000',
        surfaceMin: surfaceSlider?.value || '0',
        
        // Checkbox types
        selectedTypes: Array.from(typeCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value),
        
        // Équipements
        elevator: elevatorFilter?.checked || false,
        parking: parkingFilter?.checked || false,
        balcony: balconyFilter?.checked || false,
        furnished: furnishedFilter?.checked || false,
        pets: petsFilter?.checked || false,
        pmr: pmrFilter?.checked || false,
        
        // Sélecteurs
        rooms: roomsSelect?.value || '0',
        sort: sortSelect?.value || 'price_asc',
        
        // Filtres supplémentaires visibles
        moreFiltersVisible: moreFiltersVisible
    };
    
    localStorage.setItem('dorocho_filters', JSON.stringify(filtersState));
}

// Charger l'état des filtres depuis localStorage
function loadFiltersState() {
    const saved = localStorage.getItem('dorocho_filters');
    if (!saved) return false;
    
    try {
        const filters = JSON.parse(saved);
        
        // Restaurer les champs texte
        if (searchInput) searchInput.value = filters.searchText || '';
        if (globalSearch) globalSearch.value = filters.globalSearch || '';
        
        // Restaurer les sliders
        if (priceSlider) priceSlider.value = filters.priceMax || '3000';
        if (priceValue) priceValue.textContent = filters.priceMax || '3000';
        if (surfaceSlider) surfaceSlider.value = filters.surfaceMin || '0';
        if (surfaceValue) surfaceValue.textContent = filters.surfaceMin || '0';
        
        // Restaurer les types de logement
        const selectedTypes = filters.selectedTypes || [];
        typeCheckboxes.forEach(cb => {
            cb.checked = selectedTypes.includes(cb.value);
        });
        
        // Restaurer les équipements
        if (elevatorFilter) elevatorFilter.checked = filters.elevator || false;
        if (parkingFilter) parkingFilter.checked = filters.parking || false;
        if (balconyFilter) balconyFilter.checked = filters.balcony || false;
        if (furnishedFilter) furnishedFilter.checked = filters.furnished || false;
        if (petsFilter) petsFilter.checked = filters.pets || false;
        if (pmrFilter) pmrFilter.checked = filters.pmr || false;
        
        // Restaurer les sélecteurs
        if (roomsSelect) roomsSelect.value = filters.rooms || '0';
        if (sortSelect) sortSelect.value = filters.sort || 'price_asc';
        
        // Restaurer l'état des filtres supplémentaires
        if (filters.moreFiltersVisible && moreFiltersDiv) {
            moreFiltersVisible = true;
            moreFiltersDiv.style.display = 'block';
            if (moreFiltersBtn) moreFiltersBtn.innerHTML = '<i class="fas fa-minus-circle"></i> Voir moins de filtres';
        }
        
        return true;
    } catch (e) {
        console.error('Erreur chargement filtres:', e);
        return false;
    }
}

// Fonction helper pour sauvegarder ET recharger
function onFilterChange() {
    currentPage = 1;
    loadListings();
}

// Tous les événements qui déclenchent une sauvegarde
if (searchInput) {
    searchInput.addEventListener('input', () => {
        saveFiltersState();
        onFilterChange();
    });
}

if (priceSlider) {
    priceSlider.addEventListener('input', () => {
        updatePriceDisplay();
        saveFiltersState();
    });
}

if (surfaceSlider) {
    surfaceSlider.addEventListener('input', () => {
        updateSurfaceDisplay();
        saveFiltersState();
    });
}

typeCheckboxes.forEach(cb => {
    cb.addEventListener('change', () => {
        saveFiltersState();
        onFilterChange();
    });
});

if (elevatorFilter) {
    elevatorFilter.addEventListener('change', () => {
        saveFiltersState();
        onFilterChange();
    });
}

if (parkingFilter) {
    parkingFilter.addEventListener('change', () => {
        saveFiltersState();
        onFilterChange();
    });
}

if (balconyFilter) {
    balconyFilter.addEventListener('change', () => {
        saveFiltersState();
        onFilterChange();
    });
}

if (furnishedFilter) {
    furnishedFilter.addEventListener('change', () => {
        saveFiltersState();
        onFilterChange();
    });
}

if (petsFilter) {
    petsFilter.addEventListener('change', () => {
        saveFiltersState();
        onFilterChange();
    });
}

if (pmrFilter) {
    pmrFilter.addEventListener('change', () => {
        saveFiltersState();
        onFilterChange();
    });
}

if (roomsSelect) {
    roomsSelect.addEventListener('change', () => {
        saveFiltersState();
        onFilterChange();
    });
}

if (sortSelect) {
    sortSelect.addEventListener('change', () => {
        saveFiltersState();
        applySorting();
    });
}

// Event listeners
if (searchInput) searchInput.addEventListener('input', handleSearch);
// Barre de recherche principale
if (globalSearch) {
    globalSearch.addEventListener('input', function(e) {
        if (searchInput) searchInput.value = e.target.value;
        handleSearch();
    });
}
if (priceSlider) priceSlider.addEventListener('input', updatePriceDisplay);
if (surfaceSlider) surfaceSlider.addEventListener('input', updateSurfaceDisplay);
typeCheckboxes.forEach(cb => cb.addEventListener('change', () => { currentPage = 1; loadListings(); }));
if (petsFilter) petsFilter.addEventListener('change', () => { currentPage = 1; loadListings(); });
if (furnishedFilter) furnishedFilter.addEventListener('change', () => { currentPage = 1; loadListings(); });
if (elevatorFilter) elevatorFilter.addEventListener('change', () => { currentPage = 1; loadListings(); });
if (parkingFilter) parkingFilter.addEventListener('change', () => { currentPage = 1; loadListings(); });
if (balconyFilter) balconyFilter.addEventListener('change', () => { currentPage = 1; loadListings(); });
if (pmrFilter) pmrFilter.addEventListener('change', () => { currentPage = 1; loadListings(); });
if (roomsSelect) roomsSelect.addEventListener('change', () => { currentPage = 1; loadListings(); });
if (sortSelect) sortSelect.addEventListener('change', applySorting);
if (clearBtn) clearBtn.addEventListener('click', clearFilters);

const searchBtn = document.getElementById('searchBtn');
if (searchBtn) {
    searchBtn.addEventListener('click', () => { currentPage = 1; loadListings(); });
}

// Initialize
if (priceSlider) updatePriceDisplay();
if (surfaceSlider) updateSurfaceDisplay();

// Restaurer l'état des filtres
const hasSavedFilters = loadFiltersState();

// Charger les annonces (avec les filtres restaurés)
loadListings();
