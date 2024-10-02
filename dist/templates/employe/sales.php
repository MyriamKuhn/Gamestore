<?php 

use App\Tools\Security;

require_once _TEMPLATEPATH_ . '/employe/header.php'; 

?>

  <section class="container my-5">
    <div class="d-flex justify-content-between gamestore-title mb-4">
      <h2 class="text-uppercase">Statistiques des ventes de votre Gamestore <?= Security::secureInput(Security::getEmployeStore()) ?></h2>
    </div>
    <!-- Graphique pour afficher les ventes -->
    <div id="chart"></div>
  </section>
        
<?php require_once _TEMPLATEPATH_ . '/employe/footer.php'; ?>