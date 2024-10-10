<?php 

use App\Tools\Security;

require_once _TEMPLATEPATH_ . '/employe/header.php'; 

?>

<section class="container my-5">
  <div class="d-flex justify-content-between gamestore-title mb-4">
    <h2 class="text-uppercase">Commande numéro <?= Security::secureInput($order['order_id']) ?> du <?= (new DateTime($order['order_date']))->format('d/m/Y') ?></h2>
  </div>
  <!-- START : Affichage de la commande en tableau -->
  <div class="table-responsive">
    <table class="table table-striped table-hover" id="orderTable">
      <thead>
        <tr>
          <th scope="col">Jeu</th>
          <th scope="col">Plateforme</th>
          <th scope="col">Quantité</th>
          <th scope="col">Prix (TTC)</th>
          <th scope="col">Total (TTC)</th>
        </tr>
      </thead>
      <tbody>
        <?php $totalPrice = 0;
        foreach ($order['games'] as $game): 
          $gameTotal = Security::secureInput($game['quantity']) * Security::secureInput($game['price']);
          $totalPrice += $gameTotal; ?>
          <tr>
            <td><a href="/index.php?controller=games&action=show&id=<?=Security::secureInput($game['game_id']) ?>" class="text-link"><?= html_entity_decode(Security::secureInput($game['name'])) ?></a></td>
            <td><?= Security::secureInput($game['platform']) ?></td>
            <td><?= Security::secureInput($game['quantity']) ?></td>
            <td><?= number_format($game['price'], 2) ?> €</td>
            <td><?= number_format($gameTotal, 2) ?> €</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="4" class="text-end">Total du panier (TTC) :</th>
          <th><?= number_format($totalPrice, 2) ?> €</th>
        </tr>
      </tfoot>
    </table>
  </div>
  <!-- END : Affichage de la commande en tableau -->
  <div class="mb-5">
    <div class="text-center">
      <p class="mb-0">Commande passée par :</p>
      <p class="mb-0"><?= Security::secureInput($order['order_user']) ?></p>
      <p><?= Security::secureInput($order['order_address']) ?></p>
    </div>
  </div>
  <div class="text-center mt-5">
    <a href="/index.php?controller=employe&action=orders" class="btn btn-gamestore text-uppercase">Voir toutes les commandes</a>
  </div>
</section>



<?php require_once _TEMPLATEPATH_ . '/employe/footer.php'; ?>