<?php

namespace App\Controller;

use App\Repository\GamePlatformRepository;

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
    try {
      $gpRepository = new GamePlatformRepository();
      $gamesNantes = $gpRepository->getAllGamesByStore(1);
      $gamesLille = $gpRepository->getAllGamesByStore(2);
      $gamesBordeaux = $gpRepository->getAllGamesByStore(3);
      $gamesParis = $gpRepository->getAllGamesByStore(4);
      $gamesToulouse = $gpRepository->getAllGamesByStore(5);

      $gamesNantes = json_encode($gamesNantes);
      $gamesLille = json_encode($gamesLille);
      $gamesBordeaux = json_encode($gamesBordeaux);
      $gamesParis = json_encode($gamesParis);
      $gamesToulouse = json_encode($gamesToulouse);

      $this->render('games/list', [
        'gamesNantes' => $gamesNantes,
        'gamesLille' => $gamesLille,
        'gamesBordeaux' => $gamesBordeaux,
        'gamesParis' => $gamesParis,
        'gamesToulouse' => $gamesToulouse
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
      $gpRepository = new GamePlatformRepository();
      $reducedGames = $gpRepository->getAllReducedGames();

      $reducedGames = json_encode($reducedGames);

      $this->render('games/promo', [
        'reducedGames' => $reducedGames
      ]);

    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage()
      ]);
    }
  }
      
}