<?php 

use App\Tools\Security;

require_once _TEMPLATEPATH_ . '/employe/header.php'; 

?>

  <section class="container my-5">
    <div class="d-flex justify-content-between gamestore-title mb-4">
      <h2 class="text-uppercase">Gestion des commandes de votre Gamestore <?= Security::secureInput(Security::getEmployeStore()) ?></h2>
    </div>
    <!-- Filtre par Numéro de commande -->
    <div class="form-floating mb-3">
      <input type="text" id="orderIdFilter" class="form-control">
      <label for="orderIdFilter">Rechercher une commande</label>
    </div>
    <!-- Filtre par Client -->
    <div class="form-floating mb-3">
      <input type="text" id="clientFilter" class="form-control">
      <label for="clientFilter">Rechercher un client</label>
    </div>
    <!-- Filtre par Statut -->
    <div class="form-floating mb-3">
      <select id="statusFilter" class="form-select">
        <option value="">Tous les statuts</option>
        <option value="Validée">Validée</option>
        <option value="Livrée">Livrée</option>
        <option value="Annulée">Annulée</option>
        <option value="Annulée">Magasin</option>
      </select>
      <label for="statusFilter" class="form-label">Filtrer par Statut</label>
    </div>
    <!-- Filtre par Date -->
    <div class="form-floating mb-3">
      <input type="date" id="dateFilter" class="form-control">
      <label for="dateFilter" class="form-label">Filtrer par Date</label>
    </div>
    <!-- Tableau des commandes -->
    <div class="table-responsive">
      <table class="table table-striped table-hover" id="ordersTable">
        <thead>
          <tr>
            <th scope="col" class="tablet desktop">N° Commande</th>
            <th scope="col" class="all">Date</th>
            <th scope="col" class="all">Client</th>
            <th scope="col" class="desktop">Email</th>
            <th scope="col" class="tablet desktop">Statut</th>
            <th scope="col" class="tablet desktop">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $order) : ?>
            <tr>
              <th scope="row"><a href="index.php?controller=employe&action=order&id=<?= Security::secureInput($order['order_id']) ?>" class="text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Voir les détails de la commande"><?= Security::secureInput($order['order_id']) ?></a></th>
              <td data-order="<?= $order['order_date'] ?>"><?= (new DateTime($order['order_date']))->format('d/m/Y') ?></td>
              <td><?= Security::secureInput($order['user_name']) ?></td>
              <td><?= Security::secureInput($order['user_address']) ?></td>
              <td><?= Security::secureInput($order['order_status']) ?></td>
              <td>
                <form method="post">
                  <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                  <input type="hidden" name="order_id" value="<?= Security::secureInput($order['order_id']) ?>">
                  <button type="submit" name="validateOrder" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Passer la commande en statut 'Livrée'" class="btn btn-gamestore <?= Security::secureInput($order['order_status']) == 'Validée' ? '' : 'disabled' ?>">
                    <i class="bi bi-check2-square"></i>
                  </button>
                  <button type="submit" name="cancelOrder" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Annuler la commande" class="btn btn-gamestore <?= Security::secureInput($order['order_status']) == 'Validée' ? '' : 'disabled' ?>">
                    <i class="bi bi-x-square"></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
  </section>
        
<?php require_once _TEMPLATEPATH_ . '/employe/footer.php'; ?>