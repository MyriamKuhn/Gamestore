<?php 

use App\Tools\NavigationTools;
use App\Tools\Security;

Security::employeOnly();

// Récupération du contenu du panier de l'utilisateur

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
  <meta name="description" content="Accédez à votre espace employé pour gérer les commandes. Modifiez le statut des commandes et analysez les statistiques de vente de votre magasin.">
  <meta name="keywords" content="espace employé, gestion des commandes, suivi des commandes, statistiques des ventes, suivi des commandes">
  <!-- END : SEO -->
  <title>Espace Employé - Gestion des Commandes</title>
  <link rel="shortcut icon" href="./assets/images/logo_small.svg" type="image/svg+xml">
  <!-- START : Styles -->
  <link rel="stylesheet" href="./assets/css/main.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
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
            <ul class="nav nav-pills flex-column mb-auto text-center text-lg-start">
              <li class="nav-item mb-5">
                <a href="index.php?controller=employe&action=home" class="menu-link text-uppercase <?= NavigationTools::addActiveClass('employe', 'home') ?>">
                  <i class="bi bi-house me-2"></i>
                  <span class="d-none d-lg-inline">Accueil</span>
                </a>
              </li>
              <li class="nav-item mb-5">
                <a href="index.php?controller=employe&action=password" class="menu-link text-uppercase <?= NavigationTools::addActiveClass('employe', 'password') ?>">
                  <i class="bi bi-key me-2"></i>
                  <span class="d-none d-lg-inline">Mot de passe</span>
                </a>
              </li>
              <li class="nav-item mb-3">
                <a href="index.php?controller=employe&action=orders" class="menu-link text-uppercase <?= NavigationTools::addActiveClass('employe', 'orders') ?>">
                  <i class="bi bi-receipt-cutoff me-2"></i>
                  <span class="d-none d-lg-inline">Gestion des commandes</span>
                </a>
              </li>
              <li class="nav-item mb-5">
                <a href="index.php?controller=employe&action=buying" class="menu-link text-uppercase <?= NavigationTools::addActiveClass('employe', 'buying') ?>">
                  <i class="bi bi-shop me-2"></i>
                  <span class="d-none d-lg-inline">Gestion des ventes</span>
                </a>
              </li>
              <li class="nav-item mb-5">
                <a href="index.php?controller=employe&action=sales" class="menu-link text-uppercase <?= NavigationTools::addActiveClass('employe', 'sales') ?>">
                  <i class="bi bi-graph-up me-2"></i>
                  <span class="d-none d-lg-inline">Statistiques des ventes</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="index.php?controller=auth&action=logout" class="menu-link text-uppercase">
                  <i class="bi bi-box-arrow-in-right me-2"></i>
                  <span class="d-none d-lg-inline">Déconnexion</span>
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