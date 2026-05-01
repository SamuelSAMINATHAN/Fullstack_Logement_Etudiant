<?php

// ============================================================
//  DATABASE.PHP — Classe de connexion et d'accès aux données
//  Projet : Plateforme de logements étudiants (PHP MVC)
//
//  ARCHITECTURE MVC :
//    Ce fichier est la couche "Core" de la base de données.
//    Tous les modèles héritent de cette classe :
//
//      class AnnonceModel extends Database { ... }
//
//    Ils accèdent alors aux méthodes via $this->select(...) etc.
//
//  POURQUOI PDO ?
//    - Plus besoin de préciser les types ("ssi") : PDO les gère
//    - Résultats disponibles directement en tableau ou en objet
//    - Même syntaxe si on change de SGBD (MySQL, SQLite...)
//    - Pas de $GLOBALS, la connexion est encapsulée dans l'objet
//
// ============================================================

class Database
{
    // ----------------------------------------------------------
    //  Paramètres de connexion
    //  NOTE : En production, utiliser des variables d'environnement
    //  ou un fichier config.php exclu du dépôt Git (.gitignore).
    // ----------------------------------------------------------
    private string $host     = DB_HOST;
    private string $dbname   = DB_NAME;
    private string $username = DB_USER;
    private string $password = DB_PASS;
    private string $charset  = DB_CHARSET;

    // L'objet PDO est stocké en propriété protégée :
    // accessible par cette classe ET par tous les modèles enfants.
    protected ?PDO $pdo = null;


    // ----------------------------------------------------------
    //  Constructeur — connexion automatique à l'instanciation
    //  ou à la première instanciation d'un modèle enfant.
    // ----------------------------------------------------------
    public function __construct()
    {
        $this->connect();
    }


