<?php

use App\Tools\NavigationTools;
use App\Tools\FileTools;

?>

<!-- START : Back To Top -->
<a class="btn btn-gamestore-outline shadow px-1" href="#" id="scrollTopButton">
          <i class="bi bi-chevron-up fs-4"></i>
        </a>
        <!-- END : Back To Top -->
      </main>
      <!-- END : Main -->
      <!-- START : Footer -->
      <footer class="container-fluid bg-white flex-shrink-0">
        <div class="row row-cols-1 row-cols-md-3 justify-content-center justify-content-md-center align-items-center pt-4">
          <a href="index.php?controller=page&action=home" class="mb-4 mx-auto my-md-auto logo">
            <img src="./assets/images/logo_small.svg" alt="Logo de l'entreprise Gamestore">
          </a>
          <ul class="navbar-nav text-center">
            <li class="nav-item pb-1"><a href="index.php?controller=page&action=legal" class="text-uppercase menu-link <?= NavigationTools::addActiveClass('page', 'legal') ?>">Mentions légales</a></li>
            <li class="nav-item pb-1"><a href="index.php?controller=page&action=cgu" class="text-uppercase menu-link <?= NavigationTools::addActiveClass('page', 'cgu') ?>">Cgu</a></li>
            <li class="nav-item pb-1"><a href="index.php?controller=page&action=private" class="text-uppercase menu-link <?= NavigationTools::addActiveClass('page', 'private') ?>">Vie privée</a></li>
          </ul>
          <div class="text-center mt-3 my-md-auto">
            <a href="https://www.facebook.com/" target="_blank" class="footer-logo pe-3"><i class="bi bi-facebook fs-1 footer-logo-img"></i></a>
            <a href="https://www.instagram.com/" target="_blank" class="footer-logo pe-3"><i class="bi bi-instagram fs-1 footer-logo-img"></i></a>
            <a href="https://www.twitter.com/" target="_blank" class="footer-logo"><i class="bi bi-twitter-x fs-1 footer-logo-img"></i></a>
          </div>
        </div>
        <p class="text-center copyright py-3 m-0">© 2024 Gamestore - All rights reserved</p>
      </footer>
      <!-- END : Footer -->
    </div>
    <!-- END : Div pour le contenu -->
  </div>
  <!-- END : Wrapper -->

  <!-- START : Scripts -->
  <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <?php 
    $scripts = FileTools::addScripts();
    foreach($scripts as $script) : ?>
      <script type="module" src="./assets/js/<?= $script ?>"></script>
    <?php endforeach; ?>
  <script src="./assets/js/scrollOnPages.js"></script>
</body>
</html>