<?php

namespace App\Controller;

use App\Repository\GamesRepository;
use App\Repository\GamePlatformRepository;
use App\Repository\ImageRepository;

class PageController extends RoutingController
{
  public function route(): void
  {
    try {
      if (isset($_GET['action'])) {
        switch ($_GET['action']) {
          case 'home':
            //charger controleur home
            $this->home();
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

  protected function home()
  {
    $lastGamesDatas = [];

    try {
      $gamesRepository = new GamesRepository();
      $lastGames = $gamesRepository->getGames(5);

      $gamePlatformRepository = new GamePlatformRepository();

      $gameImageRepository = new ImageRepository();

      foreach ($lastGames as $game) {
        $gamePlatforms = $gamePlatformRepository->getAllPlatformsByGameId($game['id']);
        
        $gameImages = $gameImageRepository->getImagesByGameId($game['id']);
        $spotlight = 'spotlight';
        $spotlightImage = array_filter($gameImages, function ($image) use ($spotlight) {
          return strpos($image['name'], $spotlight) !== false;
        });

        $lastGamesDatas[] = [
          'id' => $game['id'],
          'name' => $game['game_name'],
          'description' => $game['description'],
          'pegi' => $game['pegi_name'],
          'gamePlatforms' => $gamePlatforms,
          'gameImages' => $spotlightImage[0]['name']
        ];
      }

      $this->render('page/home', [
        'lastGamesDatas' => $lastGamesDatas
      ]);

    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage()
      ]);
    }
  }

}
