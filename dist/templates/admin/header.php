<?php 

use App\Tools\NavigationTools;
use App\Tools\Security;

Security::adminOnly();

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
  <meta name="description" content="Espace dédié aux administrateurs pour la gestion des employés, des commandes, des droits d'accès au sein du système et contrôler les flux opérationnels de l'entreprise.">
  <meta name="keywords" content="espace administrateur, gestion des commandes, gestion des utilisateurs, supervision des commandes, statistiques des ventes, contrôle des opérations">
  <!-- END : SEO -->
  <title>Espace Admin - Gestion Utilisateurs et Commandes</title>
  <link rel="shortcut icon" href="./assets/images/logo_small.svg" type="image/svg+xml">
  <!-- START : Styles -->
  <link rel="stylesheet" href="./assets/css/main.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.1.7/b-3.1.2/b-html5-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
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
            <div class="pt-5 pb-5 px-sm-3 text-center">
              <a href="index.php?controller=page&action=home">
                <img src="./assets/images/logo_big.svg" alt="Logo de l'entreprise Gamestore" class="d-none d-sm-inline w-auto">
              </a>
            </div>
            <ul class="nav nav-pills flex-column mb-auto">
              <li class="nav-item mb-5">
                <a href="index.php?controller=admin&action=home" class="menu-link text-uppercase <?= NavigationTools::addActiveClass('admin', 'home') ?>">
                  <i class="bi bi-house me-2"></i>
                  <span class="d-none d-sm-inline">Accueil</span>
                </a>
              </li>
              <li class="nav-item mb-5">
                <a href="index.php?controller=admin&action=password" class="menu-link text-uppercase <?= NavigationTools::addActiveClass('admin', 'password') ?>">
                  <i class="bi bi-key me-2"></i>
                  <span class="d-none d-sm-inline">Mot de passe</span>
                </a>
              </li>
              <li class="nav-item mb-3">
                <a href="index.php?controller=admin&action=orders" class="menu-link text-uppercase <?= NavigationTools::addActiveClass('admin', 'orders') ?>">
                  <i class="bi bi-receipt-cutoff me-2"></i>
                  <span class="d-none d-sm-inline">Commandes</span>
                </a>
              </li>
              <li class="nav-item mb-5">
                <a href="index.php?controller=admin&action=buying" class="menu-link text-uppercase <?= NavigationTools::addActiveClass('admin', 'buying') ?>">
                  <i class="bi bi-shop me-2"></i>
                  <span class="d-none d-sm-inline">Magasin</span>
                </a>
              </li>
              <li class="nav-item mb-3">
                <a href="index.php?controller=admin&action=employes" class="menu-link text-uppercase <?= NavigationTools::addActiveClass('admin', 'employes') ?>">
                  <i class="bi bi-person-lines-fill me-2"></i>
                  <span class="d-none d-sm-inline">Employés</span>
                </a>
              </li>
              <li class="nav-item mb-5">
                <a href="index.php?controller=admin&action=users" class="menu-link text-uppercase <?= NavigationTools::addActiveClass('admin', 'users') ?>">
                  <i class="bi bi-person-fill me-2"></i>
                  <span class="d-none d-sm-inline">Clients</span>
                </a>
              </li>
              <li class="nav-item mb-5">
                <a href="index.php?controller=admin&action=sales" class="menu-link text-uppercase <?= NavigationTools::addActiveClass('admin', 'sales') ?>">
                  <i class="bi bi-graph-up me-2"></i>
                  <span class="d-none d-sm-inline">Ventes</span>
                </a>
              </li>
              <li class="nav-item mb-5">
                <a href="index.php?controller=admin&action=products" class="menu-link text-uppercase <?= NavigationTools::addActiveClass('admin', 'products') ?>">
                <i class="bi bi-clipboard2 me-2"></i>
                  <span class="d-none d-sm-inline">Articles</span>
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