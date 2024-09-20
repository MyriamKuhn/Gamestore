<?php

use App\Tools\Security;
use App\Tools\FileTools;

require_once _TEMPLATEPATH_ . '/header.php';

$presentation = FileTools::getImagesAsCategory('presentation', $game['images']);
$carousel = FileTools::getImagesAsCategory('carousel', $game['images']);

?>

<!-- START : Main -->
<main class="container my-4 main" id="hero">
  <section class="mt-2">
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
        <span class="price-show" id="price-nantes">13,99€</span>
        <div id="price-container">
          <span id="discount">60%</span><span class="text-decoration-line-through full-price py-auto ps-1" id="oldprice">25,99€</span>
        </div>
      </div>
      <!-- Plateformes et locations -->
      <div class="dropdown">
        <button class="btn btn-gamestore dropdown-toggle w-50" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-x-lg cross"></i> <span class="ms-2">Maison</span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <li>
            <a class="dropdown-item" href="#">
              <i class="bi bi-x-lg cross"></i> Maison
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <i class="bi bi-gear"></i> Paramètres
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <i class="bi bi-person"></i> Profil
            </a>
          </li>
        </ul>
      </div>
      <div id="select-platforms" class="mb-4">
        <!-- Emplacement des plateformes et locations -->
      </div>
      <!-- Stock -->
      <div class="row pb-4">
        <div class="col-6 text-end">
          <span class="title-store text-uppercase">Disponibilité : </span>
        </div>
        <div class="col-6 text-start">
          <span>reste 5 en stock</span>
        </div>
      </div>
        <!-- Bouton d'ajout au panier -->  
        <div class="d-flex justify-content-center">
          <button class="btn btn-gamestore text-uppercase px-5"><i class="bi bi-cart2"></i> Ajouter au panier</button>
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