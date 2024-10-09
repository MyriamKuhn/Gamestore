<?php 

require_once _TEMPLATEPATH_.'/header.php'; 

if ($error =="Vous n'avez pas les droits pour accéder à cette page, veuillez vous connecter") {
	header('refresh:5;url=index.php?controller=auth&action=logout');
};

?>

<!-- START : Main -->
<main class="container my-4 main">
	<section class="mt-5">
		<div class="d-flex justify-content-between gamestore-title">
			<h2 class="text-uppercase">Oups - Une erreur s'est produite</h2>
		</div>
			<?php if ($error) :?>
				<div class="alert alert-danger py-5 my-5"><?= $error; ?></div>
			<?php endif; ?>
		<div class="mt-5 text-center">
    	<a href="index.php?controller=page&action=home" class="btn btn-gamestore text-uppercase">Retourner à l'accueil</a>
  	</div>
	</section>

	<?php require_once _TEMPLATEPATH_.'/footer.php'; ?>
