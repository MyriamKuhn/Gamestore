<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'].'/error.log');

require_once __DIR__.'/config.php';

// Sécurisation du cookie de session avec httpOnly
session_set_cookie_params(['lifetime' => 3600,
  'path' => '/',
  'domain' => $_SERVER['SERVER_NAME'],
  //'secure' => true,
  'httponly' => true,
  'samesite' => 'Strict'
]);
// Démarrage de la session
session_start();
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

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
use App\Repository\UserRepository;

// Create an instance of GamesRepository
$gamesRepository = new UserRepository();
// Call the getGames method
$games = $gamesRepository->checkToken('75b398ba37f36ba5f0ee1afcd6b05c48cf1f316af646287e2c55c3f8d0d6453f08388540b3c50ea1ab2eee41c5b4236cf828');
// Output the result
