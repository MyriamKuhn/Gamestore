<?php 

require_once _TEMPLATEPATH_ . '/admin/header.php'; 

?>

  <section class="container my-5">
    <div class="d-flex justify-content-between gamestore-title mb-4">
      <h2 class="text-uppercase">Modification de votre mot de passe</h2>
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
        
<?php require_once _TEMPLATEPATH_ . '/admin/footer.php'; ?>