<?php 

use App\Tools\Security;

require_once _TEMPLATEPATH_ . '/dashboard/header.php'; 

?>

<section class="container my-5">
  <!-- START : Affichage des commandes validées -->
  <div class="d-flex justify-content-between gamestore-title mb-4">
    <h2 class="text-uppercase">Vos commandes à récupérer</h2>
  </div>
  <div class="row">
    <?php if (empty($validatedOrders)): ?>
      <div class="col-12">
        <p class="text-center">Vous n'avez pas encore de commandes à récupérer.</p>
      </div>
    <?php endif; ?>
    <?php foreach ($validatedOrders as $order): ?>
      <div class="col-12 col-lg-6 col-xl-4">
        <div class="card mb-4">
          <div class="card-header">
            <h3 class="card-title">Commande n°<?= Security::secureInput($order['order_id']) ?></h3>
            <p class="card-subtitle">A retirer le <?= (new DateTime($order['order_date']))->format('d/m/Y') ?> au Gamestore de <?= Security::secureInput($order['store_location']) ?></p>
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <?php foreach ($order['games'] as $game): ?>
                <li class="list-group-item d-flex justify-content-between">
                  <span><?= html_entity_decode(Security::secureInput($game['name'])) ?> (<?= Security::secureInput($game['platform']) ?>)</span>
                  <span><?= Security::secureInput($game['quantity']) ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
            <div class="mt-3 text-center">
              <a href="/index.php?controller=dashboard&action=order&id=<?= Security::secureInput($order['order_id']) ?>" class="btn btn-gamestore text-uppercase">Voir la commande</a>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <!-- END : Affichage des commandes validées -->
  <!-- START : Affichage des commandes finalisées -->
  <div class="d-flex justify-content-between gamestore-title mb-4">
    <h2 class="text-uppercase">Vos commandes récupérées</h2>
  </div>
  <div class="row">
    <?php if (empty($finishedOrders)): ?>
      <div class="col-12">
        <p class="text-center">Vous n'avez pas encore de commandes récupérées.</p>
      </div>
    <?php endif; ?>
    <?php foreach ($finishedOrders as $order): ?>
      <div class="col-12 col-lg-6 col-xl-4">
        <div class="card mb-4">
          <div class="card-header">
            <h3 class="card-title">Commande n°<?= Security::secureInput($order['order_id']) ?></h3>
            <p class="card-subtitle">A retirer le <?= (new DateTime($order['order_date']))->format('d/m/Y') ?> au Gamestore de <?= Security::secureInput($order['store_location']) ?></p>
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <?php foreach ($order['games'] as $game): ?>
                <li class="list-group-item d-flex justify-content-between">
                  <span><?= html_entity_decode(Security::secureInput($game['name'])) ?> (<?= Security::secureInput($game['platform']) ?>)</span>
                  <span><?= Security::secureInput($game['quantity']) ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
            <div class="mt-3 text-center">
              <a href="/index.php?controller=dashboard&action=order&id=<?= Security::secureInput($order['order_id']) ?>" class="btn btn-gamestore text-uppercase">Voir la commande</a>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <!-- END : Affichage des commandes finalisées -->
  <!-- START : Affichage des commandes annulées -->
  <div class="d-flex justify-content-between gamestore-title mb-4">
    <h2 class="text-uppercase">Vos commandes annulées</h2>
  </div>
  <div class="row">
    <?php if (empty($deletedOrders)): ?>
      <div class="col-12">
        <p class="text-center">Vous n'avez pas encore de commandes annulées.</p>
      </div>
    <?php endif; ?>
    <?php foreach ($deletedOrders as $order): ?>
      <div class="col-12 col-lg-6 col-xl-4">
        <div class="card mb-4">
          <div class="card-header">
            <h3 class="card-title">Commande n°<?= Security::secureInput($order['order_id']) ?></h3>
            <p class="card-subtitle">A retirer le <?= (new DateTime($order['order_date']))->format('d/m/Y') ?> au Gamestore de <?= Security::secureInput($order['store_location']) ?></p>
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <?php foreach ($order['games'] as $game): ?>
                <li class="list-group-item d-flex justify-content-between">
                  <span><?= html_entity_decode(Security::secureInput($game['name'])) ?> (<?= Security::secureInput($game['platform']) ?>)</span>
                  <span><?= Security::secureInput($game['quantity']) ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
            <div class="mt-3 text-center">
              <a href="/index.php?controller=dashboard&action=order&id=<?= Security::secureInput($order['order_id']) ?>" class="btn btn-gamestore text-uppercase">Voir la commande</a>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <!-- END : Affichage des commandes annulées -->
  <!-- START : Affichage des commandes en magasin -->
  <div class="d-flex justify-content-between gamestore-title mb-4">
    <h2 class="text-uppercase">Vos commandes en magasin</h2>
  </div>
  <div class="row">
    <?php if (empty($storeOrders)): ?>
      <div class="col-12">
        <p class="text-center">Vous n'avez pas encore de commandes en magasin.</p>
      </div>
    <?php endif; ?>
    <?php foreach ($storeOrders as $order): ?>
      <div class="col-12 col-lg-6 col-xl-4">
        <div class="card mb-4">
          <div class="card-header">
            <h3 class="card-title">Commande n°<?= Security::secureInput($order['order_id']) ?></h3>
            <p class="card-subtitle">A retirer le <?= (new DateTime($order['order_date']))->format('d/m/Y') ?> au Gamestore de <?= Security::secureInput($order['store_location']) ?></p>
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <?php foreach ($order['games'] as $game): ?>
                <li class="list-group-item d-flex justify-content-between">
                  <span><?= html_entity_decode(Security::secureInput($game['name'])) ?> (<?= Security::secureInput($game['platform']) ?>)</span>
                  <span><?= Security::secureInput($game['quantity']) ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
            <div class="mt-3 text-center">
              <a href="/index.php?controller=dashboard&action=order&id=<?= Security::secureInput($order['order_id']) ?>" class="btn btn-gamestore text-uppercase">Voir la commande</a>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <!-- END : Affichage des commandes en magasin -->
</section>

<?php require_once _TEMPLATEPATH_ . '/dashboard/footer.php'; ?>