<?php 

use App\Tools\Security;

require_once _TEMPLATEPATH_ . '/admin/header.php'; 

?>

  <section class="container my-5">
    <div class="d-flex justify-content-between gamestore-title mb-4">
      <h2 class="text-uppercase">Gestion des commandes des magasins Gamestore</h2>
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
        <option value="Magasin">Magasin</option>
      </select>
      <label for="statusFilter" class="form-label">Filtrer par statut</label>
    </div>
    <!-- Filtre par Magasin -->
    <div class="form-floating mb-3">
      <select id="storeFilter" class="form-select">
        <option value="">Tous les magasin</option>
        <option value="Nantes">Nantes</option>
        <option value="Lille">Lille</option>
        <option value="Bordeaux">Bordeaux</option>
        <option value="Paris">Paris</option>
        <option value="Toulouse">Toulouse</option>
      </select>
      <label for="storeFilter" class="form-label">Filtrer par magasin</label>
    </div>
    <!-- Filtre par Date -->
    <div class="form-floating mb-3">
      <input type="date" id="dateFilter" class="form-control">
      <label for="dateFilter" class="form-label">Filtrer par date</label>
    </div>
    <!-- START : Spinner de chargement -->
    <div class="d-flex flex-column align-items-center" id="loadingOrders">
      <div class="loader"></div>
      <h4 class="text-uppercase fs-2 loading-title">Chargement en cours ...</h4>
    </div>
    <!-- END : Spinner de chargement -->
    <!-- Tableau des commandes -->
    <div class="table-responsive">
      <table class="table table-striped table-hover visually-hidden" id="ordersTable">
        <thead>
          <tr>
            <th scope="col" class="tablet desktop">N° Commande</th>
            <th scope="col" class="all">Date</th>
            <th scope="col" class="all">Client</th>
            <th scope="col" class="desktop">Email</th>
            <th scope="col" class="desktop">Magasin</th>
            <th scope="col" class="desktop">Statut</th>
            <th scope="col" class="tablet desktop">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $order) : ?>
            <tr>
              <th scope="row"><a href="/index.php?controller=admin&action=order&id=<?= Security::secureInput($order['order_id']) ?>" class="text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Voir les détails de la commande"><?= Security::secureInput($order['order_id']) ?></a></th>
              <td data-order="<?= $order['order_date'] ?>"><?= (new DateTime($order['order_date']))->format('d/m/Y') ?></td>
              <td class="<?= in_array(Security::secureInput($order['user_role']), ['employe', 'admin']) ? 'text-danger' : '' ?>"><?= Security::secureInput($order['user_name']) ?></td>
              <td><?= Security::secureInput($order['user_address']) ?></td>
              <td><?= Security::secureInput($order['store_location']) ?></td>
              <td><?= Security::secureInput($order['order_status']) ?></td>
              <td>
                <form method="post">
                  <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                  <input type="hidden" name="order_id" value="<?= Security::secureInput($order['order_id']) ?>">
                  <input type="hidden" name="order_status" value="<?= Security::secureInput($order['order_status']) ?>">
                  <button type="submit" name="validateOrder" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Passer la commande en statut 'Livrée'" class="btn btn-gamestore <?= Security::secureInput($order['order_status']) == 'Livrée' ? 'disabled' : '' ?>">
                    <i class="bi bi-check2-square"></i>
                  </button>
                  <button type="submit" name="cancelOrder" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Annuler la commande" class="btn btn-gamestore <?= Security::secureInput($order['order_status']) == 'Annulée' ? 'disabled' : '' ?>">
                    <i class="bi bi-x-square"></i>
                  </button>
                  <button type="submit" name="shopOrder" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Passer la commande en statut 'Magasin'" class="btn btn-gamestore <?= Security::secureInput($order['order_status']) == 'Magasin' ? 'disabled' : '' ?>">
                    <i class="bi bi-shop"></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
  </section>
        
<?php require_once _TEMPLATEPATH_ . '/admin/footer.php'; ?>