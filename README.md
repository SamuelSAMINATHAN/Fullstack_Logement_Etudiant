CROUS – Plateforme de logements étudiants
========================================

## 1. Lancer le projet

### 1.1. Prérequis communs
- PHP 8+ installé
- Serveur MySQL/MariaDB (par exemple via XAMPP/MAMP/WAMP)
- Base de données `crous_logements` créée avec les tables nécessaires (au minimum `annonce`)

Vérifiez les paramètres de connexion dans `config/config.php` :

- **DB_HOST** : hôte MySQL (souvent `localhost`)
- **DB_NAME** : nom de la base (`crous_logements`)
- **DB_USER** : utilisateur MySQL
- **DB_PASS** : mot de passe MySQL

---

### 1.2. Lancer sur macOS

#### Option A – PHP intégré (avec Homebrew)

1. Installer Homebrew (si nécessaire) :
   ```bash
   /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
   ```
2. Installer PHP :
   ```bash
   brew install php
   ```
3. Vérifier PHP :
   ```bash
   php -v
   ```
4. Depuis le dossier du projet :
   ```bash
   cd /Users/samsam/Documents/vscode/fullstack/CROUS
   php -S localhost:8000 -t public
   ```
5. Ouvrir le navigateur à l’adresse :
   - `http://localhost:8000/index.php`

#### Option B – MAMP/XAMPP

1. Installer MAMP ou XAMPP pour macOS.
2. Copier le dossier `CROUS` dans le dossier web :
   - MAMP : `Applications/MAMP/htdocs/`
3. Démarrer les serveurs Apache + MySQL depuis MAMP/XAMPP.
4. Accéder au projet dans le navigateur :
   - `http://localhost/CROUS/public/index.php`

---

### 1.3. Lancer sur Windows

#### Option A – XAMPP/WAMP (recommandé)

1. Installer XAMPP ou WAMP.
2. Copier le dossier `CROUS` dans le dossier web :
   - XAMPP : `C:\xampp\htdocs\CROUS`
   - WAMP : `C:\wamp64\www\CROUS`
3. Démarrer Apache et MySQL depuis le panneau de contrôle.
4. Créer la base `crous_logements` dans phpMyAdmin et importer le script SQL.
5. Accéder au projet :
   - `http://localhost/CROUS/public/index.php`

#### Option B – Serveur PHP intégré

1. Installer PHP pour Windows (par exemple via `choco install php` avec Chocolatey).
2. Dans un terminal (PowerShell ou CMD), depuis le dossier du projet :
   ```bash
   cd C:\chemin\vers\CROUS
   php -S localhost:8000 -t public
   ```
3. Ouvrir :
   - `http://localhost:8000/index.php`

---

## 2. Spécification projet – Todo list

### 2.1. Base technique
- [ ] **Initialiser la base de données**
  - [ ] Créer la base `crous_logements`
  - [ ] Créer les tables : `utilisateur`, `annonce`, `favori`, `candidature`, `message`
  - [ ] Ajouter quelques données de test (annonces, utilisateurs)
- [ ] **Finaliser la configuration**
  - [ ] Vérifier/adapter `config/config.php` (paramètres MySQL, `BASE_URL`)

### 2.2. Utilisateurs & authentification
- [ ] **Inscription utilisateur**
  - [ ] Formulaire d’inscription (étudiant / particulier / organisme)
  - [ ] Validation des champs (PHP + côté client)
  - [ ] Hash du mot de passe (password_hash)
- [ ] **Connexion / déconnexion**
  - [ ] Formulaire de connexion
  - [ ] Gestion de session en PHP
  - [ ] Lien de déconnexion
- [ ] **Gestion du profil**
  - [ ] Page « Mon profil »
  - [ ] Modification des informations de l’utilisateur

### 2.3. Gestion des annonces
- [ ] **CRUD annonces pour propriétaires (particuliers / organismes / étudiants colocataires)**
  - [ ] Formulaire de création d’annonce
  - [ ] Modification d’une annonce existante
  - [ ] Suppression / archivage
  - [ ] Liste « Mes annonces »
- [ ] **Affichage public des annonces**
  - [ ] Page liste des annonces (déjà commencée)
  - [ ] Page détail d’une annonce (déjà commencée)
  - [ ] Pagination simple si beaucoup d’annonces

### 2.4. Recherche et filtres
- [ ] **Recherche d’annonces**
  - [ ] Formulaire de recherche (ville, type de logement, budget, surface, etc.)
  - [ ] Filtrage côté serveur (requêtes SQL avec `WHERE`)
- [ ] **Recherche dynamique (Ajax – optionnel)**
  - [ ] Endpoint PHP qui renvoie un JSON d’annonces filtrées
  - [ ] JavaScript pour mettre à jour la liste sans recharger la page

### 2.5. Favoris
- [ ] **Gestion des favoris pour les étudiants**
  - [ ] Bouton « Ajouter / Retirer des favoris » sur la page d’annonce
  - [ ] Table `favori` (étudiant ↔ annonce)
  - [ ] Page « Mes favoris »
  - [ ] Interaction Ajax pour ajouter/supprimer sans rechargement (optionnel)

### 2.6. Candidatures
- [ ] **Déposer une candidature**
  - [ ] Formulaire de candidature sur la page détail d’une annonce
  - [ ] Insertion en base dans `candidature`
- [ ] **Suivi des candidatures**
  - [ ] Page « Mes candidatures » côté étudiant
  - [ ] Page « Candidatures reçues » côté propriétaire/organisme
  - [ ] Changement de statut (en_attente, acceptee, refusee)

### 2.7. Messagerie / contact
- [ ] **Contact entre étudiants et propriétaires/organismes**
  - [ ] Formulaire de message lié à une annonce
  - [ ] Stockage dans la table `message`
  - [ ] Liste des messages reçus/envoyés pour chaque utilisateur
  - [ ] Indicateur de message non lu

### 2.8. Interface & UX
- [ ] **Design général**
  - [ ] Améliorer la feuille de style `public/css/style.css`
  - [ ] Rendre le site responsive (mobile/tablette)
- [ ] **Navigation**
  - [ ] Barre de navigation avec liens principaux (Accueil, Annonces, Connexion, Inscription, Profil)
  - [ ] Affichage conditionnel des liens selon le type d’utilisateur connecté

### 2.9. Sécurité & robustesse
- [ ] **Sécurité**
  - [ ] Utiliser des requêtes préparées partout (PDO)
  - [ ] Échapper toutes les sorties (`htmlspecialchars`)
  - [ ] Vérifier les droits d’accès (un utilisateur ne peut modifier que ses propres annonces, etc.)
- [ ] **Validation**
  - [ ] Validation des formulaires côté serveur
  - [ ] Messages d’erreur clairs pour l’utilisateur

### 2.10. Améliorations possibles (optionnel)
- [ ] Système de rôles plus avancé (admin, modérateur)
- [ ] Upload de photos pour les annonces
- [ ] Système de notation/commentaires
- [ ] Export PDF ou impression des annonces / candidatures

