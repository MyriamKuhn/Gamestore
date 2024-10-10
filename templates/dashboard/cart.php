<?php 

use App\Tools\Security;

$orderDate = new DateTime();

require_once _TEMPLATEPATH_ . '/dashboard/header.php'; 

?>

<section class="container my-5">
  <div class="d-flex justify-content-between gamestore-title mb-4">
    <h2 class="text-uppercase">Votre panier</h2>
  </div>
  <!-- START : Affichage du panier en tableau -->
  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th scope="col">Jeu</th>
          <th scope="col">Plateforme</th>
          <th scope="col">Quantité</th>
          <th scope="col">Prix (TTC)</th>
          <th scope="col">Total (TTC)</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <?php $totalPrice = 0;
        foreach ($cartContent as $content): 
          $contentTotal = Security::secureInput($content['quantity']) * Security::secureInput($content['price']);
          $totalPrice += $contentTotal; ?>
          <tr>
            <td><a href="/index.php?controller=games&action=show&id=<?=Security::secureInput($content['game_id']) ?>" class="text-link"><?= html_entity_decode(Security::secureInput($content['game_name'])) ?></a></td>
            <td><?= Security::secureInput($content['platform_name']) ?></td>
            <td>
              <form method="post">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="game_id" value="<?= Security::secureInput($content['game_id']) ?>">
                <input type="hidden" name="platform_id" value="<?= Security::secureInput($content['platform_id']) ?>">
                <input type="hidden" name="price_at_order" value="<?= Security::secureInput($content['price']) ?>">
                <input type="hidden" name="quantity" value="<?= Security::secureInput($content['quantity']) ?>">
                <?php if (Security::secureInput($content['quantity']) > 1) : ?> 
                <button type="submit" name="updateQuantity" value="decrease" class="btn btn-gamestore-outline me-2">
                  <i class="bi bi-dash-lg"></i>
                </button>
                <?php endif; ?>
                <?= Security::secureInput($content['quantity']) ?>
                <button type="submit" name="updateQuantity" value="increase" class="btn btn-gamestore-outline ms-2">
                  <i class="bi bi-plus-lg"></i>
                </button>
              </form>
            </td>
            <td><?= number_format($content['price'], 2) ?> €</td>
            <td><?= number_format($contentTotal, 2) ?> €</td>
            <td>
              <form method="post">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="game_id" value="<?= Security::secureInput($content['game_id']) ?>">
                <input type="hidden" name="platform_id" value="<?= Security::secureInput($content['platform_id']) ?>">
                <button type="submit" name="deleteGame" class="btn btn-gamestore">
                  <i class="bi bi-trash3-fill"></i>
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="4" class="text-end">Total du panier (TTC) :</th>
          <th><?= number_format($totalPrice, 2) ?> €</th>
          <th></th>
        </tr>
      </tfoot>
    </table>
  </div>
  <!-- END : Affichage du panier en tableau -->
  <div class="mb-5">
    <p>Après avoir validé votre panier, vous pourrez récupérer vos jeux dans votre magasin :</p>
    <?php switch ($_SESSION['user']['store_id']) :
      case 1: ?>
        <div class="text-center">
          <p class="fw-bold mb-0">Gamestore Nantes</p>
          <p class="mb-0">42 Rue des Joueurs, 44000 Nantes</p>
          <p>Horaires d'ouverture : Mardi - Samedi : 10h00 - 19h00</p>
        </div>
      <?php break;
      case 2: ?>
        <div class="text-center">
          <p class="fw-bold mb-0">Gamestore Lille</p>
          <p class="mb-0">15 Rue du Pixel, 59000 Lille</p>
          <p>Horaires d'ouverture : Mardi - Samedi : 10h00 - 19h00</p>
        </div>
      <?php break;
      case 3: ?>
        <div class="text-center">
          <p class="fw-bold mb-0">Gamestore Bordeaux</p>
          <p class="mb-0">23 Place du Geek, 33000 Bordeaux</p>
          <p>Horaires d'ouverture : Mardi - Samedi : 10h00 - 19h30</p>
        </div>
      <?php break;
      case 4: ?>
        <div class="text-center">
          <p class="fw-bold mb-0">Gamestore Paris</p>
          <p class="mb-0">12 Rue du Gamer, 75001 Paris</p>
          <p>Horaires d'ouverture : Mardi - Samedi : 10h00 - 19h30</p>
        </div>
      <?php break;
      case 5: ?>
        <div class="text-center">
          <p class="fw-bold mb-0">Gamestore Toulouse</p>
          <p class="mb-0">67 Avenue du Game, 31000 Toulouse</p>
          <p>Horaires d'ouverture : Mardi - Samedi : 10h00 - 19h30</p>
        </div>
      <?php break;
    endswitch; ?>
    <p class="mb-0">Vous pouvez définir votre magasin de retrait en vous rendant dans l'onglet <a href="/index.php?controller=dashboard&action=modify" class="text-link">Données personnelles</a> de votre espace client.</p>
    <p class="text-danger">Attention, en modifiant votre magasin Gamestore de retrait, vous supprimerez l'intégralité de votre panier !</p>
  </div>
  <div class="mb-2"> Définissez à présent une date de retrait de vos jeux. Elle doit être à moins de 7 jours à partir d'aujourd'hui et uniquement les jours d'ouverture de nos magasins.</div>
  <!-- START : Formulaire de choix de la date de retrait -->
  <form method="post">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
    <div class="form-floating mb-2">
      <select class="form-select" name="pickupDate" required>
        <?php
        // Générer les dates valides
        for ($i = 1; $i <= 7; $i++) {
          $pickupDate = clone $orderDate; // Cloner pour ne pas modifier l'original
          $pickupDate->modify("+$i days"); 
          // Vérifier que ce n'est pas un lundi ou un dimanche
          if ($pickupDate->format('N') != 1 && $pickupDate->format('N') != 7) {
            echo '<option value="' . $pickupDate->format('Y-m-d') . '">' . $pickupDate->format('d/m/Y') . '</option>';
          }
        } ?>
      </select>
      <label for="pickupDate">Choisissez une date de retrait :</label>
    </div>
    <div class="text-center">
      <button class="btn btn-gamestore text-uppercase" type="submit" name="validateCart">Valider le panier</button>
    </div>
  </form>
  <!-- END : Formulaire de choix de la date de retrait -->
  <div class="text-center mt-5">
    <a href="/index.php?controller=page&action=home" class="btn btn-gamestore text-uppercase">Voir les jeux</a>
  </div>

</section>



<?php require_once _TEMPLATEPATH_ . '/dashboard/footer.php'; ?>