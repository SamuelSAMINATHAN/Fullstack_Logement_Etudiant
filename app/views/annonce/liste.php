<?php
// app/views/annonce/liste.php

// 1. TU CHARGES TON HEADER ICI (C'est lui qui contient Bootstrap et ton menu)
require APPROOT . '/views/layout/header.php'; 
?>


<main class="main-container">
    <aside class="sidebar">
        <h3><i class="fas fa-sliders-h"></i> Filtres</h3>
        
        <div class="filter-group">
            <label><i class="fas fa-map-marker-alt"></i> Localisation</label>
            <input type="text" id="searchText" placeholder="Ville, résidence...">
        </div>

        <div class="filter-group">
            <label><i class="fas fa-euro-sign"></i> Budget maximum</label>
            <div class="price-labels">
                <span>0 €</span>
                <span><strong id="priceValue">2000</strong> €</span>
                <span>3000 €</span>
            </div>
            <input type="range" id="priceSlider" min="0" max="3000" step="50" value="3000">
        </div>

        <div class="filter-group">
            <label><i class="fas fa-users"></i> Type de logement</label>
            <label class="checkbox-item">
                <input type="checkbox" class="type-filter" value="individuel">
                <span>🏠 Individuel (<span class="count" id="countIndividuel">0</span>)</span>
            </label>
            <label class="checkbox-item">
                <input type="checkbox" class="type-filter" value="couple">
                <span>💑 Couple (<span class="count" id="countCouple">0</span>)</span>
            </label>
            <label class="checkbox-item">
                <input type="checkbox" class="type-filter" value="colocation">
                <span>👥 Colocation (<span class="count" id="countColocation">0</span>)</span>
            </label>
        </div>

        <div id="moreFilters" style="display: none;">
            <div class="filter-group">
                <label><i class="fas fa-ruler-combined"></i> Surface minimum</label>
                <div class="price-labels">
                    <span>0 m²</span>
                    <span><strong id="surfaceValue">30</strong> m²</span>
                    <span>150 m²</span>
                </div>
                <input type="range" id="surfaceSlider" min="0" max="150" step="5" value="0">
            </div>

            <div class="filter-group">
                <label><i class="fas fa-door-open"></i> Nombre de pièces</label>
                <select id="roomsSelect">
                    <option value="0">Toutes</option>
                    <option value="1">Studio / 1 pièce</option>
                    <option value="2">2 pièces</option>
                    <option value="3">3 pièces</option>
                    <option value="4">4+ pièces</option>
                </select>
            </div>

            <div class="filter-group">
                <label><i class="fas fa-wrench"></i> Équipements</label>
                <label class="checkbox-item">
                    <input type="checkbox" id="furnishedFilter">
                    <span>🛋️ Meublé</span>
                </label>
                <label class="checkbox-item">
                    <input type="checkbox" id="elevatorFilter">
                    <span>⬆️ Ascenseur</span>
                </label>
                <label class="checkbox-item">
                    <input type="checkbox" id="parkingFilter">
                    <span>🅿️ Parking</span>
                </label>
                <label class="checkbox-item">
                    <input type="checkbox" id="balconyFilter">
                    <span>🌿 Balcon/Terrasse</span>
                </label>
            </div>

            <div class="filter-group">
                <label><i class="fas fa-paw"></i> Animaux</label>
                <label class="checkbox-item">
                    <input type="checkbox" id="petsFilter">
                    <span>🐾 Animaux acceptés</span>
                </label>
            </div>
        </div>

        <div class="filter-group">
            <label><i class="fas fa-wheelchair"></i> Accessibilité</label>
            <label class="checkbox-item">
                <input type="checkbox" id="pmrFilter">
                <span>♿ Logement adapté PMR</span>
            </label>
        </div>

        <button class="btn-outline" id="moreFiltersBtn">
            <i class="fas fa-plus-circle"></i> Voir plus de filtres
        </button>
        <button class="btn-outline" id="clearFiltersBtn">
            <i class="fas fa-eraser"></i> Effacer les filtres
        </button>
    </aside>

    <section class="results-section">
        <div class="results-header">
            <div class="results-count">
                <i class="fas fa-home"></i> <strong id="resultCount">0</strong> logements trouvés
            </div>
            <select class="sort-select" id="sortSelect">
                <option value="price_asc">Prix croissant</option>
                <option value="price_desc">Prix décroissant</option>
                <option value="surface_desc">Surface décroissante</option>
            </select>
        </div>
        
        <div class="listings-grid" id="listingsGrid"></div>
        <div class="pagination" id="pagination"></div>
    </section>
</main>

<div class="toast" id="toast">
    <i class="fas fa-check-circle"></i> <span id="toastMessage"></span>
</div>

<div class="scroll-top" id="scrollTop">
    <i class="fas fa-arrow-up"></i>
</div>

<script src="<?php echo ROOT; ?>/js/logements.js"></script>

<?php 
// 3. TU CHARGES TON FOOTER ICI POUR FERMER LA PAGE PROPREMENT
require APPROOT . '/views/layout/footer.php'; 
?>