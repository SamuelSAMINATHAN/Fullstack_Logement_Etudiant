<?php

if($_SERVER['SERVER_NAME'] == 'localhost')
{
    /** database config **/
    define('DB_NAME', 'test');
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', 'root');
    define('DB_DRIVER', '');
    define('DB_CHARSET', 'utf8mb4');

    // À adapter selon votre configuration locale
    // Exemple pour MAMP: http://localhost:8888/test
    // Exemple pour XAMPP: http://localhost/test
    define('ROOT', 'http://localhost:8888/test');
    
    define('URLROOT', 'http://localhost:8888/test');

} else {
    // Configuration pour production
    define('DB_NAME', 'test');
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', 'root');
    define('DB_DRIVER', '');
    define('DB_CHARSET', 'utf8mb4');
    define('ROOT', 'https://www.yourwebsite.com');
    define('URLROOT', 'https://www.yourwebsite.com');
}

define('APPROOT', dirname(__DIR__) . '/app');
define('APP_NAME', 'Test');
define('APP_DESCRIPTION', 'Logements étudiants');
define('DEBUG', true);
