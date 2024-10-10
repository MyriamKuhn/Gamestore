<?php 

use App\Tools\Security;
use App\Tools\NavigationTools;

require_once _TEMPLATEPATH_ . '/dashboard/header.php'; 

?>

        <section class="container mt-5 d-flex flex-column align-items-center mb-5">
          <img src="/assets/images/logo_big.svg" alt="Logo de l'entreprise Gamestore" class="my-5" style="width: 200px;">
          <p class="mt-3 fs-2 text-center">Bienvenue dans votre espace client, <?= Security::getCurrentUserFullName() ?>.</p>
          <p>Vous pouvez gérer vos informations personnelles, suivre vos commandes, consulter vos factures et bénéficier de nos services en ligne.</p>
          <div class="row my-5">
            <div class="col-md-6 mt-2">
              <div class="card shadow">
                <div class="card-body d-flex flex-column">
                  <h2 class="card-title text-uppercase"><i class="bi bi-person-fill me-2"></i>Données personnelles</h2>
                  <p class="card-text">Consultez et modifiez vos informations personnelles.</p>
                  <a href="index.php?controller=dashboard&action=modify" class="btn btn-gamestore-outline text-uppercase align-self-end">Accéder</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 mt-2 <?= NavigationTools::showCart($cartContent) ?>">
              <div class="card shadow">
                <div class="card-body d-flex flex-column">
                  <h2 class="card-title text-uppercase"><i class="bi bi-cart2 me-2"></i>Panier</h2>
                  <p class="card-text">Consultez, modifiez et validez le contenu de votre panier.</p>
                  <a href="index.php?controller=dashboard&action=cart" class="btn btn-gamestore-outline text-uppercase align-self-end">Accéder</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 mt-2">
              <div class="card shadow">
                <div class="card-body d-flex flex-column">
                  <h2 class="card-title text-uppercase"><i class="bi bi-receipt-cutoff me-2"></i>Commandes</h2>
                  <p class="card-text">Consultez l'historique de vos commandes et vos factures.</p>
                  <a href="index.php?controller=dashboard&action=orders" class="btn btn-gamestore-outline text-uppercase align-self-end">Accéder</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 mt-2">
              <div class="card shadow">
                <div class="card-body d-flex flex-column">
                  <h2 class="card-title text-uppercase"><i class="bi bi-box-arrow-in-right me-2"></i>Déconnexion</h2>
                  <p class="card-text">Déconnectez-vous et accédez à la page d'accueil.</p>
                  <a href="index.php?controller=auth&action=logout" class="btn btn-gamestore-outline text-uppercase align-self-end">Accéder</a>
                </div>
              </div>
            </div>
          </div>
          <a href="index.php?controller=page&action=home" class="btn btn-gamestore text-uppercase">Retourner voir les jeux</a>
        </section>
        
<?php require_once _TEMPLATEPATH_ . '/dashboard/footer.php'; ?>