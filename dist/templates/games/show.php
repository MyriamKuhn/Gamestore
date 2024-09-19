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
  <section class="py-4 row row-cols-1 row-cols-xl-2 mx-lg-5 mx-xl-0">
    <img class="mx-md-auto" src="<?= Security::secureInput(_GAMES_IMAGES_FOLDER_.reset($presentation)) ?>" alt="<?= Security::secureInput($game['game_name']) ?>" loading="lazy">
    <div class="pt-3 pt-lg-0 my-auto mx-lg-auto">
      <div id="select-platforms" class="mb-4">
        <!-- Emplacement des plateformes -->
      </div>
      <div class="row mb-1 align-items-center">
        <div class="col-12 col-sm-5">
          <i class="bi bi-check2 check" id="icon-nantes"></i>
          <span class="title-store text-uppercase">Gamestore Nantes : </span>
        </div>
        <div class="col-12 col-sm-2 d-flex flex-column align-items-center">
          <span class="price-show" id="price-nantes">13,99€</span>
          <div id="prices-container-nantes">
            <span id="discount-nantes">60%</span><span class="text-decoration-line-through full-price py-auto ps-1" id="oldprice-nantes">25,99€</div>
          </div>  
        <span class="col-12 col-sm-5 text-end" id="stock-nantes">15 exemplaires en stock</span>
      </div>
      <div class="row mb-1 align-items-center">
        <div class="col-12 col-sm-5">
          <i class="bi bi-check2 check" id="icon-lille"></i>
          <span class="title-store text-uppercase">Gamestore Lille : </span>
        </div>
        <div class="col-12 col-sm-2 d-flex flex-column align-items-center">
          <span class="price-show" id="price-lille">13,99€</span>
          <div id="prices-container-lille">
            <span id="discount-lille">60%</span><span class="text-decoration-line-through full-price py-auto ps-1" id="oldprice-lille">25,99€</div>
          </div>  
        <span class="col-12 col-sm-5 text-end" id="stock-lille">15 exemplaires en stock</span>
      </div>
      <div class="row mb-1 align-items-center">
        <div class="col-12 col-sm-5">
          <i class="bi bi-exclamation-lg warn" id="icon-bordeaux"></i>
          <span class="title-store text-uppercase">Gamestore Bordeaux : </span>
        </div>
        <div class="col-12 col-sm-2 d-flex flex-column align-items-center">
          <span class="price-show" id="price-bordeaux">13,99€</span>
          <div id="prices-container-bordeaux">
            <span id="discount-bordeaux">60%</span><span class="text-decoration-line-through full-price py-auto ps-1" id="oldprice-bordeaux">25,99€</div>
          </div>  
        <span class="col-12 col-sm-5 text-end" id="stock-bordeaux">15 exemplaires en stock</span>
      </div>
      <div class="row mb-1 align-items-center">
        <div class="col-12 col-sm-5">
          <i class="bi bi-percent percent" id="icon-paris"></i>
          <span class="title-store text-uppercase">Gamestore Paris : </span>
        </div>
        <div class="col-12 col-sm-2 d-flex flex-column align-items-center">
          <span class="price-show" id="price-paris">13,99€</span>
          <div id="prices-container-paris">
            <span id="discount-paris">60%</span><span class="text-decoration-line-through full-price py-auto ps-1" id="oldprice-paris">25,99€</div>
          </div>  
        <span class="col-12 col-sm-5 text-end" id="stock-paris">15 exemplaires en stock</span>
      </div>
      <div class="row mb-1 align-items-center">
        <div class="col-12 col-sm-5">
          <i class="bi bi-x-lg cross" id="icon-toulouse"></i>
          <span class="title-store text-uppercase">Gamestore Toulouse : </span>
        </div>
        <div class="col-12 col-sm-2 d-flex flex-column align-items-center">
          <span class="price-show" id="price-toulouse">13,99€</span>
          <div id="prices-container-toulouse">
            <span id="discount-toulouse">60%</span><span class="text-decoration-line-through full-price py-auto ps-1" id="oldprice-toulouse">25,99€</div>
          </div>  
        <span class="col-12 col-sm-5 text-end" id="stock-toulouse">15 exemplaires en stock</span>
      </div>
      <div class="d-flex justify-content-center">
        <button class="btn btn-gamestore text-uppercase px-5"><i class="bi bi-cart2"></i> Ajouter au panier</button>
      </div>
    </div>
  </section>
  <section class="flex-column-reverse flex-lg-row row row-cols-1 row-cols-lg-2 g-lg-5 pb-4 ">
    <div class="px-xl-4"><?= Security::secureInput($game['game_description']) ?></div>
    <div>
      <p class="text-uppercase title-show"><?= Security::secureInput($game['game_name']) ?></p>
      <p><span class="fw-bold">Genre : </span><?= Security::secureInput($game['genres']) ?></p>
      <img class="pb-3" src="<?= Security::secureInput(_ASSETS_IMAGES_FOLDER_.'pegi/'.$game['pegi_name'].'.jpg') ?>" alt="<?= Security::secureInput($game['pegi_name']) ?>" width="30">
    </div>
  </section>
  <section class="row row-cols-1 px-xl-5">
    <img src="<?= Security::secureInput(_GAMES_IMAGES_FOLDER_.reset($presentation)) ?>" alt="<?= Security::secureInput($game['game_name']) ?>">
    <!-- START : Pagination -->
    <section class="mt-3 mb-3">
      <div class="d-flex justify-content-center" id="pagination-container">
        Paginaton<!-- Emplacement de la pagination -->
      </div>
    </section>
    <!-- END : Pagination -->
  </section>
  <?php var_dump($game); ?>
</section>

<?php require_once _TEMPLATEPATH_.'/footer.php'; ?>