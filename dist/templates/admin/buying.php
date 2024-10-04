<?php

use App\Tools\Security;

require_once _TEMPLATEPATH_ . '/admin/header.php';

?>

<section class="container my-5">
  <div class="d-flex justify-content-between gamestore-title mb-4">
    <h2 class="text-uppercase">Gestion des ventes en magasin</h2>
  </div>
  <!-- Filtre par jeu -->
  <div class="form-floating mb-3">
    <input type="text" id="gameFilter" class="form-control">
    <label for="gameFilter">Rechercher un jeu</label>
  </div>
  <!-- Filtre par plateformes -->
  <div class="form-floating mb-3">
    <select id="platformFilter" class="form-select">
      <option value="">Toutes les plateformes</option>
      <?php foreach ($platforms as $platform) : ?>
        <option value="<?= Security::secureInput($platform['name']) ?>"><?= Security::secureInput($platform['name']) ?></option>
      <?php endforeach; ?>
    </select>
    <label for="statusFilter" class="form-label">Filtrer par Statut</label>
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
  <!-- Affichage de tous les jeux disponibles dans un tableau -->
  <div class="table-responsive">
    <table class="table table-striped table-hover" id="gamesTable">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Nom</th>
          <th scope="col">Plateforme</th>
          <th scope="col">Magasin</th>
          <th scope="col">Prix</th>
          <th scope="col">Stock</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($games as $game) :
          $price = $game['platform_price'];
          $priceToPay = 0;
          if ($game['is_reduced'] == 1) {
            $priceToPay = $price - ($price * $game['discount_rate'] / 100);
          } else {
            $priceToPay = $price;
          }
        ?>
          <tr>
            <th scope="row"><?= Security::secureInput($game['game_id']) ?></th>
            <td><?= Security::secureInput($game['game_name']) ?></td>
            <td><?= Security::secureInput($game['platform_name']) ?></td>
            <td><?= Security::secureInput($game['store_location']) ?></td>
            <td><?= number_format($priceToPay, 2) ?> €</td>
            <td><?= Security::secureInput($game['quantity']) ?></td>
            <td>
              <form method="post">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="gameId" value="<?= Security::secureInput($game['game_id']) ?>">
                <input type="hidden" name="platformId" value="<?= Security::secureInput($game['platform_id']) ?>">
                <input type="hidden" name="price" value="<?= Security::secureInput($game['platform_price']) ?>">
                <input type="hidden" name="discount" value="<?= Security::secureInput($game['discount_rate']) ?>">
                <input type="hidden" name="store_id" value="<?= Security::secureInput($game['store_id']) ?>">
                <div class="input-group flex-nowrap" style="min-width: 400px;">
                  <input type="number" name="quantity" value="1" min="1" max="<?= Security::secureInput($game['quantity']) ?>" class="form-control" style="max-width: 80px;">
                  <select name="user_id" class="form-select user-select">
                    <option value="0">Pas client inscrit</option>
                    <?php foreach ($users as $user) :
                      if (Security::secureInput($user['store_id']) == Security::secureInput($game['store_id']) && Security::secureInput($user['user_role']) !== 'admin') : ?>
                        <option value="<?= Security::secureInput($user['user_id']) ?>" <?= Security::secureInput($user['user_role']) === 'employe' ? 'data-color="red"' : '' ?>><?= Security::secureInput($user['user_name']) . ' ' . Security::secureInput($user['user_address']) . ' (' . Security::secureInput($user['store_location']) . ')' ?></option>
                    <?php endif;
                    endforeach; ?>
                  </select>
                  <button type="submit" name="orderStore" class="btn btn-gamestore <?= Security::secureInput($game['quantity']) == 0 ? 'disabled' : '' ?>"><i class="bi bi-bag-plus-fill"></i></button>
                </div>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>


</section>

<?php require_once _TEMPLATEPATH_ . '/admin/footer.php'; ?>