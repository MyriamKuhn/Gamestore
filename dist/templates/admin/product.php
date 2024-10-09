<?php

use App\Tools\FileTools;
use App\Tools\Security;

require_once _TEMPLATEPATH_ . '/admin/header.php';

?>

<section class="container my-5">
  <div class="d-flex justify-content-between gamestore-title">
    <h2 class="text-uppercase"><?= Security::secureInput($isModify) ? 'Modifiez le jeu' : 'Ajoutez un jeu' ?></h2>
  </div>
  <!-- Affichage des erreurs -->
  <div class="alert alert-danger py-5 my-5 <?= empty($errors) ? 'visually-hidden' : '' ?>" id="error-message">
    <?php if (!empty($errors)) {
      foreach ($errors as $error) {
        echo $error . '<br>';
      }
    } ?>
  </div>
  <!-- START : Formulaire -->
      <form method="post" id="game-form" class="needs-validation mt-5" enctype="multipart/form-data">
        <!-- Inclusion du token CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
        <input type="hidden" name="game_id" value="<?= $isModify ? Security::secureInput($game['game_id']) : '0' ?>">
        <!-- Nom du jeu -->
        <div class="form-floating mb-4">
          <input type="text" class="form-control <?=(isset($errors['game_name']) ? 'is-invalid': '') ?>" id="game_name" name="game_name" minlength="3" maxlength="100" <?= $isModify ? 'value="' . html_entity_decode(Security::secureInput($game['game_name'])) . '"' : ''  ?> required>
          <label for="game_name">Nom</label>
          <div class="invalid-feedback">
            Entrez le nom du jeu.
          </div>
        </div>
        <!-- Description du jeu -->
        <div class="form-floating mb-4">
          <textarea class="form-control <?=(isset($errors['game_description']) ? 'is-invalid': '') ?>" id="game_description" name="game_description" style="height: 200px" required><?= $isModify ? html_entity_decode(Security::secureInput($game['game_description'])) : ''  ?></textarea>
          <label for="game_description">Description</label>
          <div class="invalid-feedback">
            Entrez une description.
          </div>
        </div>
        <!-- PEGI -->
        <div class="form-floating">
          <select name="pegi_id" id="pegi-select" class="form-select <?=(isset($errors['game_pegi']) ? 'is-invalid': '') ?>" required>
            <?php foreach ($pegis as $pegi) : ?>
              <option value="<?= Security::secureInput($pegi['id']) ?>" data-image="<?= _ASSETS_IMAGES_FOLDER_ . '/pegi/' . Security::secureInput($pegi['name']) . '.jpg' ?>" <?= $isModify && Security::secureInput($game['pegi_name']) == Security::secureInput($pegi['name']) ? 'selected' : '' ?>></option>
            <?php endforeach; ?>
          </select>
          <label for="pegi-select">PEGI</label>
          <div class="invalid-feedback">
            Sélectionnez un PEGI.
          </div>
        </div>
        <!-- Genres -->
        <div class="form-floating my-4">
          <select name="genres_id[]" id="genres-select" class="form-select <?=(isset($errors['game_genres']) ? 'is-invalid': '') ?>" multiple="multiple" required>
            <?php foreach ($genres as $genre) : ?>
              <option value="<?= Security::secureInput($genre['id']) ?>" <?= $isModify && str_contains($game['genres'], $genre['name']) ? 'selected' : '' ?>><?= Security::secureInput($genre['name']) ?></option>
            <?php endforeach; ?>
          </select>
          <label for="genres-select">Genres</label>
          <div class="invalid-feedback">
            Sélectionnez au minimum un genre.
          </div>
        </div>
        <!-- Images -->
        <?php if ($isModify) : 
          $spotlight = FileTools::getImagesAsCategory('spotlight', $game['images']);
          $presentation = FileTools::getImagesAsCategory('presentation', $game['images']);
          $carousel = FileTools::getImagesAsCategory('carousel', $game['images']);
        ?>
          <h4 class="text-uppercase">Image spotlight (maximum 1 image)</h4>
          <div class="d-flex flex-column align-items-center mb-2">
            <img src="<?= _GAMES_IMAGES_FOLDER_ . reset($spotlight) ?>" class="img-thumbnail" alt="Image spotlight de <?= html_entity_decode(Security::secureInput($game['game_name'])) ?>" width="200">
            <div class="d-flex justify-content-center">
              <input type="checkbox" name="delete-spotlight" id="delete-spotlight" class="me-1">
              <label for="delete-spotlight">Supprimer l'image</label>
            </div>
            <input type="hidden" name="delete-spotlight-image" value="<?= reset($spotlight) ?>">
          </div>
          <div class="form-floating mb-4 d-none" id="game_spotlight_input">
            <input type="file" class="form-control" id="game_spotlight" name="game_spotlight">
            <label for="game_spotlight">Image spotlight</label>
            <div class="invalid-feedback">
              Ajoutez une image de format .jpg, .jpeg, .png, .webp ou .svg
            </div>
          </div>
          <h4 class="text-uppercase">Image de présentation (maximum 1 image)</h4>
          <div class="d-flex flex-column align-items-center mb-2">
            <img src="<?= _GAMES_IMAGES_FOLDER_ . reset($presentation) ?>" class="img-thumbnail" alt="Image de présentation de <?= html_entity_decode(Security::secureInput($game['game_name'])) ?>" width="200">
            <div class="d-flex justify-content-center">
              <input type="checkbox" name="delete-presentation" id="delete-presentation" class="me-1">
              <label for="delete-presentation">Supprimer l'image</label>
            </div>
            <input type="hidden" name="delete-presentation-image" value="<?= reset($presentation) ?>">
          </div>
          <div class="form-floating mb-4 d-none" id="game_presentation_input">
            <input type="file" class="form-control" id="game_presentation" name="game_presentation">
            <label for="game_presentation">Image de présentation</label>
            <div class="invalid-feedback">
              Ajoutez une image de format .jpg, .jpeg, .png, .webp ou .svg
            </div>
          </div>
          <h4 class="text-uppercase">Images du carousel (minimum 2 images)</h4>
          <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-5 justify-content-center">
            <?php for ($i = 0 ; $i < (count($carousel)) ; $i++) : ?>
              <div class="d-flex flex-column align-items-center mb-2">
                <img src="<?= _GAMES_IMAGES_FOLDER_ . $carousel[$i] ?>" alt="Image du carousel de <?= html_entity_decode(Security::secureInput($game['game_name'])) ?>" class="img-thumbnail" width="200">
                <div class="d-flex justify-content-center">
                  <input type="checkbox" name="delete-carousel-<?= $i ?>" id="delete-carousel-<?= $i ?>" class="me-1 carousels-deletes">
                  <label for="delete-carousel-<?= $i ?>">Supprimer l'image</label>
                </div>
                <input type="hidden" name="delete-carousel-image-<?= $i ?>" value="<?= $carousel[$i] ?>">
              </div>
            <?php endfor; ?>
          </div>
          <div class="form-floating mb-4">
            <input type="file" class="form-control" id="game_carousel" name="game_carousel[]" multiple>
            <label for="game_carousel">Images de carousel</label>
            <div class="invalid-feedback">
              Il vous faut au moins deux images de format .jpg, .jpeg, .png, .webp ou .svg
            </div>
          </div>
        <?php else : ?>
          <h4 class="text-uppercase">Image spotlight (maximum 1 image)</h4>
          <div class="form-floating mb-4">
            <input type="file" class="form-control" id="game_spotlight" name="game_spotlight" required>
            <label for="game_image">Image spotlight</label>
            <div class="invalid-feedback">
              Ajoutez une image de format .jpg, .jpeg, .png, .webp ou .svg
            </div>
          </div>
          <h4 class="text-uppercase">Image de présentation (maximum 1 image)</h4>
          <div class="form-floating mb-4">
            <input type="file" class="form-control" id="game_presentation" name="game_presentation" required>
            <label for="game_presentation">Image de présentation</label>
            <div class="invalid-feedback">
              Ajoutez une image de format .jpg, .jpeg, .png, .webp ou .svg
            </div>
          </div>
          <h4 class="text-uppercase">Images du carousel (minimum 2 images)</h4>
          <div class="form-floating mb-4">
            <input type="file" class="form-control" id="game_carousel" name="game_carousel[]" multiple required>
            <label for="game_carousel">Images de carousel</label>
            <div class="invalid-feedback">
              Ajoutez au moins deux images de format .jpg, .jpeg, .png, .webp ou .svg
            </div>
          </div>
        <?php endif; ?>    
        <!-- Données par magasin et par plateformes -->
        <?php foreach ($stores as $store) : ?>
          <h4 class="text-uppercase fs-6">Gamestore <?= $store['location'] ?></h4>
          <div class="table-responsive mb-4">
            <table class="table table-striped table-hover" id="gameTable">
              <thead>
                <tr>
                  <th scope="col" class="all">Platforme</th>
                  <th scope="col" class="all">Prix en €</th>
                  <th scope="col" class="tablet desktop">Badge</th>
                  <th scope="col" class="tablet desktop">Réduction en %</th>
                  <th scope="col" class="tablet desktop">Stock</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($isModify) : ?>
                  <?php foreach ($platforms as $platform) : 
                    $filteredGame = null;
                    foreach ($game['game_prices'] as $price) {
                      if ($price['location'] == $store['location'] && $price['platform'] == $platform['name']) {
                        $filteredGame = $price;
                        break;
                      }
                    }
                    if ($filteredGame == null) {
                      $filteredGame = ['price' => 0, 'is_new' => 0, 'is_reduced' => 0, 'discount_rate' => 0, 'stock' => 0];
                    }
                    ?>
                    <tr>
                      <td><?= Security::secureInput($platform['name']) ?></td>
                      <td><input type="number" name="<?= Security::secureInput($store['id']) . '-' . Security::secureInput($platform['id']) ?>-price" class="form-control" min="0" step="0.01" value="<?= Security::secureInput($filteredGame['price']) ?>"></td>
                      <td>
                        <input type="checkbox" name="<?= Security::secureInput($store['id']) . '-' . Security::secureInput($platform['id']) ?>-new" id="<?= Security::secureInput($store['id']) . '-' . Security::secureInput($platform['id']) ?>-new" class="me-1" <?= Security::secureInput($filteredGame['is_new']) == 1 ? 'checked' : '' ?>>
                        <label for="<?= Security::secureInput($store['id']) . '-' . Security::secureInput($platform['id']) ?>-new" class="me-3">Nouveauté</label>
                        <input type="checkbox" name="<?= Security::secureInput($store['id']) . '-' . Security::secureInput($platform['id']) ?>-reduced" id="<?= Security::secureInput($store['id']) . '-' . Security::secureInput($platform['id']) ?>-reduced" class="me-1" <?= Security::secureInput($filteredGame['is_reduced']) == 1 ? 'checked' : '' ?>>
                        <label for="<?= Security::secureInput($store['id']) . '-' . Security::secureInput($platform['id']) ?>-reduced">Promo</label>
                      </td>
                      <td><input type="number" name="<?= Security::secureInput($store['id']) . '-' . Security::secureInput($platform['id']) ?>-discount" class="form-control" min="0" value="<?= Security::secureInput($filteredGame['discount_rate']) * 100 ?>"></td>
                      <td><input type="number" name="<?= Security::secureInput($store['id']) . '-' . Security::secureInput($platform['id']) ?>-stock" class="form-control" min="0" value="<?= Security::secureInput($filteredGame['stock']) ?>"></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else : ?>
                  <?php foreach ($platforms as $platform) : ?>
                    <tr>
                      <td><?= Security::secureInput($platform['name']) ?></td>
                      <td><input type="number" name="<?= Security::secureInput($store['id']) . '-' . Security::secureInput($platform['id']) ?>-price" class="form-control" min="0" value="0"></td>
                      <td>
                        <input type="checkbox" name="<?= Security::secureInput($store['id']) . '-' . Security::secureInput($platform['id']) ?>-new" id="<?= Security::secureInput($store['id']) . '-' . Security::secureInput($platform['id']) ?>-new" class="me-1">
                        <label for="<?= Security::secureInput($store['id']) . '-' . Security::secureInput($platform['id']) ?>-new" class="me-3">Nouveauté</label>
                        <input type="checkbox" name="<?= Security::secureInput($store['id']) . '-' . Security::secureInput($platform['id']) ?>-reduced" id="<?= Security::secureInput($store['id']) . '-' . Security::secureInput($platform['id']) ?>-reduced" class="me-1">
                        <label for="<?= Security::secureInput($store['id']) . '-' . Security::secureInput($platform['id']) ?>-reduced">Promo</label>
                      </td>
                      <td><input type="number" name="<?= Security::secureInput($store['id']) . '-' . Security::secureInput($platform['id']) ?>-discount" class="form-control" min="0"></td>
                      <td><input type="number" name="<?= Security::secureInput($store['id']) . '-' . Security::secureInput($platform['id']) ?>-stock" class="form-control" min="0"></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        <?php endforeach; ?>
        <div class="text-center mt-3">
          <input type="submit" class="btn btn-gamestore text-uppercase" name="<?= $isModify ? 'modifyGame' : 'addGame'  ?>" value="<?= $isModify ? 'Modifier' : 'Ajouter'  ?>">
        </div>
      </form>
      <div class="text-center my-5">
        <a href="index.php?controller=admin&action=products" class="btn btn-gamestore text-uppercase">Retour vers la page des articles</a>
      </div>
      <!-- END : Formulaire d'inscription -->
  </section>

  <?php require_once _TEMPLATEPATH_ . '/admin/footer.php'; ?>