<?php

/** * Détection de l'environnement 
 * On peut se baser sur le SERVER_NAME ou le REMOTE_ADDR
 **/

if($_SERVER['SERVER_NAME'] == 'localhost')
{
    // ==========================================
    // CONFIGURATION POUR MAC (MAMP)
    // ==========================================
    /*
    define('DB_NAME', 'test');
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', 'root'); // MAMP utilise souvent 'root'
    define('DB_DRIVER', '');
    define('DB_CHARSET', 'utf8mb4');

    define('ROOT', 'http://localhost:8888/test/public');
    define('URLROOT', 'http://localhost:8888/test');
    */

    // ==========================================
    // CONFIGURATION POUR WINDOWS (WAMP)
    // ==========================================
    // Sous WAMP, le port est généralement 80 (donc invisible) 
    // et le mot de passe root est souvent vide ('')
    
    define('DB_NAME', 'test');
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', ''); // WAMP n'a pas de mot de passe par défaut
    define('DB_DRIVER', '');
    define('DB_CHARSET', 'utf8mb4');

    define('ROOT', 'http://localhost/test/public');
    define('URLROOT', 'http://localhost/test');

} else {
    // ==========================================
    // CONFIGURATION PRODUCTION (EN LIGNE)
    // ==========================================
    define('DB_NAME', 'nom_de_votre_bdd');
    define('DB_HOST', 'localhost');
    define('DB_USER', 'votre_user');
    define('DB_PASS', 'votre_password');
    define('DB_DRIVER', '');
    define('DB_CHARSET', 'utf8mb4');
    define('ROOT', 'https://www.yourwebsite.com');
    define('URLROOT', 'https://www.yourwebsite.com');
}

// Paramètres globaux (communs à tous)
define('APPROOT', dirname(__DIR__) . '/app');
define('APP_NAME', 'Test');
define('APP_DESCRIPTION', 'Logements étudiants');
define('DEBUG', true);