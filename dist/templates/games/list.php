<?php

use App\Tools\Security;

require_once _TEMPLATEPATH_ . '/header.php'; ?>

<!-- START : Main -->
<main class="container my-4 main" id="hero">
  <section class="mt-2">
    <div class="d-flex justify-content-between gamestore-title">
      <h2 class="text-uppercase">Tous nos jeux vidéo</h2>
    </div>
    <!-- Sélection de la ville -->
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
  </section>
  <!-- END : Sélection de la ville -->
  
  <!-- START : Filtres -->
  <div class="my-4 pb-3">
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
            <label class="btn btn-gamestore-outline-checked text-uppercase mb-1 me-1" for="btn-check-genre-<?= $genre['id'] ?>"><?= $genre['name'] ?></label>
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
            <label class="btn btn-gamestore-outline-checked text-uppercase mb-1 me-1" for="btn-check-platform-<?= $platform['id'] ?>"><?= $platform['name'] ?></label>
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
          <label class="form-label text-center">Les prix sont compris entre <?= Security::secureInput(implode(min($prices))) ?>€ et <?= Security::secureInput(implode(max($prices)))?>€ (hors promotions)
            <div class="mt-2 d-flex align-items-center">
              <input class="form-control pe-2" id="min-price" type="number" value="<?= Security::secureInput(implode(min($prices))) ?>" data-min="<?= Security::secureInput(implode(min($prices))) ?>">
              <div class="px-4"> - </div>
              <input class="form-control" type="number" id="max-price" value="<?= Security::secureInput(implode(max($prices))) ?>" data-max="<?= Security::secureInput(implode(max($prices))) ?>">
            </div>
          </label>
        </div>
      </div>
    </div>
  </div>
</div>
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
    <div class="tab-pane fade" id="lille">
      <h5 class="card-title text-uppercase pb-4">Lille</h5>
      <div class="card-text justify-content-around row">
        <!-- START : Filtres -->
        <div>
          <label for="filter" class="form-label">Filtrer par :</label>
          <select class="form-select" id="filter">
            <option value="all">Tous les jeux</option>
            <option value="promo">Promotions</option>
            <option value="new">Nouveautés</option>
          </select>
        </div>
        <!-- END : Filtres -->
        <div class="mt-3 row row-cols-1 row-cols-lg-5 justify-content-center justify-content-lg-evenly gap-4" id="cards-lille">
          <!-- Emplacement des cards de la catégorie nantes -->
        </div>
      </div>
    </div>
    <div class="tab-pane fade" id="bordeaux">
      <h5 class="card-title text-uppercase pb-4">Bordeaux</h5>
      <div class="card-text justify-content-around row">
        <!-- START : Filtres -->
        <div>
          <label for="filter" class="form-label">Filtrer par :</label>
          <select class="form-select" id="filter">
            <option value="all">Tous les jeux</option>
            <option value="promo">Promotions</option>
            <option value="new">Nouveautés</option>
          </select>
        </div>
        <!-- END : Filtres -->
        <div class="mt-3 row row-cols-1 row-cols-lg-5 justify-content-center justify-content-lg-evenly gap-4" id="cards-bordeaux">
          <!-- Emplacement des cards de la catégorie nantes -->
        </div>
      </div>
    </div>
    <div class="tab-pane fade" id="paris">
      <h5 class="card-title text-uppercase pb-4">Paris</h5>
      <div class="card-text justify-content-around row">
        <!-- START : Filtres -->
        <div>
          <label for="filter" class="form-label">Filtrer par :</label>
          <select class="form-select" id="filter">
            <option value="all">Tous les jeux</option>
            <option value="promo">Promotions</option>
            <option value="new">Nouveautés</option>
          </select>
        </div>
        <!-- END : Filtres -->
        <div class="mt-3 row row-cols-1 row-cols-lg-5 justify-content-center justify-content-lg-evenly gap-4" id="cards-paris">
          <!-- Emplacement des cards de la catégorie nantes -->
        </div>
      </div>
    </div>
    <div class="tab-pane fade" id="toulouse">
      <h5 class="card-title text-uppercase pb-4">Toulouse</h5>
      <div class="card-text justify-content-around row">
        <!-- START : Filtres -->
        <div>
          <label for="filter" class="form-label">Filtrer par :</label>
          <select class="form-select" id="filter">
            <option value="all">Tous les jeux</option>
            <option value="promo">Promotions</option>
            <option value="new">Nouveautés</option>
          </select>
        </div>
        <!-- END : Filtres -->
        <div class="mt-3 row row-cols-1 row-cols-lg-5 justify-content-center justify-content-lg-evenly gap-4" id="cards-toulouse">
          <!-- Emplacement des cards de la catégorie nantes -->
        </div>
      </div>
    </div>
  </section>

  <script src="./assets/js/listPage.js"></script>

  <?php require_once _TEMPLATEPATH_ . '/footer.php'; ?>