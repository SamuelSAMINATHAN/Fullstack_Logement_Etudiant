Plateforme de logements étudiants - DOROCHO
========================================

## 1. Lancer le projet

### 1.1. Prérequis communs
- PHP 8+ installé
- Serveur MySQL/MariaDB (par exemple via XAMPP/MAMP/WAMP)
- Base de données `Dorocho SQL` créée avec les tables nécessaires (au minimum `annonce`)

Vérifiez les paramètres de connexion dans `config/config.php` :

- **DB_HOST** : hôte MySQL (souvent `localhost`)
- **DB_NAME** : nom de la base (`dorocho`)
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
   cd /Users/samsam/Documents/vscode/fullstack/Dorocho
   php -S localhost:8000 -t public
   ```
5. Ouvrir le navigateur à l’adresse :
   - `http://localhost:8000/index.php`

#### Option B – MAMP/XAMPP

1. Installer MAMP ou XAMPP pour macOS.
2. Copier le dossier `Dorocho` dans le dossier web :
   - MAMP : `Applications/MAMP/htdocs/`
3. Démarrer les serveurs Apache + MySQL depuis MAMP/XAMPP.
4. Accéder au projet dans le navigateur :
   - `http://localhost/Dorocho/public/index.php`

---

### 1.3. Lancer sur Windows

#### Option A – XAMPP/WAMP (recommandé)

1. Installer XAMPP ou WAMP.
2. Copier le dossier `Dorocho` dans le dossier web :
   - XAMPP : `C:\xampp\htdocs\Dorocho`
   - WAMP : `C:\wamp64\www\Dorocho`
3. Démarrer Apache et MySQL depuis le panneau de contrôle.
4. Créer la base `Dorocho SQL` dans phpMyAdmin et importer le script SQL.
5. Accéder au projet :
   - `http://localhost/Dorocho/public/index.php`

#### Option B – Serveur PHP intégré

1. Installer PHP pour Windows (par exemple via `choco install php` avec Chocolatey).
2. Dans un terminal (PowerShell ou CMD), depuis le dossier du projet :
   ```bash
   cd C:\chemin\vers\Dorocho
   php -S localhost:8000 -t public
   ```
3. Ouvrir :
   - `http://localhost:8000/index.php`

---

## 2. Protocole de réalisation : Approche "Data-First"

Pour garantir la cohérence technique et éviter les refontes coûteuses, nous adoptons une approche **Data-First**. L'ordre de développement doit impérativement être le suivant :

1.  **MCD / SQL (La Fondation)** : On commence par modéliser les données. Sans une base solide et des relations claires (Logement, Utilisateur, Message), le code PHP sera fragile.
2.  **Models (PHP/PDO - La Logique)** : On crée les classes dans `app/models/`. C'est ici que l'on écrit les requêtes SQL via PDO. Un Model ne doit faire qu'une chose : interagir avec la base.
3.  **Controllers (Le Chef d'Orchestre)** : On développe la logique métier dans `app/controllers/`. Le contrôleur appelle le Model pour récupérer les données, puis les transmet à la Vue.
4.  **Views (HTML/CSS - L'Interface)** : Enfin, on s'occupe du rendu visuel. La vue ne doit contenir aucune logique complexe, seulement de l'affichage.

**Pourquoi c'est vital ?** Cette approche garantit que l'interface s'adapte aux données réelles et non l'inverse. Cela évite de se retrouver avec des formulaires qui ne correspondent pas aux colonnes de la base de données.

---

## 3. Guide d'intégration MySQL

### 3.1. Configuration
Le fichier [config.php](file:///Users/samsam/Documents/vscode/fullstack/Dorocho/config/config.php) contient les constantes de connexion. Ne modifiez ce fichier que pour l'adapter à votre environnement local (identifiants MySQL).

### 3.2. Utilisation de la classe PDO
Nous utilisons une classe [Database.php](file:///Users/samsam/Documents/vscode/fullstack/Dorocho/app/core/Database.php) pour gérer la connexion unique (Singleton). Pour exécuter une requête dans un model, utilisez :
`$db = Database::getInstance();`

### 3.3. Exemple concret : models/Logement.php
Voici comment structurer une fonction pour retourner des données :

```php
<?php
// app/models/Logement.php

class Logement {
    public static function getAll() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM logement ORDER BY date_publication DESC");
        return $stmt->fetchAll(); // Retourne un tableau associatif
    }
}
```

---

## 4. Répartition stratégique des rôles

| Profil | Membres | Responsabilités |
| :--- | :--- | :--- |
| **Moteurs** | 3 membres | Backend Critique, Architecture MVC, Sécurité, Gestion de la Base de données. |
| **Soutien** | 1 membre | Frontend (HTML/CSS), Design UI/UX, Intégration des composants. |
| **Passif** | 1 membre | Documentation technique, Tests simples (formulaires), Relecture du README. |

---

## 5. Todo List (Ce qu'il reste à faire)

### 1.1 Priorité élevée
- [ ] **Page d'accueil** : Page principale du site présentant la plateforme, permettant d’accéder rapidement aux principales fonctionnalités et à la recherche de logements.
- [ ] **Inscription** : Création de compte avec différents profils (étudiant, propriétaire, administrateur).
- [ ] **Authentification** : Connexion sécurisée des utilisateurs inscrits.
- [ ] **Édition du profil** : Consultation et modification des informations personnelles par l'utilisateur.
- [ ] **FAQ** : Réponses aux questions fréquemment posées.
- [ ] **CGU et Mentions légales** : Pages informatives obligatoires.
- [ ] **Recherche simple** : Recherche par mot-clé dans la barre de recherche.
- [ ] **Backoffice - Gestion des utilisateurs** : Administration des comptes et des droits d'accès.
- [ ] **Backoffice - Gestion de la FAQ** : CRUD des questions/réponses.
- [ ] **Backoffice - Gestion des CGU/Mentions légales** : Mise à jour des contenus légaux.
- [ ] **Contact** : Formulaire de contact pour assistance ou signalement.
- [ ] **Profil complet** : Permettre de compléter les informations personnelles.
- [ ] **Gestion des annonces** : Ajout, modification et suppression par les propriétaires/organismes.
- [ ] **Respect du RGPD** : Mesures de protection des données personnelles.

### 1.2 Priorité moyenne
- [ ] **Mot de passe oublié** : Processus de réinitialisation sécurisé (e-mail).
- [ ] **Site responsive** : Adaptation automatique à tous les types d'écrans (mobile, tablette, PC).
- [ ] **Recherche avancée** : Filtres multicritères pour affiner les résultats.
- [ ] **Alertes** : Notifications lors de la publication de nouvelles annonces correspondant aux critères.
- [ ] **Avis** : Système d'évaluation et commentaires sur les annonces/propriétaires.
- [ ] **Option colocataire** : Préciser le type de logement ou recherche de colocataires.
- [ ] **Favoris** : Enregistrement d'annonces pour consultation ultérieure.
- [ ] **Partage d'offre** : Partage via lien, e-mail ou réseaux sociaux.
- [ ] **Badge "Propriétaire Vérifié"** : Renforcement de la confiance utilisateur.

### 1.3 Priorité faible
- [ ] **Messagerie interne** : Système de communication directe entre utilisateurs.
- [ ] **Site multilingue** : Accessibilité internationale.
- [ ] **Backoffice - Gestion messagerie** : Supervision des échanges par les administrateurs.
- [ ] **Backoffice - Gestion du rendu visuel** : Personnalisation des éléments visuels du site.
- [ ] **Authentification 2FA** : Sécurité renforcée lors de la connexion.

# Fullstack_Logement_Etudiant
