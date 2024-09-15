<?php require_once _TEMPLATEPATH_ . '/header.php'; ?>

<!-- START : Main -->
<main class="container my-4 main" id="hero">
  <section class="mt-2">
    <div id="data-container" data-games="<?= htmlspecialchars($reducedGames, ENT_QUOTES, 'UTF-8') ?>"></div>
    <div class="d-flex justify-content-between gamestore-title">
      <h2 class="text-uppercase">Toutes nos offres promotionnelles</h2>
    </div>
    <div class="mt-3 row row-cols-1 row-cols-lg-5 justify-content-center justify-content-lg-evenly gap-4" id="cards">
      <!-- Emplacement des cards pour afficher les jeux -->
    </div>
  </section>

  <script src="./assets/js/promoPage.js"></script>

  <?php require_once _TEMPLATEPATH_ . '/footer.php'; ?>