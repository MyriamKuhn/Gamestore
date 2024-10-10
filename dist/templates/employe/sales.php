<?php 

use App\Tools\Security;

require_once _TEMPLATEPATH_ . '/employe/header.php'; 

?>

  <section class="container my-5">
    <div class="d-flex justify-content-between gamestore-title mb-4">
      <h2 class="text-uppercase">Statistiques des ventes de votre Gamestore <?= Security::secureInput(Security::getEmployeStore()) ?></h2>
    </div>
    <div class="my-5 d-flex flex-column">
      <p>Voici les statistiques des ventes de votre Gamestore <?= Security::secureInput(Security::getEmployeStore()) ?>. Vous pouvez consulter les détails des ventes en cliquant sur le bouton ci-dessous :</p>
      <a href="/index.php?controller=employe&action=details" class="btn btn-gamestore text-uppercase align-self-center">Plus de détails</a>
    </div>
    <!-- START : Spinner de chargement -->
    <div class="visually-hidden" id="no-datas">
      <h4 class='text-center text-uppercase fs-4'>Aucune vente n'a été trouvée</h4>
    </div>
    <div class="d-flex flex-column align-items-center" id="loading">
      <div class="loader"></div>
      <h4 class="text-uppercase fs-2 loading-title">Chargement en cours ...</h4>
    </div>
    <!-- END : Spinner de chargement -->
    <!-- Graphique pour afficher les ventes -->
    <h4 class='text-center text-uppercase graphic-title visually-hidden'>Ventes de jeux vidéo par titre et par date</h4>
    <div id="chart" class="my-5">
      <!-- Emplacement du graphique -->
    </div>
  </section>
        
<?php require_once _TEMPLATEPATH_ . '/employe/footer.php'; ?>