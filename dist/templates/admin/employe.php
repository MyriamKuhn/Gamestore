<?php 

use App\Tools\Security;

require_once _TEMPLATEPATH_ . '/admin/header.php';

?>

<section class="container my-5">
  <div class="d-flex justify-content-between gamestore-title">
    <h2 class="text-uppercase"><?= Security::secureInput($isModify) ? 'Modifiez l\'employé' : 'Ajoutez un employé' ?></h2>
  </div>
  <!-- Alerte pour signifier que l'utilisateur doit être déconnecté ou dois se déco et reco pour que les informations soient actualisées -->
    <div class="alert alert-info py-5 my-5">
      <?php if ($isModify) : ?>
        <p><i class="bi bi-exclamation-circle"></i> Pour que les informations soient actualisées, l'employé doit se déconnecter et se reconnecter.</p>
        <p>De préférence, demandez-lui de se déconnecter avant les modifications.</p>
      <?php else : ?>
        <p><i class="bi bi-exclamation-circle"></i> Le mot de passe par défaut de votre nouvel employé sera toujours celui qui vous a été remis lors de l'initiation à l'espace admin.</p>
        <p>Veuillez lui communiquer ce mot de passe par un moyen sécurisé. Lors de sa première connexion il sera invité à le modifier.</p>
      <?php endif; ?>
    </div>
  <!-- Affichage des erreurs -->
  <div class="alert alert-danger py-5 my-5 <?= empty($errors) ? 'visually-hidden' : '' ?>" id="error-message">
    <?php if (!empty($errors)) {
      foreach ($errors as $error) {
        echo $error . '<br>';
      }
    } ?>
  </div>
  <!-- START : Formulaire -->
      <form method="post" id="employe-form" class="needs-validation mt-5">
        <!-- Inclusion du token CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
        <input type="hidden" name="user_id" value="<?= $isModify ? Security::secureInput($employe->getId()) : '0' ?>">
        <!-- Nom et prénom -->
        <div class="input-group mb-3">
          <div class="form-floating">
            <input type="text" class="form-control <?=(isset($errors['last_name']) ? 'is-invalid': '') ?>" id="last_name" name="last_name" minlength="3" maxlength="100" <?= $isModify ? 'value="' . Security::secureInput($employe->getLast_name()) . '"' : ''  ?> required>
            <label for="last_name">Nom</label>
            <div class="invalid-feedback">
              Entrez le nom.
            </div>
          </div>
          <div class="form-floating">
            <input type="text" class="form-control <?=(isset($errors['first_name']) ? 'is-invalid': '') ?>" id="first_name" name="first_name" minlength="3" maxlength="100" <?= $isModify ? 'value="' . Security::secureInput($employe->getFirst_name()) . '"' : ''  ?> required>
            <label for="first_name">Prénom</label>
            <div class="invalid-feedback">
              Entrez le prénom.
            </div>
          </div>
        </div>
        <!-- Affichage adresse, code postal, ville et Gamestore le plus proche -->
        <div class="form-floating mb-3">
          <input type="text" class="form-control <?=(isset($errors['address']) ? 'is-invalid': '') ?>" id="address" name="address" minlength="3" maxlength="255" <?= $isModify ? 'value="' . Security::secureInput($employe->getAddress()) . '"' : ''  ?> required>
          <label for="address">Adresse</label>
          <div class="invalid-feedback">
              Entrez l'adresse.
          </div>
        </div>
        <div class="input-group mb-3">
          <div class="form-floating">
            <input type="number" class="form-control <?=(isset($errors['postcode']) ? 'is-invalid': '') ?>" id="postcode" name="postcode" min="0" max="99999" <?= $isModify ? 'value="' . Security::secureInput($employe->getPostcode()) . '"' : ''  ?> required>
            <label for="postcode">Code postal</label>
            <div class="invalid-feedback">
              Entrez le code postal.
            </div>
          </div>
          <div class="form-floating">
            <input type="text" class="form-control <?=(isset($errors['city']) ? 'is-invalid': '') ?>" id="city" name="city" minlength="3" maxlength="100" <?= $isModify ? 'value="' . Security::secureInput($employe->getCity()) . '"' : ''  ?> required>
            <label for="city">Ville</label>
            <div class="invalid-feedback">
              Entrez la ville.
            </div>
          </div>
        </div>
        <div class="form-floating mb-3">
          <select class="form-select" id="store" name="store_id" required>
            <?php if ($isModify) : ?>
              <option value="1" <?= Security::secureInput($employe->getFk_store_id()) == 1 ? 'selected' : '' ?>>Nantes</option>
              <option value="2" <?= Security::secureInput($employe->getFk_store_id()) == 2 ? 'selected' : '' ?>>Lille</option>
              <option value="3" <?= Security::secureInput($employe->getFk_store_id()) == 3 ? 'selected' : '' ?>>Bordeaux</option>
              <option value="4" <?= Security::secureInput($employe->getFk_store_id()) == 4 ? 'selected' : '' ?>>Paris</option>
              <option value="5" <?= Security::secureInput($employe->getFk_store_id()) == 5 ? 'selected' : '' ?>>Toulouse</option>
            <?php else : ?>
              <option value="1">Nantes</option>
              <option value="2">Lille</option>
              <option value="3">Bordeaux</option>
              <option value="4">Paris</option>
              <option value="5">Toulouse</option>
            <?php endif; ?>
          </select>
          <label for="store">Magasin Gamestore</label>
        </div>
        <!-- Adresse mail -->
        <div class="form-floating mb-3">
          <input type="email" class="form-control <?=(isset($errors['email']) ? 'is-invalid': '') ?>" id="email" name="email" <?= $isModify ? 'value="' . Security::secureInput($employe->getEmail()) . '"' : ''  ?> required>
          <label for="email">Adresse mail</label>
          <div class="invalid-feedback">
            Entrez votre adresse mail.
          </div>
        </div>
        <div class="text-center mt-3">
          <input type="submit" class="btn btn-gamestore text-uppercase" name="<?= $isModify ? 'modifyEmploye' : 'addEmploye'  ?>" value="<?= $isModify ? 'Modifier' : 'Ajouter'  ?>">
        </div>
      </form>
      <div class="text-center my-5">
        <a href="index.php?controller=admin&action=employes" class="btn btn-gamestore text-uppercase">Retour vers la page des employés</a>
      </div>
      <!-- END : Formulaire d'inscription -->
  </section>

  <?php require_once _TEMPLATEPATH_ . '/admin/footer.php'; ?>