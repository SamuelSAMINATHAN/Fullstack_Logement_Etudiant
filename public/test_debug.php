<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('APPROOT', dirname(__DIR__) . '/app');
require_once dirname(__DIR__) . '/config/config.php';

// Autoloader minimal pour le test
require_once APPROOT . '/core/Database.php';
require_once APPROOT . '/core/Model.php';
require_once APPROOT . '/core/Security.php';

use App\Core\Security;

echo "<h1>--- SCRIPT DE DIAGNOSTIC RADICAL ---</h1>";

// 1. TEST DE LA CLASSE SECURITY
echo "<h2>1. Test de ta classe Security existante</h2>";
$pass_test = "DorochoAdmin2026";
$hash_local = Security::hashPassword($pass_test);
echo "Mot de passe testé : <code>$pass_test</code><br>";
echo "Hash généré par ta classe Security : <code>$hash_local</code><br>";

if (Security::verifyPassword($pass_test, $hash_local)) {
    echo "Résultat : <strong style='color:green;'>SUCCÈS ✅ (Ta classe Security fonctionne parfaitement en local)</strong><br>";
} else {
    echo "Résultat : <strong style='color:red;'>ÉCHEC ❌ (Il y a un problème interne avec PHP ou l'extension d'encodage de ton MAMP !)</strong><br>";
}

// 2. RECUPÉRATION EN BDD VIA PDO PUR
echo "<h2>2. Vérification brute de ce qui est stocké en BDD</h2>";
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    $stmt = $pdo->prepare("SELECT * FROM administrateur WHERE login = 'admin'");
    $stmt->execute();
    $admin = $stmt->fetch();
    
    if ($admin) {
        echo "Admin trouvé en BDD !<br>";
        echo "Login stocké : <code>" . $admin['login'] . "</code><br>";
        echo "Hash stocké : <code>" . $admin['motDePasse'] . "</code><br>";
        echo "Longueur du hash en BDD : " . strlen($admin['motDePasse']) . " caractères<br>";
        
        // Test croisé
        echo "<br><strong>Test de comparaison direct :</strong><br>";
        if (password_verify($pass_test, $admin['motDePasse'])) {
            echo "password_verify natif : <strong style='color:green;'>VALIDE ✅</strong><br>";
        } else {
            echo "password_verify natif : <strong style='color:red;'>INCORRECT ❌</strong><br>";
        }
    } else {
        echo "<strong style='color:red;'>Aucun utilisateur avec le login 'admin' n'existe dans la table !</strong><br>";
    }
} catch (Exception $e) {
    echo "Erreur de connexion BDD : " . $e->getMessage();
}

// 3. LA SOLUTION DE SECOURS AUTOMATIQUE
echo "<h2>3. Réparation automatique forcée</h2>";
if ($admin) {
    echo "Génération d'un nouveau hash 100% compatible avec ton MAMP actuel...<br>";
    $nouveau_hash = Security::hashPassword('DorochoAdmin2026');
    
    $update = $pdo->prepare("UPDATE administrateur SET motDePasse = ? WHERE login = 'admin'");
    $update->execute([$nouveau_hash]);
    
    echo "<strong style='color:blue;'>La BDD a été mise à jour avec le hash natif de ta machine !</strong><br>";
    echo "Tu peux maintenant supprimer ce fichier de débug et réessayer de te connecter normalement.";
}