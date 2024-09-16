<?php

require_once __DIR__.'/config.php';

// Sécurisation du cookie de session avec httpOnly
session_set_cookie_params([
  'lifetime' => 3600,
  'path' => '/',
  'domain' => $_SERVER['SERVER_NAME'],
  'httponly' => true
]);
// Démarrage de la session
session_start();

// Définition des chemins racines
define('_ROOTPATH_', __DIR__);
define('_TEMPLATEPATH_', __DIR__.'/templates');

// Chargement de l'autoloader
require_once __DIR__.'/Autoload.php';
Autoload::register();

// Chargement du routeur
$controller = new App\Controller\RoutingController();
$controller->route();




// JUST FOR TESTING
use App\Repository\GamePlatformRepository;

// Create an instance of GamesRepository
$gamesRepository = new GamePlatformRepository();
// Call the getGames method
$games = $gamesRepository->getAllGamesByStore(1);
// Output the result
