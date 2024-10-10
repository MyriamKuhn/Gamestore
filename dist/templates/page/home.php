<?php

require_once _TEMPLATEPATH_.'/header.php'; 

use App\Tools\StringTools;
use App\Tools\FileTools;
use App\Tools\Security;


$needle = 'spotlight';

?>

<!-- START : Main -->
<main class="container my-4 main">
    <!-- START : Hero -->
    <section class="hero row row-cols-1 row-cols-lg-2 align-items-lg-center">
      <div class="pe-lg-5">
        <h1 class="text-uppercase mb-0">Bienvenue chez Gamestore !</h1>
        <p><strong>Gamestore</strong> est votre destination incontournable pour tous vos besoins en <strong>jeux vidéo en France</strong>.</p>
        <p>Avec <strong>cinq magasins situés à Nantes, Lille, Bordeaux, Paris et Toulouse</strong>, nous offrons une large sélection de jeux sur toutes les plateformes populaires : PlayStation, Xbox, Nintendo Switch, et PC.</p>
        <p>Découvrez notre boutique en ligne pour une expérience d'achat pratique et enrichissante, accessible de n'importe où.</p>
        <p class="m-0"><strong>Réservez vos jeux préférés en ligne et venez les récupérer dans l'un des nos magasins !</strong></p>
        <p>Rejoignez notre communauté de passionnés et restez à jour avec les dernières sorties et offres spéciales.</p>
        <p><strong>Gamestore</strong>, c'est plus qu'un magasin de jeux vidéo, c'est votre univers de divertissement !</p>
        <a href="index.php?controller=page&action=about" class="btn btn-gamestore text-uppercase">En savoir plus</a>
      </div>
      <!-- Accordion -->
      <div class="mt-4 ps-lg-5">
        <div class="accordion accordion-flush" id="accordionFlushHero">
          <!-- Comment acheter -->
          <div class="accordion-item">
            <div class="accordion-header">
              <button class="accordion-button collapsed text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                Comment acheter
              </button>
            </div>
            <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushHero">
              <div class="accordion-body">Placez le ou les jeux de votre choix dans votre panier, définissez une date de retrait dans votre magasin préféré lors de la confirmation de votre panier et venez retirer vos jeux directement en magasin. Vous avez 7 jours pour retirer votre réservation. <a href="index.php?controller=page&action=buy" class="text-link">Plus d'infos</a></div>
            </div>
          </div>
          <!-- Pourquoi Choisir Gamestore -->
          <div class="accordion-item">
            <div class="accordion-header">
              <button class="accordion-button collapsed text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                Pourquoi Choisir Gamestore
              </button>
            </div>
            <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushHero">
              <div class="accordion-body">
                <ol>
                  <li class="pb-3">
                    <strong>Expertise locale :</strong> en tant qu'acteur local avec des magasins dans les principales villes de France, nous comprenons les besoins et les préférences de notre clientèle.
                  </li>
                  <li class="pb-3">
                    <strong>Sélection diversifiée :</strong> notre engagement à fournir une gamme complète de jeux sur toutes les plateformes assure que chaque joueur trouve son bonheur chez nous.
                  </li>
                  <li>
                    <strong>Service client dévoué :</strong> nos équipes en magasin et bientôt en ligne sont passionnées et prêtes à offrir des conseils avisés et un service de qualité.
                  </li>
                </ol>
              </div>
            </div>
          </div>
          <!-- Nous Contacter -->
          <div class="accordion-item">
            <div class="accordion-header">
              <button class="accordion-button collapsed text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                Nous Contacter
              </button>
            </div>
            <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushHero">
              <div class="accordion-body">Vous pouvez toujours nous rendre visite dans l'un de nos cinq magasins ou nous suivre sur nos réseaux sociaux pour les dernières nouvelles et les offres spéciales. Vous trouverez les coordonnées de nos magasins sur la <a href="index.php?controller=page&action=contact" class="text-link">page de contact</a>.</div>
            </div>
          </div>
          <!-- Nos prestations -->
          <div class="accordion-item">
            <div class="accordion-header">
              <button class="accordion-button collapsed text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                Nos prestations
              </button>
            </div>
            <div id="flush-collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionFlushHero">
              <div class="accordion-body">
                <ol>
                  <li class="pb-3">
                    <strong>Conseils personnalisés en magasin :</strong> nos équipes passionnées sont à votre disposition dans nos magasins pour vous offrir des recommandations sur les jeux et les équipements, adaptés à vos besoins et à vos envies.
                  </li>
                  <li class="pb-3">
                    <strong>Réservation de jeux en avant-première :</strong> soyez parmi les premiers à obtenir les dernières sorties grâce à notre service de précommande en magasin et bientôt en ligne.
                  </li>
                  <li>
                    <strong>Offres spéciales et promotions :</strong> profitez de promotions régulières et d'offres exclusives en magasin et bientôt sur notre plateforme en ligne.
                  </li>
                </ol>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- END : Hero -->
    <!-- START : News -->
    <section class="news mt-5">
      <div class="d-flex justify-content-between gamestore-title">
        <h2 class="text-uppercase">Nouveautés</h2>
        <a href="index.php?controller=games&action=list" class="btn btn-gamestore-outline text-uppercase align-self-start">Voir tout</a>
      </div>
      <div class="mt-3 row row-cols-1 row-cols-lg-5 justify-content-center justify-content-xl-between gap-4">
        <?php foreach ($lastGamesDatas as $lastGameData) : 
          $spotlight = FileTools::getImagesAsCategory('spotlight', $lastGameData['images']) ?>
        <!-- START : Card News -->
        <div class="card gamestore-card" style="width: 15rem;">
          <div class="card-img-block">
            <img class="card-img-top" src="<?= Security::secureInput(_GAMES_IMAGES_FOLDER_.reset($spotlight)) ?>" alt="<?= html_entity_decode(Security::secureInput($lastGameData['game_name'])) ?>" loading="lazy">
            <span class="badge position-absolute badge rounded-pill text-uppercase py-1 px-2">Nouveauté</span>
          </div>
          <div class="card-body card-body-news pt-0">
            <div class="card-title text-uppercase text-center"><?= html_entity_decode(Security::secureInput($lastGameData['game_name'])) ?></div>
            <div class="d-flex justify-content-between align-items-center">
              <div>
              <?php foreach ($lastGameData['platforms'] as $lastGamePlatform) : ?>
                <img src="<?= Security::secureInput(_ASSETS_IMAGES_FOLDER_.'platforms/'.StringTools::slugify($lastGamePlatform).'.svg') ?>" alt="<?= Security::secureInput($lastGamePlatform) ?>" width="25">
              <?php endforeach; ?>
              </div>
              <div>
                <img src="<?= Security::secureInput(_ASSETS_IMAGES_FOLDER_.'pegi/'.$lastGameData['pegi_name'].'.jpg') ?>" alt="<?= Security::secureInput($lastGameData['pegi_name']) ?>" width="30">
              </div>
            </div>
          </div>
          <div class="row row-cols-1 justify-content-center">
            <a href="index.php?controller=games&action=show&id=<?= Security::secureInput($lastGameData['game_id']) ?>" class="news-card-footer text-uppercase py-3 text-center text-decoration-none">Plus d'infos</a>
          </div>
        </div>
        <!-- END : Card News -->
        <?php endforeach; ?>
      </div>
    </section>
    <!-- END : News -->
    <!-- START : Banner Community -->
    <section class="banner row row-cols-1 mt-4">
      <div class="banner-community py-4">
        <h3 class="text-uppercase text-center">Rejoignez notre communauté de gamers</h3>
        <div class="text-center">
          <a href="https://www.facebook.com/" target="_blank" class="banner-logo pe-3"><i class="bi bi-facebook fs-1 footer-logo-img"></i></a>
          <a href="https://www.instagram.com/" target="_blank" class="banner-logo pe-3"><i class="bi bi-instagram fs-1 footer-logo-img"></i></a>
          <a href="https://www.twitter.com/" target="_blank" class="banner-logo"><i class="bi bi-twitter-x fs-1 footer-logo-img"></i></a>
        </div>
      </div>
    </section>
    <!-- END : Banner Community -->
    <!-- START : Promos -->
    <section class="news mt-4">
      <div class="d-flex justify-content-between gamestore-title">
        <h2 class="text-uppercase">Promos</h2>
        <a href="index.php?controller=games&action=promo" class="btn btn-gamestore-outline text-uppercase align-self-start">Voir tout</a>
      </div>
      <div class="mt-3 row row-cols-1 row-cols-lg-5 justify-content-center justify-content-lg-evenly gap-4">
        <!-- START : Card Promos -->
        <?php foreach ($reducedGamesDatas as $reducedGameData) : 
          $spotlight = FileTools::getImagesAsCategory('spotlight', $reducedGameData['images']);
          $platformPrice = (float) $reducedGameData['platform_price'];
          $discountRate = (float) $reducedGameData['discount_rate'];
          $reducedPrice = ($platformPrice * (1 - $discountRate));
          ?>
        <div class="card gamestore-card" style="width: 18rem;">
          <div class="card-img-block">
            <img class="card-img-top" src="<?= Security::secureInput(_GAMES_IMAGES_FOLDER_.reset($spotlight)) ?>" alt="<?= html_entity_decode(Security::secureInput($reducedGameData['game_name'])) ?>" loading="lazy">
            <?php if ($reducedGameData['is_new'] == 1) : ?>
              <span class="badge-new position-absolute rounded-pill text-uppercase py-1 px-2">Nouveauté</span>
            <?php endif; ?>
            <span class="badge position-absolute badge rounded-pill text-uppercase py-1 px-2">Promo</span>
          </div>
          <div class="card-body card-body-promos pt-0">
            <div class="card-title text-uppercase text-center pb-2"><?= html_entity_decode(Security::secureInput($reducedGameData['game_name'])) ?></div>
            <div class="d-flex justify-content-center">
              <div class="card-percent"><?= Security::secureInput(($reducedGameData['discount_rate'] * 100)) ?></div>
              <img src="/assets/images/percent_icon.svg" alt="Image représentant un pourcentage">
              <div class="d-flex flex-column align-items-center justify-content-center ps-3">
                <div class="card-price m-0"><?= Security::secureInput(number_format($reducedPrice, 2)) ?> €</div>
                <div class="text-decoration-line-through"><?= Security::secureInput($reducedGameData['platform_price']) ?> €</div>
              </div>
            </div>
            <h5 class="text-center">Uniquement à <?= Security::secureInput($reducedGameData['store_location']) ?></h5>
            <div class="d-flex justify-content-between align-items-center">
              <div>
                  <img src="<?= Security::secureInput(_ASSETS_IMAGES_FOLDER_.'platforms/'.StringTools::slugify($reducedGameData['platform_name']).'.svg') ?>" alt="<?= Security::secureInput($reducedGameData['platform_name']) ?>" width="25">
              </div>
              <div>
                <img src="<?= Security::secureInput(_ASSETS_IMAGES_FOLDER_.'pegi/'.$reducedGameData['pegi_name'].'.jpg') ?>" alt="<?= Security::secureInput($reducedGameData['pegi_name']) ?>" width="30">
              </div>
            </div>
          </div>
          <div class="row row-cols-1 justify-content-center">
            <a href="index.php?controller=games&action=show&id=<?= Security::secureInput($reducedGameData['game_id']) ?>" class="news-card-footer text-uppercase py-3 text-center text-decoration-none">Plus d'infos</a>
          </div>
        </div>
        <!-- END : Card Promo -->
        <?php endforeach; ?>
      </div>
    </section>
    <!-- END : Promos -->
    <!-- START : Banner NL -->
    <section class="banner row row-cols-1 mt-4">
      <div class="banner-community py-4">
        <h3 class="text-uppercase text-center">Ne ratez plus aucune nouveauté ou promo</h3>
        <div class="text-center">
          <a href="index.php?controller=page&action=contact" class="btn btn-gamestore text-uppercase shadow me-lg-5">Inscrivez-vous</a>
        </div>
      </div>
    </section>
    <!-- END : Banner NL -->

<?php require_once _TEMPLATEPATH_.'/footer.php'; ?>