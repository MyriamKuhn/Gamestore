<?php

use App\Tools\Security;

require_once _TEMPLATEPATH_ . '/header.php'; ?>

<!-- START : Main -->
<main class="container my-4 main" id="hero">
  <section class="mt-2">
    <div class="d-flex justify-content-between gamestore-title">
      <h2 class="text-uppercase">Tous nos jeux vidéo</h2>
    </div>

    <!-- START : Sélection de la ville -->
    <ul class="nav justify-content-between justify-content-lg-around mt-3" role="tablist">
      <li class="nav-item pb-2">
        <a class="menu-link text-uppercase stores active" id="nantes-tab" href="#nantes" data-bs-toggle="tab">Nantes</a>
      </li>
      <li class="nav-item pb-2">
        <a class="menu-link text-uppercase stores" id="lille-tab" href="#lille" data-bs-toggle="tab">Lille</a>
      </li>
      <li class="nav-item pb-2">
        <a class="menu-link text-uppercase stores" id="bordeaux-tab" href="#bordeaux" data-bs-toggle="tab">Bordeaux</a>
      </li>
      <li class="nav-item pb-2">
        <a class="menu-link text-uppercase stores" id="paris-tab" href="#paris" data-bs-toggle="tab">Paris</a>
      </li>
      <li class="nav-item pb-2">
        <a class="menu-link text-uppercase stores" id="toulouse-tab" href="#toulouse" data-bs-toggle="tab">Toulouse</a>
      </li>
    </ul>
    <!-- END : Sélection de la ville -->
  </section>

  <!-- START : Filtres -->
  <section class="my-4 pb-3">
    <div class="accordion" id="filters">
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#filter-genres" aria-expanded="true" aria-controls="filter-genres">
            Filtrer par genre
          </button>
        </h2>
        <div id="filter-genres" class="accordion-collapse collapse show">
          <div class="accordion-body row row-cols-auto justify-content-center">
            <?php foreach ($genres as $genre) : ?>
              <input type="checkbox" class="btn-check genre-filter" id="btn-check-genre-<?= Security::secureInput($genre['id']) ?>" autocomplete="off" value="<?= Security::secureInput($genre['name']) ?>">
              <label class="btn btn-gamestore-outline-checked text-uppercase mb-1 me-1" for="btn-check-genre-<?= Security::secureInput($genre['id']) ?>"><?= Security::secureInput($genre['name']) ?></label>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#filter-platform" aria-expanded="false" aria-controls="filter-platform">
            Filtrer par plateforme
          </button>
        </h2>
        <div id="filter-platform" class="accordion-collapse collapse show">
          <div class="accordion-body row row-cols-auto justify-content-center">
            <?php foreach ($platforms as $platform) : ?>
              <input type="checkbox" class="btn-check platform-filter" id="btn-check-platform-<?= Security::secureInput($platform['id']) ?>" autocomplete="off" value="<?= Security::secureInput($platform['name']) ?>">
              <label class="btn btn-gamestore-outline-checked text-uppercase mb-1 me-1" for="btn-check-platform-<?= Security::secureInput($platform['id']) ?>"><?= Security::secureInput($platform['name']) ?></label>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#filter-name" aria-expanded="false" aria-controls="filter-name">
            Rechercher par nom
          </button>
        </h2>
        <div id="filter-name" class="accordion-collapse collapse show">
          <div class="accordion-body">
            <input class="form-control" type="search" placeholder="Quel jeu recherchez-vous ?" id="search-game">
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#filter-price" aria-expanded="false" aria-controls="filter-price">
            Filtrer par prix
          </button>
        </h2>
        <div id="filter-price" class="accordion-collapse collapse show">
          <div class="accordion-body d-flex justify-content-center">
            <div class="mt-2 d-flex align-items-center justify-content-center">
              <div class="text-center">Les prix sont compris entre <?= Security::secureInput(floor(implode(min($prices)))) ?>€ et <?= Security::secureInput(ceil(implode(max($prices)))) ?>€ (hors promotions)
                <div class="d-flex justify-content-center">
                  <div>
                    <div id="label-min-price"><?= Security::secureInput(floor(implode(min($prices)))) ?> €</div>
                    <input type="range" class="form-range" id="min-price" step="0.01" value="<?= Security::secureInput(implode(min($prices))) ?>" min="<?= Security::secureInput(floor(implode(min($prices)))) ?>" max="<?= Security::secureInput(ceil(implode(max($prices)))) ?>">
                  </div>
                  <div class="px-4"> - </div>
                  <div>
                    <div id="label-max-price"><?= Security::secureInput(ceil(implode(max($prices)))) ?> €</div>
                    <input type="range" class="form-range" id="max-price" step="0.01" value="<?= Security::secureInput(implode(max($prices))) ?>" min="<?= Security::secureInput(floor(implode(min($prices)))) ?>" max="<?= Security::secureInput(ceil(implode(max($prices)))) ?>">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#filter-pages" aria-expanded="false" aria-controls="filter-pages">
            Pages et reset des filtres
          </button>
        </h2>
        <div id="filter-pages" class="accordion-collapse collapse show">
          <div class="accordion-body d-flex justify-content-center">
            <div class="mt-2 d-flex align-items-center justify-content-center">
              <div class="text-center">Nombre de jeux par page
                <select class="form-select" id="games-per-page">
                  <option value="4">4</option>
                  <option value="8" selected>8</option>
                  <option value="12">12</option>
                  <option value="16">16</option>
                  <option value="20">20</option>
                  <option value="24">24</option>
                  <option value="28">28</option>
                  <option value="32">32</option>
                  <option value="36">36</option>
                  <option value="40">40</option>
                </select>
              </div>
              <div class="text-center d-flex flex-column ms-5">
                <div>Reset des filtres</div>
                <button class="btn btn-gamestore" id="reset-filters">Reset</button>
              </div>
            </div>
          </div>
        </div>
      </div>
  </section>
  <!-- END : Filtres -->

  <!-- START : Contenu des tabs -->
  <section class="tab-content">

    <!-- START : Nantes -->
    <div class="tab-pane fade active show" id="nantes">
      <div class="card-text justify-content-around row">
        <div class="d-flex justify-content-between gamestore-title">
          <h2 class="text-uppercase">Gamestore Nantes</h2>
        </div>

        <!-- START : Spinner de chargement -->
        <div class="d-flex flex-column align-items-center" id="loading-nantes">
          <div class="loader"></div>
          <h4 class="text-uppercase fs-2 loading-title">Chargement en cours ...</h4>
        </div>
        <!-- END : Spinner de chargement -->

        <div class="mt-3 row row-cols-1 row-cols-lg-5 justify-content-center justify-content-lg-evenly gap-4" id="cards-nantes">
          <!-- Emplacement des cards de la catégorie nantes -->
        </div>
      </div>
    </div>
    <!-- END : Nantes -->

    <!-- START : Lille -->
    <div class="tab-pane fade" id="lille">
      <div class="card-text justify-content-around row">
        <div class="d-flex justify-content-between gamestore-title">
          <h2 class="text-uppercase">Gamestore Lille</h2>
        </div>

        <!-- START : Spinner de chargement -->
        <div class="d-flex flex-column align-items-center" id="loading-lille">
          <div class="loader"></div>
          <h4 class="text-uppercase fs-2 loading-title">Chargement en cours ...</h4>
        </div>
        <!-- END : Spinner de chargement -->

        <div class="mt-3 row row-cols-1 row-cols-lg-5 justify-content-center justify-content-lg-evenly gap-4" id="cards-lille">
          <!-- Emplacement des cards de la catégorie lille -->
        </div>
      </div>
    </div>
    <!-- END : Lille -->

    <!-- START : Bordeaux -->
    <div class="tab-pane fade" id="bordeaux">
      <div class="card-text justify-content-around row">
        <div class="d-flex justify-content-between gamestore-title">
          <h2 class="text-uppercase">Gamestore Bordeaux</h2>
        </div>

        <!-- START : Spinner de chargement -->
        <div class="d-flex flex-column align-items-center" id="loading-bordeaux">
          <div class="loader"></div>
          <h4 class="text-uppercase fs-2 loading-title">Chargement en cours ...</h4>
        </div>
        <!-- END : Spinner de chargement -->

        <div class="mt-3 row row-cols-1 row-cols-lg-5 justify-content-center justify-content-lg-evenly gap-4" id="cards-bordeaux">
          <!-- Emplacement des cards de la catégorie bordeaux -->
        </div>
      </div>
    </div>
    <!-- END : Bordeaux -->

    <!-- START : Paris -->
    <div class="tab-pane fade" id="paris">
      <div class="card-text justify-content-around row">
        <div class="d-flex justify-content-between gamestore-title">
          <h2 class="text-uppercase">Gamestore Paris</h2>
        </div>

        <!-- START : Spinner de chargement -->
        <div class="d-flex flex-column align-items-center" id="loading-paris">
          <div class="loader"></div>
          <h4 class="text-uppercase fs-2 loading-title">Chargement en cours ...</h4>
        </div>
        <!-- END : Spinner de chargement -->

        <div class="mt-3 row row-cols-1 row-cols-lg-5 justify-content-center justify-content-lg-evenly gap-4" id="cards-paris">
          <!-- Emplacement des cards de la catégorie nantes -->
        </div>
      </div>
    </div>
    <!-- END : Paris -->

    <!-- START : Toulouse -->
    <div class="tab-pane fade" id="toulouse">
      <div class="card-text justify-content-around row">
        <div class="d-flex justify-content-between gamestore-title">
          <h2 class="text-uppercase">Gamestore Toulouse</h2>
        </div>

        <!-- START : Spinner de chargement -->
        <div class="d-flex flex-column align-items-center" id="loading-toulouse">
          <div class="loader"></div>
          <h4 class="text-uppercase fs-2 loading-title">Chargement en cours ...</h4>
        </div>
        <!-- END : Spinner de chargement -->

        <div class="mt-3 row row-cols-1 row-cols-lg-5 justify-content-center justify-content-lg-evenly gap-4" id="cards-toulouse">
          <!-- Emplacement des cards de la catégorie nantes -->
        </div>
      </div>
    </div>
    <!-- END : Toulouse -->

    <!-- START : Pagination -->
    <section class="mt-5 mb-3">
      <div class="d-flex justify-content-center" id="pagination-container">
        <!-- Emplacement de la pagination -->
      </div>
    </section>
    <!-- END : Pagination -->

  </section>

  <?php require_once _TEMPLATEPATH_ . '/footer.php'; ?>