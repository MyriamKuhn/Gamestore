<?php 

use App\Tools\Security;

require_once _TEMPLATEPATH_ . '/admin/header.php'; 

?>

  <section class="container my-5">
    <div class="d-flex justify-content-between gamestore-title mb-4">
      <h2 class="text-uppercase">Statistiques des ventes</h2>
    </div>
    <div class="my-5 d-flex flex-column">
      <p>Voici les statistiques des ventes. Vous pouvez consulter les détails des ventes en cliquant sur le bouton ci-dessous :</p>
      <a href="/index.php?controller=admin&action=details" class="btn btn-gamestore text-uppercase align-self-center">Plus de détails</a>
    </div>
    <!-- START : Sélection de la ville -->
    <section class="mt-2">
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
        <li class="nav-item pb-2">
          <a class="menu-link text-uppercase stores" id="all-tab" href="#all" data-bs-toggle="tab">Tous</a>
        </li>
      </ul>
      <!-- END : Sélection de la ville -->
    </section>
    <!-- START : Contenu des tabs -->
    <section class="tab-content my-4 pb-3">
      <!-- START : Nantes -->
      <div class="tab-pane fade active show" id="nantes">
        <div class="d-flex justify-content-between gamestore-title mb-5">
          <h2 class="text-uppercase">Gamestore Nantes</h2>
        </div>
        <!-- START : Spinner de chargement -->
        <div class="visually-hidden" id="no-datas-nantes">
          <h4 class='text-center text-uppercase fs-4'>Aucune vente n'a été trouvée</h4>
        </div>
        <div class="d-flex flex-column align-items-center" id="loading-nantes">
          <div class="loader"></div>
          <h4 class="text-uppercase fs-2 loading-title">Chargement en cours ...</h4>
        </div>
        <!-- END : Spinner de chargement -->
        <h4 class='text-center text-uppercase graphic-title-nantes visually-hidden'>Ventes de jeux vidéo par titre et par date</h4>
        <div id="chartNantes" class="my-5 mt-3">
          <!-- Emplacement du graphique -->
        </div>
        <h4 class='text-center text-uppercase graphic-title-nantes visually-hidden'>Ventes de jeux vidéo par genre et par date</h4>
        <div id="chartGenreNantes" class="my-5 mt-3">
          <!-- Emplacement du graphique -->
        </div>
      </div>
      <!-- END : Nantes -->
      <!-- START : Lille -->
      <div class="tab-pane fade" id="lille">
        <div class="d-flex justify-content-between gamestore-title mb-5">
          <h2 class="text-uppercase">Gamestore Lille</h2>
        </div>
        <!-- START : Spinner de chargement -->
        <div class="visually-hidden" id="no-datas-lille">
          <h4 class='text-center text-uppercase fs-4'>Aucune vente n'a été trouvée</h4>
        </div>
        <div class="d-flex flex-column align-items-center" id="loading-lille">
          <div class="loader"></div>
          <h4 class="text-uppercase fs-2 loading-title">Chargement en cours ...</h4>
        </div>
        <!-- END : Spinner de chargement -->
        <h4 class='text-center text-uppercase graphic-title-lille visually-hidden'>Ventes de jeux vidéo par titre et par date</h4>
        <div id="chartLille" class="my-5 mt-3">
          <!-- Emplacement du graphique -->
        </div>
        <h4 class='text-center text-uppercase graphic-title-lille visually-hidden'>Ventes de jeux vidéo par genre et par date</h4>
        <div id="chartGenreLille" class="my-5 mt-3">
          <!-- Emplacement du graphique -->
        </div>
      </div>
      <!-- END : Lille -->
      <!-- START : Bordeaux -->
      <div class="tab-pane fade" id="bordeaux">
        <div class="d-flex justify-content-between gamestore-title mb-5">
          <h2 class="text-uppercase">Gamestore Bordeaux</h2>
        </div>
        <!-- START : Spinner de chargement -->
        <div class="visually-hidden" id="no-datas-bordeaux">
          <h4 class='text-center text-uppercase fs-4'>Aucune vente n'a été trouvée</h4>
        </div>
        <div class="d-flex flex-column align-items-center" id="loading-bordeaux">
          <div class="loader"></div>
          <h4 class="text-uppercase fs-2 loading-title">Chargement en cours ...</h4>
        </div>
        <!-- END : Spinner de chargement -->
        <h4 class='text-center text-uppercase graphic-title-bordeaux visually-hidden'>Ventes de jeux vidéo par titre et par date</h4>
        <div id="chartBordeaux" class="my-5 mt-3">
          <!-- Emplacement du graphique -->
        </div>
        <h4 class='text-center text-uppercase graphic-title-bordeaux visually-hidden'>Ventes de jeux vidéo par genre et par date</h4>
        <div id="chartGenreBordeaux" class="my-5 mt-3">
          <!-- Emplacement du graphique -->
        </div>
      </div>
      <!-- END : Bordeaux -->
      <!-- START : Paris -->
      <div class="tab-pane fade" id="paris">
        <div class="d-flex justify-content-between gamestore-title mb-5">
          <h2 class="text-uppercase">Gamestore Paris</h2>
        </div>
        <!-- START : Spinner de chargement -->
        <div class="visually-hidden" id="no-datas-paris">
          <h4 class='text-center text-uppercase fs-4'>Aucune vente n'a été trouvée</h4>
        </div>
        <div class="d-flex flex-column align-items-center" id="loading-paris">
          <div class="loader"></div>
          <h4 class="text-uppercase fs-2 loading-title">Chargement en cours ...</h4>
        </div>
        <!-- END : Spinner de chargement -->
        <h4 class='text-center text-uppercase graphic-title-paris visually-hidden'>Ventes de jeux vidéo par titre et par date</h4>
        <div id="chartParis" class="my-5 mt-3">
          <!-- Emplacement du graphique -->
        </div>
        <h4 class='text-center text-uppercase graphic-title-paris visually-hidden'>Ventes de jeux vidéo par genre et par date</h4>
        <div id="chartGenreParis" class="my-5 mt-3">
          <!-- Emplacement du graphique -->
        </div>
      </div>
      <!-- END : Paris -->
      <!-- START : Toulouse -->
      <div class="tab-pane fade" id="toulouse">
        <div class="d-flex justify-content-between gamestore-title mb-5">
          <h2 class="text-uppercase">Gamestore Toulouse</h2>
        </div>
        <!-- START : Spinner de chargement -->
        <div class="visually-hidden" id="no-datas-toulouse">
          <h4 class='text-center text-uppercase fs-4'>Aucune vente n'a été trouvée</h4>
        </div>
        <div class="d-flex flex-column align-items-center" id="loading-toulouse">
          <div class="loader"></div>
          <h4 class="text-uppercase fs-2 loading-title">Chargement en cours ...</h4>
        </div>
        <!-- END : Spinner de chargement -->
        <h4 class='text-center text-uppercase graphic-title-toulouse visually-hidden'>Ventes de jeux vidéo par titre et par date</h4>
        <div id="chartToulouse" class="my-5 mt-3">
          <!-- Emplacement du graphique -->
        </div>
        <h4 class='text-center text-uppercase graphic-title-toulouse visually-hidden'>Ventes de jeux vidéo par genre et par date</h4>
        <div id="chartGenreToulouse" class="my-5 mt-3">
          <!-- Emplacement du graphique -->
        </div>
      </div>
      <!-- END : Toulouse -->
      <!-- START : Tous -->
      <div class="tab-pane fade" id="all">
        <div class="d-flex justify-content-between gamestore-title mb-5">
          <h2 class="text-uppercase">Gamestore France</h2>
        </div>
        <!-- START : Spinner de chargement -->
        <div class="visually-hidden" id="no-datas-all">
          <h4 class='text-center text-uppercase fs-4'>Aucune vente n'a été trouvée</h4>
        </div>
        <div class="d-flex flex-column align-items-center" id="loading-all">
          <div class="loader"></div>
          <h4 class="text-uppercase fs-2 loading-title">Chargement en cours ...</h4>
        </div>
        <!-- END : Spinner de chargement -->
        <h4 class='text-center text-uppercase graphic-title-all visually-hidden'>Ventes de jeux vidéo par titre et par date</h4>
        <div id="chartAll" class="my-5 mt-3">
          <!-- Emplacement du graphique -->
        </div>
        <h4 class='text-center text-uppercase graphic-title-all visually-hidden'>Ventes de jeux vidéo par genre et par date</h4>
        <div id="chartGenreAll" class="my-5 mt-3">
          <!-- Emplacement du graphique -->
        </div>
      </div>
      <!-- END : Tous -->
    </section>
        
<?php require_once _TEMPLATEPATH_ . '/admin/footer.php'; ?>