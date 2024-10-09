<?php 

require './vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = new Dotenv(_ROOTPATH_);
$dotenv->load();

require_once _TEMPLATEPATH_ . '/header.php'; 

?>

<!-- START : Main -->
<main class="container my-4 main">
  <section class="mt-2">
    <div class="d-flex justify-content-between gamestore-title">
      <h2 class="text-uppercase">Mot de passe oublié ?</h2>
    </div>
    <div class="my-3">

      <p>Vous avez oublié votre mot de passe ? Entrez votre adresse mail d'inscription, nous vous ferons parvenir un mail avec un lien pour récupérer votre mot de passe.</p>
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

      <!-- START : Formulaire de connexion -->
      <form method="post" id="password" class="needs-validation my-5">
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
        
        <!-- Widget reCAPTCHA -->
        <div class="g-recaptcha mt-4" data-sitekey="<?= $_ENV['SITE_RECAPTCHA_KEY'] ?>"></div>

        <div class="text-center mt-3">
          <input type="submit" class="btn btn-gamestore text-uppercase" name="password" value="Envoyer">
        </div>
      </form>
      <!-- END : Formulaire de connexion -->

    </div>


<?php require_once _TEMPLATEPATH_ . '/footer.php'; ?>