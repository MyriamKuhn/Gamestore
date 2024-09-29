<?php 

require_once _TEMPLATEPATH_ . '/header.php'; 

?>

<main class="container my-4 main">
  <section class="mt-2">
    <div class="d-flex justify-content-between gamestore-title">
      <h2 class="text-uppercase">Réinitialisation de votre mot de passe</h2>
    </div>
    <div class="my-3">
      <p>Entrez un nouveau mot de passe et confirmez-le.</p>
      <!-- Affichage des erreurs -->
      <div class="alert alert-danger py-5 my-5 <?= empty($errors) ? 'visually-hidden' : '' ?>" id="error-message">
        <?php if (!empty($errors)) {
          foreach ($errors as $error) {
            echo $error . '<br>';
          }
        } ?>
      </div>
      <div class="alert alert-success py-5 my-5 <?= empty($success) ? 'visually-hidden' : '' ?>" id="success-message">
        <?php if (!empty($success)) echo $success ?>
      </div>

      <!-- START : Formulaire de réinitialisation du mot de passe -->
      <form method="post" id="reset-password-form" class="needs-validation" novalidate>
        <input type="hidden" name="token" value="<?= $token ?>">
        <!-- Inclusion du token CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
        <!-- Mot de passe -->
        <div class="input-group">
          <div class="form-floating">
            <input type="password" class="form-control <?=(isset($errors['password']) ? 'is-invalid': '') ?>" id="password" name="password" required>
            <label for="password">Mot de passe</label>
          </div>
          <span class="input-group-text"><i class="bi bi-eye-slash toggleIconPassword"></i></span>
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

        <div class="text-center mt-3">
          <input type="submit" class="btn btn-gamestore text-uppercase" name="resetPassword" value="Réinitialiser">
        </div>
      </form>
      <!-- END : Formulaire de réinitialisation du mot de passe -->
    </div>
  </section>

    <?php require_once _TEMPLATEPATH_ . '/footer.php'; ?>