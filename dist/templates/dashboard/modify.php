<?php 

use App\Tools\Security;

require_once _TEMPLATEPATH_ . '/dashboard/header.php'; 

?>

<section class="container my-5">
  <div class="d-flex justify-content-between gamestore-title mb-4">
    <h2 class="text-uppercase">Modifiez vos données personnelles</h2>
  </div>
  <!-- Affichage des erreurs -->
  <div class="alert alert-danger py-5 my-5 <?= empty($errors) ? 'visually-hidden' : '' ?>" id="error-message">
  <?php if (!empty($errors)) {
    foreach ($errors as $error) {
      echo $error . '<br>';
    }
  } ?>
  </div>
  <div class="alert alert-success py-5 my-5 <?= empty($success) ? 'visually-hidden' : '' ?>" id="success-message">
    <?php if (!empty($success)) echo $success; ?>
  </div>
  <!-- START : Formulaire de modification des données personnelles -->
  <form method="post" id="personal-form" class="needs-validation">
    <!-- Inclusion du token CSRF -->
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
    <!-- Nom et prénom -->
    <div class="input-group mb-3">
      <div class="form-floating">
        <input type="text" class="form-control <?=(isset($errors['last_name']) ? 'is-invalid': '') ?>" id="last_name" name="last_name" minlength="3" maxlength="100" value="<?= Security::secureInput($user->getFirst_name()) ?>" required>
        <label for="last_name">Nom</label>
        <div class="invalid-feedback">
          Entrez votre nom.
        </div>
      </div>
      <div class="form-floating">
        <input type="text" class="form-control <?=(isset($errors['first_name']) ? 'is-invalid': '') ?>" id="first_name" name="first_name" minlength="3" maxlength="100" value="<?= Security::secureInput($user->getLast_name()) ?>" required>
        <label for="first_name">Prénom</label>
        <div class="invalid-feedback">
          Entrez votre prénom.
        </div>
      </div>
    </div>
    <!-- Affichage adresse, code postal, ville -->
    <div class="form-floating mb-3">
      <input type="text" class="form-control <?=(isset($errors['address']) ? 'is-invalid': '') ?>" id="address" name="address" minlength="3" maxlength="255" value="<?= Security::secureInput($user->getAddress()) ?>" required>
      <label for="address">Adresse</label>
      <div class="invalid-feedback">
        Entrez votre adresse.
      </div>
    </div>
    <div class="input-group mb-3">
      <div class="form-floating">
        <input type="number" class="form-control <?=(isset($errors['postcode']) ? 'is-invalid': '') ?>" id="postcode" name="postcode" min="0" max="99999" value="<?= Security::secureInput($user->getPostcode()) ?>" required>
        <label for="postcode">Code postal</label>
        <div class="invalid-feedback">
          Entrez votre code postal.
        </div>
      </div>
      <div class="form-floating">
        <input type="text" class="form-control <?=(isset($errors['city']) ? 'is-invalid': '') ?>" id="city" name="city" minlength="3" maxlength="100" value="<?= Security::secureInput($user->getCity()) ?>" required>
        <label for="city">Ville</label>
        <div class="invalid-feedback">
          Entrez votre ville.
        </div>
      </div>
    </div>
    <div class="text-end mt-3">
      <input type="submit" class="btn btn-gamestore text-uppercase" name="modifyUser" value="Modifier">
    </div>
  </form>
  <!-- END : Formulaire de modification des données personnelles -->
  <!-- Gamestore le plus proche -->
  <div class="d-flex justify-content-between gamestore-title mt-5 mb-4">
    <h2 class="text-uppercase">Modifiez votre Gamestore le plus proche</h2>
  </div>
  <!-- START : Formulaire de modification du Gamestore le plus proche -->
  <form method="post" id="gamestore-form" class="needs-validation">
    <!-- Inclusion du token CSRF -->
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
    <div class="form-floating mb-3">
      <select class="form-select" id="nearest_store" name="nearest_store" required>
        <option value="1" <?= (Security::secureInput($user->getFk_store_id()) == 1) ? 'selected' : '' ?>>Gamestore Nantes, 42 Rue des Joueurs, 44000 Nantes</option>
        <option value="2" <?= (Security::secureInput($user->getFk_store_id()) == 2) ? 'selected' : '' ?>>Gamestore Lille, 15 Rue du Pixel, 59000 Lille</option>
        <option value="3" <?= (Security::secureInput($user->getFk_store_id()) == 3) ? 'selected' : '' ?>>Gamestore Bordeaux, 23 Place du Geek, 33000 Bordeaux</option>
        <option value="4" <?= (Security::secureInput($user->getFk_store_id()) == 4) ? 'selected' : '' ?>>Gamestore Paris, 12 Rue du Gamer, 75001 Paris</option>
        <option value="5" <?= (Security::secureInput($user->getFk_store_id()) == 5) ? 'selected' : '' ?>>Gamestore Toulouse, 67 Avenue du Game, 31000 Toulouse</option>
      </select>
      <label for="nearest_store">Votre Gamestore le plus proche</label>
    </div>
    <div class="text-end mt-3">
      <input type="submit" class="btn btn-gamestore text-uppercase" name="modifyStore" value="Modifier">
    </div>
  </form>
  <!-- END : Formulaire de modification du Gamestore le plus proche -->
  <div class="d-flex justify-content-between gamestore-title mt-5 mb-4">
    <h2 class="text-uppercase">Modifiez votre mot de passe</h2>
  </div>
  <!-- START : Formulaire de modification du mot de passe -->
  <form method="post" id="password-form" class="needs-validation">
    <!-- Inclusion du token CSRF -->
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
    <!-- Mot de passe -->
    <div class="input-group mb-3">
      <div class="form-floating">
        <input type="password" class="form-control <?=(isset($errors['password']) ? 'is-invalid': '') ?>" id="passwordOld" name="passwordOld" required>
        <label for="passwordOld">Mot de passe actuel</label>
      </div>
      <span class="input-group-text"><i class="bi bi-eye-slash toggleIconOld"></i></span>
    </div>
    <div class="input-group">
      <div class="form-floating">
        <input type="password" class="form-control <?=(isset($errors['password']) ? 'is-invalid': '') ?>" id="passwordNew" name="passwordNew" required>
        <label for="passwordNew">Nouveau mot de passe</label>
      </div>
      <span class="input-group-text"><i class="bi bi-eye-slash toggleIconNew"></i></span>
    </div>
    <div class="input-group">
      <div class="form-floating">
        <input type="password" class="form-control" id="password-confirm" name="password-confirm" required>
        <label for="password-confirm">Confirmation du mot de passe</label>
      </div>
      <span class="input-group-text"><i class="bi bi-eye-slash toggleIconConfirm"></i></span>
    </div>
    <div class="password-error">
      <!-- Affichage des erreurs -->
    </div>
    <div class="text-end mt-3">
      <input type="submit" class="btn btn-gamestore text-uppercase" name="modifyPassword" value="Modifier">
    </div>
  </form>
  <!-- END : Formulaire de modification du mot de passe -->
</section>


<?php require_once _TEMPLATEPATH_ . '/dashboard/footer.php'; ?>