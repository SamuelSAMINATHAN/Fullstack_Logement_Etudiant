<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/**
 * Dorocho - Plateforme de logements étudiants
 * Point d'entrée de l'application (Front Controller)
 */

// Démarrage de la session
session_start();

// Chargement de la configuration
require_once __DIR__ . '/config/config.php';

// PSR-4 Autoloader universel (insensible à la casse pour dossiers, respecte la casse pour fichiers)
spl_autoload_register(function ($className) {
    // Vérifier si c'est une classe App\*
    if (strpos($className, 'App\\') !== 0) {
        return;
    }

    // Enlever le préfixe 'App\'
    $relativeClass = substr($className, 4);

    // Remplacer les antislash par des slash
    $path = str_replace('\\', '/', $relativeClass);

    // Extraire le dossier principal (ex: Models, Controllers, Core)
    $parts = explode('/', $path);
    $folder = array_shift($parts);
    
    // Construire le chemin du fichier
    // Dossiers en minuscules + nom de fichier original
    $file = __DIR__ . '/app/' . strtolower($folder) . '/' . implode('/', $parts) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// Analyse de l'URL pour le routage MVC
// Format attendu (via nginx) : /index.php?url=controller/action/param1/param2
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url = filter_var($url, FILTER_SANITIZE_URL);
$urlParams = explode('/', $url);


// Détermination du contrôleur (par défaut : HomeController)
$controllerName = 'PageController';
if (!empty($urlParams[0])) {
    $requestedController = ucfirst(strtolower($urlParams[0])) . 'Controller';
    $controllerFile = __DIR__ . '/app/controllers/' . $requestedController . '.php';
    if (file_exists($controllerFile)) {
        $controllerName = $requestedController;
        unset($urlParams[0]);
    }
}

// Chargement du fichier du contrôleur
$controllerPath = __DIR__ . '/app/controllers/' . $controllerName . '.php';
if (!file_exists($controllerPath)) {
    http_response_code(404);
    die("Erreur 404 : Le contrôleur $controllerName est introuvable.");
}
require_once $controllerPath;

// Instanciation du contrôleur avec le bon namespace
$fullControllerName = '\\App\\Controllers\\' . $controllerName;
if (!class_exists($fullControllerName)) {
    http_response_code(500);
    echo "Classe contrôleur \"$fullControllerName\" introuvable.";
    exit;
}
$controller = new $fullControllerName();

// Détermination de l'action (par défaut : index)
$actionName = 'home';
if (!empty($urlParams[1])) {
    $requestedAction = strtolower($urlParams[1]);
    if (method_exists($controller, $requestedAction)) {
        $actionName = $requestedAction;
        unset($urlParams[1]);
    }
}

// Les paramètres restants
$params = $urlParams ? array_values($urlParams) : [];

// Appel de la méthode du contrôleur avec les paramètres
try {
    call_user_func_array([$controller, $actionName], $params);
// Tout en bas de ton public/index.php :

} catch (\Throwable $e) {
    // On force l'affichage sauvage du vrai problème :
    echo "<h1>BORDEL DE MERDE, VOILÀ L'ERREUR :</h1>";
    echo "<p><strong>Message :</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Fichier :</strong> " . $e->getFile() . " à la ligne " . $e->getLine() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    exit; // On coupe tout ici

}