    // ----------------------------------------------------------
    //  connect()
    //  Établit la connexion PDO.
    //  Appelé une seule fois dans le constructeur.
    //
    //  Options PDO :
    //    ERRMODE_EXCEPTION   → les erreurs SQL lèvent une exception
    //                          (attrapable avec try/catch dans le contrôleur)
    //    DEFAULT_FETCH_ASSOC → les résultats sont des tableaux associatifs
    //                          ['nom' => 'Dupont'] plutôt que [0 => 'Dupont']
    //    EMULATE_PREPARES    → false : vrais prepared statements côté MySQL
    // ----------------------------------------------------------
    private function connect(): void
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            // En production : logger l'erreur, ne jamais l'afficher
            error_log("[DB] Erreur de connexion : " . $e->getMessage());
            throw new Exception("Connexion base impossible");
        }
    }


    // ----------------------------------------------------------
    //  select(string $sql, array $params = []): array
    //
    //  Exécute un SELECT et retourne TOUTES les lignes.
    //  Retourne un tableau vide [] si aucune ligne trouvée.
    //
    //  NOTE : Plus besoin de préciser "ssi" etc.
    //  PDO lie les paramètres automatiquement.
    //
    //  Utilisation dans un modèle :
    //    $annonces = $this->select(
    //        "SELECT * FROM annonce WHERE localisation = ? AND prix <= ?",
    //        [$localisation, $prixMax]
    //    );
    //    // $annonces = [ ['idAnnonce'=>1, 'titre'=>'...'], [...] ]
    // ----------------------------------------------------------
    protected function select(string $sql, array $params = []): array
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("[DB] Erreur SELECT : " . $e->getMessage());
            return [];
        }
    }


    // ----------------------------------------------------------
    //  selectOne(string $sql, array $params = []): ?array
    //
    //  Exécute un SELECT et retourne UNE SEULE ligne.
    //  Retourne null si aucune ligne trouvée.
    //
    //  Utilisation dans un modèle :
    //    $user = $this->selectOne(
    //        "SELECT * FROM utilisateur WHERE email = ?",
    //        [$email]
    //    );
    //    if ($user === null) { /* non trouvé */ }
    // ----------------------------------------------------------
    protected function selectOne(string $sql, array $params = []): ?array
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result !== false ? $result : null;
        } catch (PDOException $e) {
            error_log("[DB] Erreur SELECT ONE : " . $e->getMessage());
            return null;
        }
    }


    // ----------------------------------------------------------
    //  selectAll(string $table): array
    //
    //  Récupère toutes les lignes d'une table sans filtre.
    //
    //  Sécurité : la table est validée contre une liste blanche
    //  pour éviter toute injection via le nom de table.
    //
    //  Utilisation dans un modèle :
    //    $faqs = $this->selectAll("faq");
    // ----------------------------------------------------------
    protected function selectAll(string $table): array
    {
        // Liste blanche des tables autorisées
        $allowedTables = [
            'utilisateur', 'etudiant', 'bailleur', 'administrateur',
            'annonce', 'photo_annonce', 'candidature', 'favoris',
            'avis', 'alerte', 'message', 'signalement',
            'reset_password', 'faq', 'informationlegale', 'contact'
        ];

        if (!in_array($table, $allowedTables, true)) {
            error_log("[DB] selectAll() : table non autorisée '$table'");
            return [];
        }

        return $this->select("SELECT * FROM `$table`");
    }


    // ----------------------------------------------------------
    //  insert(string $sql, array $params = []): int
    //
    //  Exécute un INSERT.
    //  Retourne le nombre de lignes insérées (1 si succès, 0 sinon).
    //
    //  Pour récupérer l'ID généré juste après :
    //    $this->insert("INSERT INTO utilisateur ...", [...]);
    //    $id = $this->lastInsertId();
    //
    //  Utilisation dans un modèle :
    //    $nb = $this->insert(
    //        "INSERT INTO utilisateur (nom, email, mdp, role) VALUES (?, ?, ?, ?)",
    //        [$nom, $email, $mdpHash, $role]
    //    );
    // ----------------------------------------------------------
    protected function insert(string $sql, array $params = []): int
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("[DB] Erreur INSERT : " . $e->getMessage());
            return 0;
        }
    }


    // ----------------------------------------------------------
    //  update(string $sql, array $params = []): int
    //
    //  Exécute un UPDATE.
    //  Retourne le nombre de lignes modifiées.
    //
    //  Utilisation dans un modèle :
    //    $nb = $this->update(
    //        "UPDATE utilisateur SET derniere_connexion = NOW() WHERE idUtilisateur = ?",
    //        [$idUtilisateur]
    //    );
    // ----------------------------------------------------------
    protected function update(string $sql, array $params = []): int
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("[DB] Erreur UPDATE : " . $e->getMessage());
            return 0;
        }
    }


    // ----------------------------------------------------------
    //  delete(string $sql, array $params = []): int
    //
    //  Exécute un DELETE.
    //  Retourne le nombre de lignes supprimées.
    //
    //  Utilisation dans un modèle :
    //    $nb = $this->delete(
    //        "DELETE FROM favoris WHERE idEtudiant = ? AND idAnnonce = ?",
    //        [$idEtudiant, $idAnnonce]
    //    );
    // ----------------------------------------------------------
    protected function delete(string $sql, array $params = []): int
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("[DB] Erreur DELETE : " . $e->getMessage());
            return 0;
        }
    }


    // ----------------------------------------------------------
    //  lastInsertId(): int
    //
    //  Retourne le dernier ID auto-incrémenté généré.
    //  À appeler immédiatement après un insert().
    //
    //  Cas typique : créer un compte utilisateur en 2 étapes
    //    1. INSERT dans `utilisateur`
    //    2. Récupérer l'ID → INSERT dans `etudiant` ou `bailleur`
    //
    //  Utilisation dans un modèle :
    //    $this->insert("INSERT INTO utilisateur ...", [...]);
    //    $idNouvelUtilisateur = $this->lastInsertId();
    //    $this->insert("INSERT INTO etudiant (idUtilisateur) VALUES (?)", [$idNouvelUtilisateur]);
    // ----------------------------------------------------------
    protected function lastInsertId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }


    // ----------------------------------------------------------
    //  sanitize(string $value): string
    //
    //  Nettoyage léger d'une valeur avant stockage en base :
    //    - trim()      : supprime les espaces inutiles
    //    - strip_tags(): supprime les balises HTML/PHP
    //
    //  IMPORTANT : Ne PAS appeler htmlspecialchars() ici.
    //  L'échappement HTML se fait à l'AFFICHAGE dans les vues,
    //  pas au stockage. Stocker du HTML encodé en base est une
    //  mauvaise pratique (données corrompues, double encodage).
    //
    //  Règle :
    //    Input  → sanitize() → base de données (données brutes propres)
    //    Base   → htmlspecialchars() dans la vue → affichage sécurisé
    //
    //  Utilisation dans un modèle ou contrôleur :
    //    $nom = $this->sanitize($_POST['nom']);
    // ----------------------------------------------------------
    protected function sanitize(string $value): string
    {
        return trim(strip_tags($value));
    }


    // ----------------------------------------------------------
    //  sanitizeArray(array $data): array
    //
    //  Applique sanitize() sur tout un tableau (ex: $_POST).
    //
    //  Utilisation :
    //    $data = $this->sanitizeArray($_POST);
    // ----------------------------------------------------------
    protected function sanitizeArray(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = $this->sanitize($value);
            }
        }
        return $data;
    }
}