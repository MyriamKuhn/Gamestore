<?php

use App\Tools\Security;

require_once _TEMPLATEPATH_ . '/admin/header.php';

?>

<section class="container my-5">
  <div class="d-flex justify-content-between gamestore-title mb-4">
    <h2 class="text-uppercase">Gestion des employés</h2>
  </div>
  <!-- Ajout d'un employé -->
  <div class="my-5 text-center">
    <a href="/index.php?controller=admin&action=employe" class="btn btn-gamestore text-uppercase">Ajouter un employé</a>
  </div>
  <!-- Filtre par nom -->
  <div class="form-floating mb-3">
    <input type="text" id="nameFilter" class="form-control">
    <label for="nameFilter">Rechercher un nom ou prénom</label>
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
  <!-- Filtre par statut -->
  <div class="form-floating mb-3">
    <select id="statusFilter" class="form-select">
      <option value="">Tous les statuts</option>
      <option value="Actif">Actif</option>
      <option value="Bloqué">Bloqué</option>
    </select>
    <label for="statusFilter" class="form-label">Filtrer par statut</label>
  </div>
  <!-- START : Spinner de chargement -->
  <div class="d-flex flex-column align-items-center" id="loading">
    <div class="loader"></div>
    <h4 class="text-uppercase fs-2 loading-title">Chargement en cours ...</h4>
  </div>
  <!-- END : Spinner de chargement -->
  <!-- Affichage de tous les employés disponibles dans un tableau -->
  <div class="table-responsive">
    <table class="table table-striped table-hover visually-hidden" id="employeTable">
      <thead>
        <tr>
          <th scope="col" class="all">ID</th>
          <th scope="col" class="all">Nom Prénom</th>
          <th scope="col" class="desktop">Adresse</th>
          <th scope="col" class="desktop">Email</th>
          <th scope="col" class="tablet desktop">Magasin</th>
          <th scope="col" class="tablet desktop">Statut</th>
          <th scope="col" class="all">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($employes as $employe) :?>
          <tr>
            <td><?= Security::secureInput($employe['user_id']) ?></td>
            <td><?= Security::secureInput($employe['last_name']) . ' ' . Security::secureInput($employe['first_name']) ?></td>
            <td><?= Security::secureInput($employe['address']) . ' ' . Security::secureInput($employe['postcode']) . ' ' . Security::secureInput($employe['city']) ?></td>
            <td><?= Security::secureInput($employe['user_mail']) ?></td>
            <td><?= Security::secureInput($employe['store_location']) ?></td>
            <td><?= Security::secureInput($employe['is_blocked'] == 0) ? 'Actif' : 'Bloqué' ?></td>
            <td>
              <form method="post">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="userId" value="<?= Security::secureInput($employe['user_id']) ?>">
                <button type="submit" name="editEmploye" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Modifier les données" class="btn btn-gamestore">
                  <i class="bi bi-pencil-fill"></i>
                </button>
                <?php if ($employe['is_blocked'] == 0) : ?>
                <button type="submit" name="blockEmploye" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Bloquer" class="btn btn-gamestore">
                  <i class="bi bi-lock-fill"></i>
                </button>
                <?php else : ?>
                <button type="submit" name="unblockEmploye" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Débloquer" class="btn btn-gamestore">
                  <i class="bi bi-unlock-fill"></i>
                </button>
                <?php endif; ?>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>


</section>

<?php require_once _TEMPLATEPATH_ . '/admin/footer.php'; ?>