<?php

use App\Tools\Security;
use App\Tools\FileTools;

require_once _TEMPLATEPATH_ . '/header.php';

$presentation = FileTools::getImagesAsCategory('presentation', $game['images']);
$carousel = FileTools::getImagesAsCategory('carousel', $game['images']);

?>

<!-- START : Main -->
<main class="container my-4 main">
  <section class="mt-2">

    <?= isset($_SESSION['user']) && ($_SESSION['user']['role'] == _ROLE_USER_) ? '<div id="sessionDataId" data-session-user="' . Security::secureInput($_SESSION['user']['id']) . '"></div>' : '' ?>
    <?= isset($_SESSION['user']) && ($_SESSION['user']['role'] == _ROLE_USER_) ? '<div id="sessionDataStore" data-session-store="' . Security::secureInput($_SESSION['user']['store_id']) . '"></div>' : '' ?>

    <div class="d-flex justify-content-between gamestore-title">
      <h2 class="text-uppercase"><?= Security::secureInput($game['game_name']) ?></h2>
    </div>
  </section>
  <!-- Première section de présentation -->
  <section class="py-4 row row-cols-1 row-cols-xl-2 mx-lg-5 mx-xl-0">
    <!-- Nom du jeu -->
    <div>
      <img class="mx-md-auto image-presentation" src="<?= Security::secureInput(_GAMES_IMAGES_FOLDER_ . reset($presentation)) ?>" alt="<?= Security::secureInput($game['game_name']) ?>" loading="lazy">
    </div>
    <!-- Prix  -->
    <div class="pt-3 pt-lg-0 my-auto mx-lg-auto">
      <div class="col-12 d-flex flex-column align-items-center pb-3">
        <span class="price-show" id="price"><!-- Emplacement du prix --></span>
        <div id="price-container">
          <span id="discount"><!-- Emplacement de la promo --></span><span class="text-decoration-line-through full-price py-auto ps-1" id="oldprice"><!-- Emplacement du vieux prix --></span>
        </div>
      </div>
      <!-- Plateformes et locations -->
      <div class="input-group">
        <!-- Platforms -->
        <button class="btn btn-gamestore-outline-select text-uppercase dropdown-toggle d-flex justify-content-center align-items-center w-50 text-wrap" type="button" id="platforms" data-bs-toggle="dropdown" aria-expanded="false">
          <!-- Emplacement du texte de la plateforme sélectionnée -->
        </button>
        <ul class="dropdown-menu" aria-labelledby="platforms" id="menu-platform">
          <!-- Emplacement du menu des plateformes -->
        </ul>
        <!-- Locations -->
        <button class="btn btn-gamestore-outline-select text-uppercase dropdown-toggle d-flex justify-content-center align-items-center w-50" type="button" id="locations" data-bs-toggle="dropdown" aria-expanded="false">
          <!-- Emplacement du texte de la location sélectionnée -->
        </button>
        <ul class="dropdown-menu" aria-labelledby="locations" id="menu-location">
          <!-- Emplacement du menu des locations -->
        </ul>
      </div>
      <!-- Stock -->
      <div class="pt-2 pb-4 text-center">
          <span id="stock"><!-- Emplacement du texte pour le stock --></span>
      </div>
        <!-- Bouton d'ajout au panier -->  
        <div class="d-flex justify-content-center">
          <button class="btn btn-gamestore text-uppercase px-5" id="buy-button"><i class="bi bi-cart2"></i> Ajouter au panier</button>
        </div>
  </section>
  <!-- Deuxième section de description -->
  <section class="flex-column-reverse flex-lg-row row row-cols-1 row-cols-lg-2 g-lg-5 pb-4 ">
    <div class="px-xl-4"><?= Security::secureInput($game['game_description']) ?></div>
    <div>
      <p class="text-uppercase title-show"><?= Security::secureInput($game['game_name']) ?></p>
      <p><span class="fw-bold">Genre : </span><?= Security::secureInput($game['genres']) ?></p>
      <img class="pb-3" src="<?= Security::secureInput(_ASSETS_IMAGES_FOLDER_ . 'pegi/' . $game['pegi_name'] . '.jpg') ?>" alt="<?= Security::secureInput($game['pegi_name']) ?>" width="30">
    </div>
  </section>
  <!-- Troisième section de carousel -->
  <section class="pb-4">
    <!-- Emplacement du carousel -->
    <div id="carousel-gamestore" class="mb-3">
      <div class="carousel-gamestore" tabindex="0">
        <div class="carousel-gamestore__container">
          <?php for ($i = 0; $i < count($carousel); $i++) : ?>
            <div class="carousel-gamestore__item">
              <div class="carousel-gamestore__image">
                <img src="<?= Security::secureInput(_GAMES_IMAGES_FOLDER_ . $carousel[$i]) ?>" alt="<?= Security::secureInput($game['game_name']) ?>">
              </div>
            </div>
          <?php endfor; ?>
        </div>
      </div>
    </div>
    <div class="d-flex justify-content-center" id="pagination-container">
      <div class="pagination-pacman">
        <!-- Emplacement de la pagination -->
      </div>
    </div>
  </section>

  <?php require_once _TEMPLATEPATH_ . '/footer.php'; ?>