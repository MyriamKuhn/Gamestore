<?php require_once _TEMPLATEPATH_ . '/header.php'; ?>

<!-- START : Main -->
<main class="container my-4 main" id="hero">
  <div id="data-container" data-games-nantes="<?= htmlspecialchars($gamesNantes, ENT_QUOTES, 'UTF-8') ?>" data-games-lille="<?= htmlspecialchars($gamesLille, ENT_QUOTES, 'UTF-8') ?>" data-games-bordeaux="<?= htmlspecialchars($gamesBordeaux, ENT_QUOTES, 'UTF-8') ?>" data-games-paris="<?= htmlspecialchars($gamesParis, ENT_QUOTES, 'UTF-8') ?>" data-games-toulouse="<?= htmlspecialchars($gamesToulouse, ENT_QUOTES, 'UTF-8') ?>"></div>
  <section class="mt-2">
    <div class="d-flex justify-content-between gamestore-title">
      <h2 class="text-uppercase">Liste des jeux</h2>
    </div>
    <!-- Sélection de la ville -->
    <ul class="nav justify-content-between justify-content-lg-around mt-3" role="tablist">
      <li class="nav-item pb-2">
        <a class="menu-link text-uppercase active" href="#nantes" data-bs-toggle="tab">Nantes</a>
      </li>
      <li class="nav-item pb-2">
        <a class="menu-link text-uppercase" href="#lille" data-bs-toggle="tab">Lille</a>
      </li>
      <li class="nav-item pb-2">
        <a class="menu-link text-uppercase" href="#bordeaux" data-bs-toggle="tab">Bordeaux</a>
      </li>
      <li class="nav-item pb-2">
        <a class="menu-link text-uppercase" href="#paris" data-bs-toggle="tab">Paris</a>
      </li>
      <li class="nav-item pb-2">
        <a class="menu-link text-uppercase" href="#toulouse" data-bs-toggle="tab">Toulouse</a>
      </li>
    </ul>
  </section>
  <!-- END : Sélection de la ville -->

  <!-- START : Contenu des tabs -->
  <section class="tab-content">
    <div class="tab-pane fade active show" id="nantes">
      <h5 class="card-title text-uppercase pb-4">Nantes</h5>
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
        <div class="mt-3 row row-cols-1 row-cols-lg-5 justify-content-center justify-content-lg-evenly gap-4" id="cards-nantes">
          <!-- Emplacement des cards de la catégorie nantes -->
        </div>
      </div>
    </div>
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