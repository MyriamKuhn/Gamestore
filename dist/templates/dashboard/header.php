<?php 

use App\Tools\NavigationTools;
use App\Tools\Security;
use App\Repository\GameUserOrderRepository;

Security::userOnly();

// Récupération du contenu du panier de l'utilisateur
$cartId = $_SESSION['user']['cart_id'];
if ($cartId === 0) {
  throw new \Exception("Erreur lors de la récupération de votre panier.");
}
$gameUserOrderRepository = new GameUserOrderRepository();
$cartContent = $gameUserOrderRepository->findCartContent($cartId);

?>

<!DOCTYPE html>
<html lang="fr-FR">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Gamestore">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="<?= $_SESSION['csrf_token']; ?>">
  <!-- START : SEO -->
  <meta name="description" content="Accédez à votre espace client pour gérer vos informations personnelles, suivre vos commandes, consulter vos factures et bénéficier de nos services en ligne.">
  <meta name="keywords" content="espace client, gestion des commandes, suivi des commandes, factures, informations personnelles, services en ligne, compte client, support client">
  <!-- END : SEO -->
  <title>Espace Client - Gérer vos informations et commandes en ligne</title>
  <link rel="shortcut icon" href="./assets/images/logo_small.svg" type="image/svg+xml">
  <!-- START : Styles -->
  <link rel="stylesheet" href="./assets/css/main.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- END : Styles -->
  <!-- START : Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
  <!-- END : Fonts -->
</head>

<body>
  <!-- START : Wrapper -->
  <div class="d-flex vh-100 overflow-hidden">
    <!-- START : Header -->
    <header class="flex-shrink-0 vh-100 position-sticky top-0">
      <div class="container-fluid">
        <div class="row">
          <div class="col-auto min-vh-100 bg-white bg-opacity-75">
            <div class="pt-5 pb-5 px-sm-5 text-center">
              <a href="index.php?controller=page&action=home">
                <img src="./assets/images/logo_big.svg" alt="Logo de l'entreprise Gamestore" class="d-none d-sm-inline w-auto">
              </a>
            </div>
            <ul class="nav nav-pills flex-column mb-auto">
              <li class="nav-item mb-3">
                <a href="index.php?controller=dashboard&action=home" class="menu-link text-uppercase">
                  <i class="bi bi-house me-2"></i>
                  <span class="d-none d-sm-inline">Accueil</span>
                </a>
              </li>
              <li class="nav-item mb-3">
                <a href="index.php?controller=dashboard&action=modify" class="menu-link text-uppercase <?= NavigationTools::addActiveClass('dashboard', 'modify') ?>">
                  <i class="bi bi-person-fill me-2"></i>
                  <span class="d-none d-sm-inline">Données personnelles</span>
                </a>
              </li>
              <li class="nav-item mb-3 <?= NavigationTools::showCart($cartContent) ?>">
                <a href="index.php?controller=dashboard&action=cart" class="menu-link text-uppercase <?= NavigationTools::addActiveClass('dashboard', 'cart') ?>">
                  <i class="bi bi-cart2 me-2"></i>
                  <span class="d-none d-sm-inline">Panier</span>
                </a>
              </li>
              <li class="nav-item mb-5">
                <a href="index.php?controller=dashboard&action=orders" class="menu-link text-uppercase <?= NavigationTools::addActiveClass('dashboard', 'orders') ?>">
                  <i class="bi bi-receipt-cutoff me-2"></i>
                  <span class="d-none d-sm-inline">Commandes</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="index.php?controller=auth&action=logout" class="menu-link text-uppercase">
                  <i class="bi bi-box-arrow-in-right me-2"></i>
                  <span class="d-none d-sm-inline">Déconnexion</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </header>
    <!-- END : Header -->
    <!-- START : Div pour le contenu -->
    <div class="overflow-y-auto flex-grow-1 vh-100 d-flex flex-column">
      <!-- START : Main -->
      <main class="flex-grow-1">