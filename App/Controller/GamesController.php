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
            $this->list();
            break;
          case 'show':
            if (!isset($_GET['id'])) {
              throw new \Exception("Aucun identifiant de jeu spécifié");
            }
            $this->show($_GET['id']);
            break;
          case 'promo':
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
    
    try {
      $genreRepository = new GenreRepository();
      $allGenres = $genreRepository->getAllGenres();
      $platformRepository = new PlatformRepository();
      $allPlatforms = $platformRepository->getAllPlatforms();
      $gpRepository = new GamePlatformRepository();
      $prices = $gpRepository->getAllPrices();

      if (empty($allGenres) || empty($allPlatforms) || empty($prices)) {
        throw new \Exception("Aucune donnée n'a été trouvée");
      } else {
        $this->render('games/list', [
          'genres' => $allGenres,
          'platforms' => $allPlatforms,
          'prices' => $prices
        ]);
      }
    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage() 
      ]);
    }
  }

  protected function show(int $gameId)
  {
    try {
      $gpRepository = new GamePlatformRepository();
      $game = $gpRepository->getGameById($gameId);

      if (empty($game)) {
        throw new \Exception("Aucun jeu n'a été trouvé");
      } else {
        $this->render('games/show', [
          'game' => $game
        ]);
      }
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

      if (empty($allGenres) || empty($allPlatforms) || empty($allStores)) {
        throw new \Exception("Aucune donnée n'a été trouvée");
      } else {
        $this->render('games/promo', [
          'genres' => $allGenres,
          'platforms' => $allPlatforms,
          'stores' => $allStores
        ]);
      }
    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage() 
      ]);
    }
  }
      
}