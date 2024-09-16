<?php 

use App\Tools\FileTools; ?>
      
      <!-- START : Back To Top -->
      <a class="btn btn-gamestore-outline shadow px-1" href="#" id="scrollTopButton">
      <i class="bi bi-chevron-up fs-4"></i>
    </a>
    <!-- END : Back To Top -->
  </main>
  <!-- END : Main -->

  <!-- START : Footer -->
  <footer class="container-fluid bg-white bg-opacity-75 footer-shadow">
    <div class="row row-cols-1 row-cols-md-3 justify-content-center justify-content-md-center align-items-center pt-4">
      <img class="mb-4 logo mx-auto my-md-auto" src="./assets/images/logo_small.svg" alt="Logo de l'entreprise Gamestore" width="50">
      <ul class="navbar-nav text-center">
        <li class="nav-item pb-1"><a href="index.php?controller=page&action=about" class="text-uppercase menu-link">Nos points de vente</a></li>
        <li class="nav-item pb-3"><a href="index.php?controller=page&action=buy" class="text-uppercase menu-link">Comment acheter</a></li>
        <li class="nav-item pb-1"><a href="index.php?controller=page&action=legal" class="text-uppercase menu-link">Mentions légales</a></li>
        <li class="nav-item pb-1"><a href="index.php?controller=page&action=cgu" class="text-uppercase menu-link">Cgu</a></li>
        <li class="nav-item pb-1"><a href="index.php?controller=page&action=private" class="text-uppercase menu-link">Vie privée</a></li>
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
  <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <?php 
    $scripts = FileTools::addScripts();
    foreach($scripts as $script) : ?>
      <script type="module" src="./assets/js/<?= $script ?>"></script>
    <?php endforeach; ?>
</body>

</html>