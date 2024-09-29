<?php 

require './vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = new Dotenv(_ROOTPATH_);
$dotenv->load();

require_once _TEMPLATEPATH_ . '/header.php'; 

?>

<main class="container my-4 main">
  <section class="mt-2">
    <div class="d-flex justify-content-between gamestore-title">
      <h2 class="text-uppercase">Créez votre compte</h2>
    </div>
    <div class="my-3">
      <p>Créez votre compte pour accéder à l'ensemble de nos services. Vous pourrez ainsi suivre vos commandes, gérer vos informations personnelles et bénéficier de nos offres exclusives.</p>
      <!-- Affichage des erreurs -->
      <div class="alert alert-danger py-5 my-5 <?= empty($errors) ? 'visually-hidden' : '' ?>" id="error-message">
        <?php if (!empty($errors)) {
          foreach ($errors as $error) {
            echo $error . '<br>';
          }
        } ?>
      </div>
      <!-- START : Formulaire d'inscription -->
      <form method="post" id="register-form" class="needs-validation" novalidate>
        <!-- Inclusion du token CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
        <!-- Nom et prénom -->
        <div class="input-group mb-3">
          <div class="form-floating">
            <input type="text" class="form-control <?=(isset($errors['last_name']) ? 'is-invalid': '') ?>" id="last_name" name="last_name" minlength="3" maxlength="100" required>
            <label for="last_name">Nom</label>
            <div class="invalid-feedback">
              Entrez votre nom.
            </div>
          </div>
          <div class="form-floating">
            <input type="text" class="form-control <?=(isset($errors['first_name']) ? 'is-invalid': '') ?>" id="first_name" name="first_name" minlength="3" maxlength="100" required>
            <label for="first_name">Prénom</label>
            <div class="invalid-feedback">
              Entrez votre prénom.
            </div>
          </div>
        </div>
        <!-- Adresse recherche autocomplétion -->
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="address-search" name="address-search" minlength="3" maxlength="255" required>
          <label for="address-search">Entrez votre adresse</label>
          <div class="invalid-feedback">
            Entrez votre adresse.
          </div>
        </div>
        <div id="suggestions"></div>
        <!-- Affichage adresse, code postal, ville et Gamestore le plus proche -->
        <div class="form-floating">
          <input type="text" readonly class="form-control <?=(isset($errors['address']) ? 'is-invalid': '') ?>" id="address" name="address" minlength="3" maxlength="255" required>
          <label for="address">Adresse</label>
        </div>
        <div class="input-group">
          <div class="form-floating">
            <input type="number" readonly class="form-control <?=(isset($errors['postcode']) ? 'is-invalid': '') ?>" id="postcode" name="postcode" min="0" max="99999" required>
            <label for="postcode">Code postal</label>
          </div>
          <div class="form-floating">
            <input type="text" readonly class="form-control <?=(isset($errors['city']) ? 'is-invalid': '') ?>" id="city" name="city" minlength="3" maxlength="100" required>
            <label for="city">Ville</label>
          </div>
        </div>
        <div class="form-floating mb-3">
          <input type="text" readonly class="form-control" id="nearest_store" name="nearest_store" minlength="3" maxlength="255" required>
          <label for="nearest_store">Votre Gamestore le plus proche</label>
          Vous pourrez modifier ces informations après votre inscription.
        </div>
        <!-- Adresse mail -->
        <div class="form-floating mb-3">
          <input type="email" class="form-control <?=(isset($errors['email']) ? 'is-invalid': '') ?>" id="email" name="email" required>
          <label for="email">Adresse mail</label>
          <div class="invalid-feedback">
            Entrez votre adresse mail.
          </div>
        </div>
        <!-- Mot de passe -->
        <div class="input-group">
          <div class="form-floating">
            <input type="password" class="form-control <?=(isset($errors['password']) ? 'is-invalid': '') ?>" id="password" name="password" required>
            <label for="password">Mot de passe</label>
          </div>
          <span class="input-group-text"><i class="bi bi-eye-slash toggleIconSubscribe"></i></span>
        </div>
        <div class="input-group">
          <div class="form-floating">
            <input type="password" class="form-control" id="password-confirm" name="password-confirm" required>
            <label for="password-confirm">Confirmation du mot de passe</label>
          </div>
          <span class="input-group-text"><i class="bi bi-eye-slash toggleIconConfirm"></i></span>
        </div>
        <div class="password-error">
          <!-- Emplacement pour afficher les erreurs de mot de passe -->
        </div>

        <!-- Widget reCAPTCHA -->
        <div class="g-recaptcha mt-4" data-sitekey="<?= $_ENV['SITE_RECAPTCHA_KEY'] ?>"></div>

        <div class="text-center mt-3">
          <input type="submit" class="btn btn-gamestore text-uppercase" name="registerUser" value="S'inscrire">
        </div>
      </form>
      <!-- END : Formulaire d'inscription -->
    </div>
  </section>

    <?php require_once _TEMPLATEPATH_ . '/footer.php'; ?>