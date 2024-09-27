<?php 

use App\Tools\FileTools;
use App\Tools\NavigationTools;

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
  <div class="wrapper d-flex">
    <header>
    <div class="container-fluid">
      <div class="row">
        <div class="col-auto min-vh-100 bg-white">
          <div class="pt-5 pb-5 px-sm-5 text-center">
            <a href="index.php?controller=page&action=home">
              <img src="./assets/images/logo_big.svg" alt="Logo de l'entreprise Gamestore" class="d-none d-sm-inline w-auto">
            </a>
          </div>
          <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item mb-3">
              <a href="index.php?controller=page&action=home" class="menu-link text-uppercase">
                <i class="bi bi-house me-2"></i>
                <span class="d-none d-sm-inline">Accueil</span>
              </a>
            </li>
            <li class="nav-item mb-3">
              <a href="" class="menu-link text-uppercase">
                <i class="bi bi-person-fill me-2"></i>
                <span class="d-none d-sm-inline">Données personnelles</span>
              </a>
            </li>
            <li class="nav-item mb-3">
              <a href="" class="menu-link text-uppercase">
                <i class="bi bi-cart2 me-2"></i>
                <span class="d-none d-sm-inline">Panier</span>
              </a>
            </li>
            <li class="nav-item mb-5">
              <a href="" class="menu-link text-uppercase">
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

    <div class="flex-fill d-flex flex-column">

    <main>
      <div class="container mt-5">
        <div class="row">
          <div class="col">
            <h1 class="display-4">Dashboard</h1>
            <p class="lead">Welcome to your dashboard</p>
          </div>
        </div>
      </div>

    <!-- START : Back To Top -->
    <a class="btn btn-gamestore-outline shadow px-1" href="#" id="scrollTopButton">
      <i class="bi bi-chevron-up fs-4"></i>
    </a>
    <!-- END : Back To Top -->
  </main>
  <!-- END : Main -->
      <!-- START : Footer -->
  <footer class="container-fluid bg-white">
    <div class="row row-cols-1 row-cols-md-3 justify-content-center justify-content-md-center align-items-center pt-4">
      <a href="index.php?controller=page&action=home" class="mb-4 mx-auto my-md-auto logo">
        <img src="./assets/images/logo_small.svg" alt="Logo de l'entreprise Gamestore">
      </a>
      <ul class="navbar-nav text-center">
        <li class="nav-item pb-1"><a href="index.php?controller=page&action=legal" class="text-uppercase menu-link <?= NavigationTools::addActiveClass('page', 'legal') ?>">Mentions légales</a></li>
        <li class="nav-item pb-1"><a href="index.php?controller=page&action=cgu" class="text-uppercase menu-link <?= NavigationTools::addActiveClass('page', 'cgu') ?>">Cgu</a></li>
        <li class="nav-item pb-1"><a href="index.php?controller=page&action=private" class="text-uppercase menu-link <?= NavigationTools::addActiveClass('page', 'private') ?>">Vie privée</a></li>
      </ul>
      <div class="text-center mt-3 my-md-auto">
        <a href="https://www.facebook.com/" target="_blank" class="footer-logo pe-3"><i class="bi bi-facebook fs-1 footer-logo-img"></i></a>
        <a href="https://www.instagram.com/" target="_blank" class="footer-logo pe-3"><i class="bi bi-instagram fs-1 footer-logo-img"></i></a>
        <a href="https://www.twitter.com/" target="_blank" class="footer-logo"><i class="bi bi-twitter-x fs-1 footer-logo-img"></i></a>
      </div>
    </div>
    <p class="text-center copyright py-3 m-0">© 2024 Gamestore - All rights reserved</p>
  </footer>
  <!-- END : Footer -->
    </div>
  </div>

  <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <?php 
    $scripts = FileTools::addScripts();
    foreach($scripts as $script) : ?>
      <script type="module" src="./assets/js/<?= $script ?>"></script>
    <?php endforeach; ?>
  <script src="./assets/js/scrollOnPages.js"></script>
</body>

</html>