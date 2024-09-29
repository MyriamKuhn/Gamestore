<?php

namespace App\Controller;

use App\Repository\GamePlatformRepository;

class PageController extends RoutingController
{
  public function route(): void
  {
    try {
      if (isset($_GET['action'])) {
        switch ($_GET['action']) {
          case 'home':
            $this->home();
            break;
          case 'about':
            $this->about();
            break;
          case 'buy':
            $this->buy();
            break;
          case 'contact':
            $this->contact();
            break;
          case 'legal':
            $this->legal();
            break;
          case 'cgu':
            $this->cgu();
            break;
          case 'private':
            $this->private();
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
        'error' => $e->getMessage() . "(Erreur : " . $e->getCode() . ")"
      ]);
    }
  }

  protected function home()
  {
    try {
      $gpRepository = new GamePlatformRepository();
      $lastGames = $gpRepository->getAllNewGames(5);
      $reducedGames = $gpRepository->getReducedGamesListShort(8);

      if (empty($lastGames) || empty($reducedGames)) {
        throw new \Exception("Aucun jeu n'a été trouvé");
      }

      $this->render('page/home', [
        'lastGamesDatas' => $lastGames,
        'reducedGamesDatas' => $reducedGames
      ]);

    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage() . "(Erreur : " . $e->getCode() . ")"
      ]);
    }
  }

  protected function about()
  {
    try {
      $this->render('page/about');

    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage() . "(Erreur : " . $e->getCode() . ")"
      ]);
    }
  }

  protected function buy()
  {
    try {
      $this->render('page/buy');

    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage() . "(Erreur : " . $e->getCode() . ")"
      ]);
    }
  }	

  protected function contact()
  {
    try {
      $this->render('page/contact');

    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage() . "(Erreur : " . $e->getCode() . ")"
      ]);
    }
  }	

  protected function legal()
  {
    try {
      $this->render('page/legal');

    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage() . "(Erreur : " . $e->getCode() . ")"
      ]);
    }
  }	

  protected function cgu()
  {
    try {
      $this->render('page/cgu');

    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage() . "(Erreur : " . $e->getCode() . ")"
      ]);
    }
  }	

  protected function private()
  {
    try {
      $this->render('page/private');

    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage() . "(Erreur : " . $e->getCode() . ")"
      ]);
    }
  }	

}
