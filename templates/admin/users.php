<?php

use App\Tools\Security;

require_once _TEMPLATEPATH_ . '/admin/header.php';

?>

<section class="container my-5">
  <div class="d-flex justify-content-between gamestore-title mb-4">
    <h2 class="text-uppercase">Gestion des clients</h2>
  </div>
  <!-- Alerte pour signifier que l'utilisateur doit être déconnecté -->
  <div class="alert alert-info py-5 my-5">
    <p><i class="bi bi-exclamation-circle"></i> Pour que les informations soient actualisées, le client doit se déconnecter.</p>
    <p>Lors de sa première reconnexion il devra à nouveau s'authentifier afin de garantir la validité de l'adresse mail.</p>
  </div>
  <!-- Affichage des erreurs -->
  <div class="alert alert-danger py-5 my-5 <?= empty($errors) ? 'visually-hidden' : '' ?>" id="error-message">
    <?php if (!empty($errors)) {
      foreach ($errors as $error) {
        echo $error . '<br>';
      }
    } ?>
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
  <!-- Affichage de tous les clients disponibles dans un tableau -->
  <div class="table-responsive">
    <table class="table table-striped table-hover visually-hidden" id="usersTable">
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
        <?php foreach ($users as $user) :?>
          <tr>
            <td><?= Security::secureInput($user['user_id']) ?></td>
            <td><?= Security::secureInput($user['last_name']) . ' ' . Security::secureInput($user['first_name']) ?></td>
            <td><?= Security::secureInput($user['address']) . ' ' . Security::secureInput($user['postcode']) . ' ' . Security::secureInput($user['city']) ?></td>
            <form method="post" id="user-form" class="needs-validation">
              <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
              <input type="hidden" name="userId" value="<?= Security::secureInput($user['user_id']) ?>">
              <td>
                <input type="email" class="form-control <?=(isset($errors['email']) ? 'is-invalid': '') ?>" name="email" value="<?= Security::secureInput($user['user_mail']) ?>" required>
              </td>
              <td><?= Security::secureInput($user['store_location']) ?></td>
              <td><?= Security::secureInput($user['is_blocked'] == 0) ? 'Actif' : 'Bloqué' ?></td>
              <td>
                <button type="submit" name="editUser" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Modifier les données" class="btn btn-gamestore">
                  <i class="bi bi-pencil-fill"></i>
                </button>
                <?php if ($user['is_blocked'] == 0) : ?>
                <button type="submit" name="blockUser" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Bloquer" class="btn btn-gamestore">
                  <i class="bi bi-lock-fill"></i>
                </button>
                <?php else : ?>
                <button type="submit" name="unblockUser" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Débloquer" class="btn btn-gamestore">
                  <i class="bi bi-unlock-fill"></i>
                </button>
                <?php endif; ?>
              </td>
            </form>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>


</section>

<?php require_once _TEMPLATEPATH_ . '/admin/footer.php'; ?>