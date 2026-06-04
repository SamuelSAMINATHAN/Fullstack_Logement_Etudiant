<?php

if($_SERVER['SERVER_NAME'] == 'localhost') {
    /** database config **/
    define('DB_NAME', 'test');
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', 'root');
    define('DB_DRIVER', '');
    define('DB_CHARSET', 'utf8mb4');

    define('ROOT', 'http://localhost:8888/test');
    define('URLROOT', 'http://localhost:8888/test');

} else {
    // Configuration pour production (Hangar)
    define('DB_NAME', 'hangardb_heja62341');
    define('DB_HOST', '178.33.122.21');
    define('DB_USER', 'heja62341');
    define('DB_PASS', 'eVlrb03JQfyIK02CLjrNDmHr');
    define('DB_DRIVER', '');
    define('DB_CHARSET', 'utf8mb4');
    
    define('ROOT', 'https://dorocho.hangar.garageisep.com');
    define('URLROOT', 'https://dorocho.hangar.garageisep.com');
}

define('APPROOT', __DIR__ . '/app');
define('APP_NAME', 'Dorocho');
define('APP_DESCRIPTION', 'Logements étudiants');
define('DEBUG', false);

// SMTP Config
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'sadyasami2003@gmail.com');
define('SMTP_PASS', '');
define('SMTP_FROM_NAME', 'Dorocho');
