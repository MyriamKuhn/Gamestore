<?php require_once _TEMPLATEPATH_.'/header.php'; ?>

<!-- START : Main -->
<main class="container my-4 main">
	<section class="mt-5">
		<div class="d-flex justify-content-between gamestore-title">
			<h2 class="text-uppercase">Oups - Une erreur s'est produite</h2>
		</div>
			<?php if ($error) :?>
				<div class="alert alert-danger py-5 my-5"><?= $error; ?></div>
			<?php endif; ?>
	</section>

	<?php require_once _TEMPLATEPATH_.'/footer.php'; ?>
