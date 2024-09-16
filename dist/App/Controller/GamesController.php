<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use App\Repository\PlatformRepository;
use App\Repository\GamePlatformRepository;
use App\Repository\StoreRepository;

class GamesController extends RoutingController
{

  public function route(): void
  {
    try {
      if (isset($_GET['action'])) {
        switch ($_GET['action']) {
          case 'list':
            //charger controleur 
            $this->list();
            break;
          case 'show':
            //charger controleur 
            $this->show();
            break;
          case 'promo':
            //charger controleur 
            $this->promo();
            break;
          default:
            throw new \Exception("Cette action n'existe pas : " . $_GET['action']);
            break;
        }
      } else {
        throw new \Exception("Aucune action détectée");
      }
    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function list()
  {
    $genreRepository = new GenreRepository();
    $allGenres = $genreRepository->getAllGenres();
    $platformRepository = new PlatformRepository();
    $allPlatforms = $platformRepository->getAllPlatforms();
    $gpRepository = new GamePlatformRepository();
    $prices = $gpRepository->getAllPrices();

    try {
        $this->render('games/list', [
          'genres' => $allGenres,
          'platforms' => $allPlatforms,
          'prices' => $prices
        ]);

    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function show()
  {
    try {
      $this->render('games/show');

    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function promo()
  {
    try {
      $genreRepository = new GenreRepository();
      $allGenres = $genreRepository->getAllGenres();
      $platformRepository = new PlatformRepository();
      $allPlatforms = $platformRepository->getAllPlatforms();
      $storeRepository = new StoreRepository();
      $allStores = $storeRepository->getAllStores();

      $this->render('games/promo', [
        'genres' => $allGenres,
        'platforms' => $allPlatforms,
        'stores' => $allStores
      ]);

    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage()
      ]);
    }
  }
      
}