<?php 

use App\Tools\Security;

require_once _TEMPLATEPATH_ . '/employe/header.php'; 

?>

  <section class="container my-5">
    <div class="d-flex justify-content-between gamestore-title mb-4">
      <h2 class="text-uppercase">Détails des statistiques des ventes de votre Gamestore <?= Security::secureInput(Security::getEmployeStore()) ?></h2>
    </div>
    <p class="my-5">Voici les statistiques en détails des ventes de votre Gamestore <?= Security::secureInput(Security::getEmployeStore()) ?>.</p>
    <div class="my-5 d-flex flex-column">
      <p>Vous pouvez consulter les statistiques globales des ventes de votre Gamestore en cliquant sur le bouton ci-dessous :</p>
      <a href="index.php?controller=employe&action=sales" class="btn btn-gamestore text-uppercase align-self-center">Statistiques globales</a>
    </div>
    <!-- Filtre par jeu -->
    <div class="form-floating mb-3">
      <input type="text" id="gameFilter" class="form-control">
      <label for="gameFilter">Rechercher un jeu</label>
    </div>
    <!-- Filtre par plateforme -->
    <div class="form-floating mb-3">
      <select id="platformFilter" class="form-select">
        <option value="">Toutes les plateformes</option>
        <?php foreach ($platforms as $platform) : ?>
          <option value="<?= Security::secureInput($platform['name']) ?>"><?= Security::secureInput($platform['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <label for="statusFilter" class="form-label">Filtrer par plateformes</label>
    </div>
    <!-- Filtre par Date -->
    <div class="form-floating mb-3">
      <input type="date" id="dateFilter" class="form-control">
      <label for="dateFilter" class="form-label">Filtrer par date</label>
    </div>
    <!-- START : Spinner de chargement -->
    <div class="d-flex flex-column align-items-center" id="loading">
      <div class="loader"></div>
      <h4 class="text-uppercase fs-2 loading-title">Chargement en cours ...</h4>
    </div>
    <!-- END : Spinner de chargement -->
    <!-- Tableau pour afficher les détails des ventes -->
    <div class="table-responsive">
      <table class="table table-striped table-hover visually-hidden" id="salesTable">
        <thead>
          <tr>
            <th scope="col">Date</th>
            <th scope="col">Nom</th>
            <th scope="col">Plateforme</th>
            <th scope="col">Total ventes journée</th>
            <th scope="col">Prix totaux</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($sales as $sale) : ?>
            <tr>
              <td data-order="<?= $sale['date'] ?>"><?= (new DateTime($sale['date']))->format('d/m/Y') ?></td>
              <td><?= Security::secureInput($sale['name']) ?></td>
              <td><?= Security::secureInput($sale['platform']) ?></td>
              <td><?= Security::secureInput($sale['totalQuantity']) ?></td>
              <td><?= Security::secureInput($sale['price']) ?> €</td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="3" class="text-end">Total</th>
            <th id="totalQuantity"></th> 
            <th id="totalPrice"></th> 
          </tr>
        </tfoot>
      </table>
    </div>

  </section>
        
<?php require_once _TEMPLATEPATH_ . '/employe/footer.php'; ?>