<?php

use App\Tools\Security;

require_once _TEMPLATEPATH_ . '/admin/header.php';

?>

<section class="container my-5">
  <div class="d-flex justify-content-between gamestore-title mb-4">
    <h2 class="text-uppercase">Gestion des jeux</h2>
  </div>
  <!-- Ajout d'un jeu -->
  <div class="my-5 text-center">
    <a href="/index.php?controller=admin&action=product" class="btn btn-gamestore text-uppercase">Ajouter un jeu</a>
  </div>
  <!-- Filtre par nom -->
  <div class="form-floating mb-3">
    <input type="text" id="nameFilter" class="form-control">
    <label for="nameFilter">Rechercher un nom ou prénom</label>
  </div>
  <!-- Filtre par plateformes -->
  <div class="form-floating mb-3">
    <select id="platformFilter" class="form-select">
      <option value="">Toutes les plateformes</option>
      <?php foreach ($platforms as $platform) : ?>
        <option value="<?= Security::secureInput($platform['name']) ?>"><?= Security::secureInput($platform['name']) ?></option>
      <?php endforeach; ?>
    </select>
    <label for="platformFilter" class="form-label">Filtrer par plateforme</label>
  </div>
  <!-- Filtre par Magasin -->
  <div class="form-floating mb-3">
    <select id="storeFilter" class="form-select">
      <option value="">Tous les magasins</option>
      <option value="Nantes">Nantes</option>
      <option value="Lille">Lille</option>
      <option value="Bordeaux">Bordeaux</option>
      <option value="Paris">Paris</option>
      <option value="Toulouse">Toulouse</option>
    </select>
    <label for="storeFilter" class="form-label">Filtrer par magasin</label>
  </div>
  <!-- START : Spinner de chargement -->
  <div class="d-flex flex-column align-items-center" id="loading">
    <div class="loader"></div>
    <h4 class="text-uppercase fs-2 loading-title">Chargement en cours ...</h4>
  </div>
  <!-- END : Spinner de chargement -->
  <!-- Affichage de tous les jeux disponibles dans un tableau -->
  <div class="table-responsive">
    <table class="table table-striped table-hover visually-hidden" id="gameTable">
      <thead>
        <tr>
          <th scope="col" class="all">ID</th>
          <th scope="col" class="all">Nom</th>
          <th scope="col" class="tablet desktop">Plateforme</th>
          <th scope="col" class="tablet desktop">Magasin</th>
          <th scope="col" class="tablet desktop">Stock</th>
          <th scope="col" class="desktop">Prix</th>
          <th scope="col" class="desktop">Promo</th>
          <th scope="col" class="all">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($games as $game) :
          if (Security::secureInput($game['discount_rate']) > 0) {
            $price = Security::secureInput($game['platform_price']) - (Security::secureInput($game['platform_price']) * Security::secureInput($game['discount_rate']) / 100);
            $rate = (Security::secureInput($game['discount_rate']) * 100) . ' %';
          } else {
            $price = Security::secureInput($game['platform_price']);
            $rate = 'Aucune';
          }
          ?>
          <tr>
            <td><?= Security::secureInput($game['game_id']) ?></td>
            <td><a href="/index.php?controller=games&action=show&id=<?= Security::secureInput($game['game_id']) ?>"><?= html_entity_decode(Security::secureInput($game['game_name'])) ?></a></td>
            <td><?= Security::secureInput($game['platform_name']) ?></td>
            <td><?= Security::secureInput($game['store_location']) ?></td>
            <td><?= Security::secureInput($game['quantity']) ?></td>
            <td><?= Security::secureInput(number_format($price, 2)) ?></td>
            <td><?= Security::secureInput($rate) ?></td>
            <td>
              <form method="post">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="game_id" value="<?= Security::secureInput($game['game_id']) ?>">
                <input type="hidden" name="platform_id" value="<?= Security::secureInput($game['platform_id']) ?>">
                <input type="hidden" name="store_id" value="<?= Security::secureInput($game['store_id']) ?>">
                <button type="submit" name="editGame" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Modifier le jeu" class="btn btn-gamestore">
                  <i class="bi bi-pencil-fill"></i>
                </button>
                <button type="submit" name="addStock" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ajouter du stock" class="btn btn-gamestore">
                  <i class="bi bi-plus-lg"></i>
                </button>
                <button type="submit" name="removeStock" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Retirer du stock" class="btn btn-gamestore <?= (Security::secureInput($game['quantity']) > 0) ? '' : 'disabled' ?>">
                  <i class="bi bi-dash-lg"></i>
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>


</section>

<?php require_once _TEMPLATEPATH_ . '/admin/footer.php'; ?>