<?php

use App\Tools\Security;

require_once _TEMPLATEPATH_ . '/header.php'; ?>

<!-- START : Main -->
<main class="container my-4 main" id="hero">
  <section class="mt-2">
    <div class="d-flex justify-content-between gamestore-title">
      <h2 class="text-uppercase">Toutes nos offres promotionnelles</h2>
    </div>
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
          <button class="accordion-button text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#filter-platform" aria-expanded="false" aria-controls="filter-platform">
            Filtrer par magasin
          </button>
        </h2>
        <div id="filter-store" class="accordion-collapse collapse show">
          <div class="accordion-body row row-cols-auto justify-content-center">
            <?php foreach ($stores as $store) : ?>
              <input type="checkbox" class="btn-check store-filter" id="btn-check-store-<?= Security::secureInput($store['id']) ?>" autocomplete="off" value="<?= Security::secureInput($store['location']) ?>">
              <label class="btn btn-gamestore-outline-checked text-uppercase mb-1 me-1" for="btn-check-store-<?= Security::secureInput($store['id']) ?>"><?= Security::secureInput($store['location']) ?></label>
            <?php endforeach; ?>
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
                <button class="btn btn-gamestore text-uppercase" id="reset-filters">Reset</button>
              </div>
            </div>
          </div>
        </div>
      </div>
  </section>
  <!-- END : Filtres -->

  <!-- START : Spinner de chargement -->
  <div class="d-flex flex-column align-items-center" id="loading">
    <div class="loader"></div>
    <h4 class="text-uppercase fs-2 loading-title">Chargement en cours ...</h4>
  </div>
  <!-- END : Spinner de chargement -->

  <div class="mt-3 row row-cols-1 row-cols-lg-5 justify-content-center justify-content-lg-evenly gap-4" id="cards">
  <!-- Emplacement des cards -->
  </div>
  <!-- START : Pagination -->
  <section class="mt-5 mb-3">
    <div class="d-flex justify-content-center" id="pagination-container">
      <!-- Emplacement de la pagination -->
    </div>
  </section>
  <!-- END : Pagination -->

  <?php require_once _TEMPLATEPATH_ . '/footer.php'; ?>