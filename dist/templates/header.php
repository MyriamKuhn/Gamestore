<?php

use App\Tools\NavigationTools;
use App\Tools\Security;

?>

<!DOCTYPE html>
<html lang="fr-FR">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- START : SEO -->
  <meta name="description" content="<?= NavigationTools::addMetas()['description'] ?>">
  <meta property="og:title" content="<?= NavigationTools::addMetas()['title'] ?>">
  <meta property="og:description" content="<?= NavigationTools::addMetas()['description'] ?>">
  <meta property="og:image" content="<?= NavigationTools::addMetas()['image'] ?>">
  <meta name="keywords" content="<?= NavigationTools::addMetas()['keywords'] ?>">
  <!-- END : SEO -->
  <title><?= NavigationTools::addMetas()['title'] ?></title>
  <link rel="shortcut icon" href="./assets/images/logo_small.svg" type="image/svg+xml">
  <!-- START : Styles -->
  <link rel="stylesheet" href="./assets/css/main.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <!-- END : Styles -->
  <!-- START : Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
  <!-- END : Fonts -->
</head>

<body>
  <div class="wrapper d-flex flex-column">
  <!-- START : Header -->
  <header>
    <nav class="navbar navbar-expand-lg bg-white fixed-top bg-opacity-75 nav-shadow" id="navbar-opacity">
      <div class="container-fluid">
        <a class="navbar-brand ps-lg-5" href="index.php?controller=page&action=home">
          <img src="./assets/images/logo_big.svg" alt="Logo de l'entreprise Gamestore" width="180">
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <i class="toggler-logo bi bi-dpad-fill fs-1"></i>
        </button>
        <div class="collapse navbar-collapse justify-content-lg-end" id="navbarNav">
          <ul class="navbar-nav align-items-center pt-2 pt-lg-0">
          <li class="nav-item pb-2 pb-lg-0 px-lg-4">
              <a class="menu-link text-uppercase <?= NavigationTools::addActiveClass('page', 'home') ?>" href="index.php?controller=page&action=home">Accueil</a>
            </li>
            <li class="nav-item px-lg-4">
              <a class="menu-link text-uppercase <?= NavigationTools::addActiveClass('games', 'list') ?>" href="index.php?controller=games&action=list">Nos jeux vidéos</a>
            </li>
          </ul>
          <div class="d-flex flex-column flex-lg-row justify-content-center align-items-center p-3 py-lg-0 pe-lg-0 position-relative">
            <?php if (Security::isLogged()) : ?>
              <a href="index.php?controller=user&action=logout" class="btn btn-gamestore text-uppercase shadow me-lg-5">Se déconnecter</a>
              <a href="#" class="nav-link navbar-cart pt-2 align-self-end me-lg-5 fw-bold fs-5"><sub>0</sub><i class="bi bi-cart2 fs-1 navbar-cart-img"></i></a>
            <?php else : ?>
            <a href="/index.php?controller=auth&action=login" class="btn btn-gamestore text-uppercase shadow me-lg-5 mb-2 mb-lg-0 <?= NavigationTools::addActiveClass('auth', 'login') ?>">Se connecter</a>
            <a href="/index.php?controller=user&action=register" class="btn btn-gamestore text-uppercase shadow me-lg-5 <?= NavigationTools::addActiveClass('auth', 'register') ?>">S'inscrire</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </nav>
  </header>
  <!-- END : Header -->