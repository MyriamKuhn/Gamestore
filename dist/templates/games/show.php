<?php 

use App\Tools\Security;
use App\Tools\FileTools;

require_once _TEMPLATEPATH_.'/header.php'; 

$presentation = FileTools::getImagesAsCategory('presentation', $game['images']) 

?>

<!-- START : Main -->
<main class="container my-4 main" id="hero">
<section class="mt-2">
  <div class="d-flex justify-content-between gamestore-title">
    <h2 class="text-uppercase"><?= Security::secureInput($game['game_name']) ?></h2>
  </div>
  <img src="<?= Security::secureInput(_GAMES_IMAGES_FOLDER_.reset($presentation)) ?>" alt="<?= Security::secureInput($game['game_name']) ?>">
  <?php var_dump($game); ?>
</section>

<?php require_once _TEMPLATEPATH_.'/footer.php'; ?>