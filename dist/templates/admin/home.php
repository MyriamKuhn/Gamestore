<?php 

use App\Tools\Security;

require_once _TEMPLATEPATH_ . '/admin/header.php'; 

?>

        <section class="container mt-5 d-flex flex-column align-items-center mb-5">
          <img src="./assets/images/logo_big.svg" alt="Logo de l'entreprise Gamestore" class="my-5" style="width: 200px;">
          <p class="mt-3 fs-2 text-center">Bienvenue dans votre espace administrateur, <?= Security::getCurrentUserFullName() ?>.</p>
          <p>Vous pouvez gérer les utilisateurs, les employés, les commandes, la mise en ligne de nouveaux articles, les stocks de chaque Gamestore ainsi qu'analyser les ventes.</p>
          <div class="row my-5">
            <div class="col-md-6 mt-2">
              <div class="card shadow">
                <div class="card-body d-flex flex-column">
                  <h2 class="card-title text-uppercase"><i class="bi bi-key me-2"></i>Mot de passe</h2>
                  <p class="card-text">Modifiez le mot de passe de votre compte administrateur régulièrement.</p>
                  <a href="index.php?controller=admin&action=password" class="btn btn-gamestore-outline text-uppercase align-self-end">Accéder</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 mt-2">
              <div class="card shadow">
                <div class="card-body d-flex flex-column">
                  <h2 class="card-title text-uppercase"><i class="bi bi-receipt-cutoff me-2"></i>Commandes</h2>
                  <p class="card-text">Gérez, validez ou annulez les commandes de chaque Gamestore.</p>
                  <a href="index.php?controller=admin&action=orders" class="btn btn-gamestore-outline text-uppercase align-self-end">Accéder</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 mt-2">
              <div class="card shadow">
                <div class="card-body d-flex flex-column">
                  <h2 class="card-title text-uppercase"><i class="bi bi-shop me-2"></i>Magasin</h2>
                  <p class="card-text">Enregistrez un achat direct en magasin.</p>
                  <a href="index.php?controller=admin&action=buying" class="btn btn-gamestore-outline text-uppercase align-self-end">Accéder</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 mt-2">
              <div class="card shadow">
                <div class="card-body d-flex flex-column">
                  <h2 class="card-title text-uppercase"><i class="bi bi-person-lines-fill"></i>Employés</h2>
                  <p class="card-text">Gérez tous vos employés pour chaque magasin Gamestore.</p>
                  <a href="index.php?controller=admin&action=employes" class="btn btn-gamestore-outline text-uppercase align-self-end">Accéder</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 mt-2">
              <div class="card shadow">
                <div class="card-body d-flex flex-column">
                  <h2 class="card-title text-uppercase"><i class="bi bi-person-fill me-2"></i>Utilisateurs</h2>
                  <p class="card-text">Gérez tous les utilisateurs pour chaque magasin Gamestore.</p>
                  <a href="index.php?controller=admin&action=users" class="btn btn-gamestore-outline text-uppercase align-self-end">Accéder</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 mt-2">
              <div class="card shadow">
                <div class="card-body d-flex flex-column">
                  <h2 class="card-title text-uppercase"><i class="bi bi-graph-up"></i>Ventes</h2>
                  <p class="card-text">Consultez et analysez toutes les ventes de chaque Gamestore.</p>
                  <a href="index.php?controller=admin&action=sales" class="btn btn-gamestore-outline text-uppercase align-self-end">Accéder</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 mt-2">
              <div class="card shadow">
                <div class="card-body d-flex flex-column">
                  <h2 class="card-title text-uppercase"><i class="bi bi-clipboard2"></i>Articles</h2>
                  <p class="card-text">Gérez la mise en ligne d'articles et les stocks disponibles de chaque Gamestore.</p>
                  <a href="index.php?controller=admin&action=products" class="btn btn-gamestore-outline text-uppercase align-self-end">Accéder</a>
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
        
<?php require_once _TEMPLATEPATH_ . '/admin/footer.php'; ?>