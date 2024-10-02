<?php 

use App\Tools\Security;

require_once _TEMPLATEPATH_ . '/employe/header.php'; 

?>

        <section class="container mt-5 d-flex flex-column align-items-center mb-5">
          <img src="./assets/images/logo_big.svg" alt="Logo de l'entreprise Gamestore" class="my-5" style="width: 200px;">
          <p class="mt-3 fs-2 text-center">Bienvenue dans votre espace employé, <?= Security::secureInput(Security::getCurrentUserFullName()) ?>.</p>
          <p>Vous pouvez gérer le statut des commandes et analyser les ventes de votre magasin Gamestore <?= Security::secureInput(Security::getEmployeStore()) ?>.</p>
          <div class="row my-5">
            <div class="col-md-6 mt-2">
              <div class="card shadow">
                <div class="card-body d-flex flex-column">
                  <h2 class="card-title text-uppercase"><i class="bi bi-key me-2"></i>Mot de passe</h2>
                  <p class="card-text">Modifiez le mot de passe de votre compte employé régulièrement.</p>
                  <a href="index.php?controller=employe&action=password" class="btn btn-gamestore-outline text-uppercase align-self-end">Accéder</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 mt-2">
              <div class="card shadow">
                <div class="card-body d-flex flex-column">
                  <h2 class="card-title text-uppercase"><i class="bi bi-receipt-cutoff me-2"></i>Gestion des commandes</h2>
                  <p class="card-text">Gérez et validez toutes les commandes de votre Gamestore.</p>
                  <a href="index.php?controller=employe&action=orders" class="btn btn-gamestore-outline text-uppercase align-self-end">Accéder</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 mt-2">
              <div class="card shadow">
                <div class="card-body d-flex flex-column">
                  <h2 class="card-title text-uppercase"><i class="bi bi-shop me-2"></i>Gestion des ventes</h2>
                  <p class="card-text">Gérez les ventes effectuées directement dans votre Gamestore sans commande préalable.</p>
                  <a href="index.php?controller=employe&action=buying" class="btn btn-gamestore-outline text-uppercase align-self-end">Accéder</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 mt-2">
              <div class="card shadow">
                <div class="card-body d-flex flex-column">
                  <h2 class="card-title text-uppercase"><i class="bi bi-graph-up me-2"></i>Statistiques des ventes</h2>
                  <p class="card-text">Consultez et analysez toutes les ventes de votre Gamestore.</p>
                  <a href="index.php?controller=employe&action=sales" class="btn btn-gamestore-outline text-uppercase align-self-end">Accéder</a>
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
        
<?php require_once _TEMPLATEPATH_ . '/employe/footer.php'; ?>