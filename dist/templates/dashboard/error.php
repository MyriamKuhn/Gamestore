<?php require_once _TEMPLATEPATH_ . '/dashboard/header.php'; ?>

<section class="container my-5">
  <div class="d-flex justify-content-between gamestore-title mb-4">
    <h2 class="text-uppercase">Oups - Une erreur s'est produite</h2>
  </div>
	<?php if ($error) :?>
		<div class="alert alert-danger py-5 my-5"><?= $error; ?></div>
	<?php endif; ?>
  <div class="mt-5 text-center">
    <a href="index.php?controller=dashboard&action=home" class="btn btn-gamestore text-uppercase">Retourner à l'accueil</a>
  </div>
</section>

<?php require_once _TEMPLATEPATH_ . '/dashboard/footer.php'; ?>