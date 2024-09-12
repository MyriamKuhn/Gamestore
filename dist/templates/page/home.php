<?php require_once _TEMPLATEPATH_.'/header.php'; ?>

<!-- START : Main -->
<main class="container my-4 main" id="hero">
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
        <a href="#" class="btn btn-gamestore text-uppercase">En savoir plus</a>
      </div>
      <div class="mt-4 ps-lg-5">
        <div class="accordion accordion-flush" id="accordionFlushHero">
          <div class="accordion-item">
            <div class="accordion-header">
              <button class="accordion-button collapsed text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                Comment acheter
              </button>
            </div>
            <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushHero">
              <div class="accordion-body">Placez le ou les jeux de votre choix dans votre panier, définissez une date de retrait dans votre magasin préféré lors de la confirmation de votre panier et venez retirer vos jeux directement en magasin. Vous avez 7 jours pour retirer votre réservation.</div>
            </div>
          </div>
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
          <div class="accordion-item">
            <div class="accordion-header">
              <button class="accordion-button collapsed text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                Nous Contacter
              </button>
            </div>
            <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushHero">
              <div class="accordion-body">Vous pouvez toujours nous rendre visite dans l'un de nos cinq magasins ou nous suivre sur nos réseaux sociaux pour les dernières nouvelles et les offres spéciales. Vous trouverez les coordonnées de nos magasins sur la <a href="#" class="text-link">page de contact</a>.</div>
            </div>
          </div>
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
        <a href="#" class="btn btn-gamestore-outline text-uppercase align-self-start">Voir tout</a>
      </div>
      <div class="mt-3 row row-cols-1 row-cols-lg-5 justify-content-center gap-4">
        <!-- START : Card News -->
        <div class="card gamestore-card" style="width: 15rem;">
          <div class="card-img-block">
            <img class="card-img-top" src="./uploads/games/spotlight-alyx.jpg" alt="Half-Life : Alyx">
            <span class="badge position-absolute badge rounded-pill text-uppercase py-1 px-2">Nouveauté</span>
          </div>
          <div class="card-body pt-0">
            <h5 class="card-title text-uppercase text-center">Half-Life : Alyx</h5>
            <div class="card-price pb-3">59,99 €</div>
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <img src="./assets/images/platforms/pc-display.svg" alt="PC" width="25">
                <img src="./assets/images/platforms/playstation.svg" alt="Playstation" width="25">
              </div>
              <div>
                <img src="./assets/images/pegi/age-12-black.jpg" alt="Xbox" width="30">
              </div>
            </div>
          </div>
          <div class="row row-cols-1 justify-content-center">
            <a href="#" class="news-card-footer text-uppercase py-3 text-center text-decoration-none">Acheter</a>
          </div>
        </div>
        <!-- END : Card News -->
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
        <a href="#" class="btn btn-gamestore-outline text-uppercase align-self-start">Voir tout</a>
      </div>
      <div class="mt-3 row row-cols-1 row-cols-lg-5 justify-content-center gap-4">
        <!-- START : Card Promos -->
        <div class="card gamestore-card" style="width: 18rem;">
          <div class="card-img-block">
            <img class="card-img-top" src="./uploads/games/spotlight-alyx.jpg" alt="Half-Life : Alyx">
            <span class="badge position-absolute badge rounded-pill text-uppercase py-1 px-2">Promo</span>
          </div>
          <div class="card-body pt-0">
            <h5 class="card-title text-uppercase text-center pb-2">Half-Life : Alyx</h5>
            <div class="d-flex justify-content-center pb-3">
              <img src="./assets/images/percent_icon.svg" alt="Image représentant un pourcentage">
              <div class="d-flex flex-column align-items-center">
                <div class="card-price m-0">49,99 €</div>
                <div class="text-decoration-line-through">59,99 €</div>
              </div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <img src="./assets/images/platforms/pc-display.svg" alt="PC" width="25">
                <img src="./assets/images/platforms/playstation.svg" alt="Playstation" width="25">
              </div>
              <div>
                <img src="./assets/images/pegi/age-12-black.jpg" alt="Xbox" width="30">
              </div>
            </div>
          </div>
          <div class="row row-cols-1 justify-content-center">
            <a href="#" class="news-card-footer text-uppercase py-3 text-center text-decoration-none">Acheter</a>
          </div>
        </div>
        <!-- END : Card Promo -->
      </div>
    </section>
    <!-- END : Promos -->
    <!-- START : Banner NL -->
    <section class="banner row row-cols-1 mt-4">
      <div class="banner-community py-4">
        <h3 class="text-uppercase text-center">Ne ratez plus aucune nouveauté ou promo</h3>
        <div class="text-center">
          <a href="#" class="btn btn-gamestore text-uppercase shadow me-lg-5">Inscrivez-vous</a>
        </div>
      </div>
    </section>
    <!-- END : Banner NL -->

<?php require_once _TEMPLATEPATH_.'/footer.php'; ?>