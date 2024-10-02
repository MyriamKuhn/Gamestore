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
      <a href="index.php?controller=employe&action=details" class="btn btn-gamestore text-uppercase align-self-center">Plus de détails</a>
    </div>
    <!-- Graphique pour afficher les ventes -->
    <div id="chart" class="my-5"></div>
  </section>
        
<?php require_once _TEMPLATEPATH_ . '/employe/footer.php'; ?>