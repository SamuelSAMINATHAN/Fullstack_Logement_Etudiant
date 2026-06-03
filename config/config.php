<?php

// Chargement des variables d'environnement (.env) si le fichier existe
if (file_exists(dirname(__DIR__) . '/.env')) {
    $lines = file(dirname(__DIR__) . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

if($_SERVER['SERVER_NAME'] == 'localhost')
{
    /** database config **/
    define('DB_NAME', $_ENV['DB_NAME'] ?? 'test');
    define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
    define('DB_USER', $_ENV['DB_USER'] ?? 'root');
    define('DB_PASS', $_ENV['DB_PASS'] ?? 'root');
    define('DB_DRIVER', '');
    define('DB_CHARSET', 'utf8mb4');

    define('ROOT', $_ENV['APP_URL'] ?? 'http://localhost:8888/test');
    
    define('URLROOT', $_ENV['APP_URL'] ?? 'http://localhost:8888/test');

} else {
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
define('APP_NAME', 'Dorocho');
define('APP_DESCRIPTION', 'Logements étudiants');
define('DEBUG', ($_ENV['DEBUG'] ?? 'true') === 'true');

// SMTP Config
define('SMTP_HOST', $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com');
define('SMTP_PORT', $_ENV['SMTP_PORT'] ?? 587);
define('SMTP_USER', $_ENV['SMTP_USER'] ?? 'sadyasami2003@gmail.com');
define('SMTP_PASS', $_ENV['SMTP_PASS'] ?? '');
define('SMTP_FROM_NAME', $_ENV['SMTP_FROM_NAME'] ?? 'Dorocho');