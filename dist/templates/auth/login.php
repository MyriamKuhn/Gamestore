<?php 

require './vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = new Dotenv(_ROOTPATH_);
$dotenv->load();

require_once _TEMPLATEPATH_ . '/header.php'; 

?>

<!-- START : Main -->
<main class="container my-4 main" id="hero">
  <section class="mt-2">
    <div class="d-flex justify-content-between gamestore-title">
      <h2 class="text-uppercase">Connexion à l'espace client</h2>
    </div>
    <div class="my-3">

      <p>Connectez-vous à votre espace client pour accéder à l'ensemble de nos services. Vous pourrez ainsi suivre vos commandes, gérer vos informations personnelles et bénéficier de nos offres exclusives.</p>
      <!-- Affichage des erreurs -->
      <div class="alert alert-danger py-5 my-5 <?= empty($errors) ? 'visually-hidden' : '' ?>" id="error-message">
        <?php if (!empty($errors)) {
          foreach ($errors as $error) {
            echo $error . '<br>';
          }
        } ?>
      </div>

      <!-- START : Formulaire de connexion -->
      <form method="post" id="login-form" class="needs-validation my-5" novalidate>
        <!-- Inclusion du token CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
        <!-- Email -->
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

        <!-- Widget reCAPTCHA -->
        <div class="g-recaptcha mt-4" data-sitekey="<?= $_ENV['SITE_RECAPTCHA_KEY'] ?>"></div>

        <div class="text-center mt-3">
          <input type="submit" class="btn btn-gamestore text-uppercase" name="loginUser" value="Se connecter">
        </div>
      </form>
      <!-- END : Formulaire de connexion -->

    </div>


<?php require_once _TEMPLATEPATH_ . '/footer.php'; ?>