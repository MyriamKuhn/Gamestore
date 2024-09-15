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
            //charger controleur home
            $this->home();
            break;
          case 'about':
            //charger controleur about
            $this->about();
            break;
          case 'buy':
            //charger controleur buy
            $this->buy();
            break;
          case 'contact':
            //charger controleur contact
            $this->contact();
            break;
          case 'legal':
            //charger controleur legal
            $this->legal();
            break;
          case 'cgu':
            //charger controleur cgu
            $this->cgu();
            break;
          case 'private':
            //charger controleur private
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
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function home()
  {
    try {
      $gpRepository = new GamePlatformRepository();
      $lastGames = $gpRepository->getAllNewGames(5);
      $reducedGames = $gpRepository->getReducedGamesListShort(8);

      $this->render('page/home', [
        'lastGamesDatas' => $lastGames,
        'reducedGamesDatas' => $reducedGames
      ]);

    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function about()
  {
    try {
      $this->render('page/about');

    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function buy()
  {
    try {
      $this->render('page/buy');

    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage()
      ]);
    }
  }	

  protected function contact()
  {
    try {
      $this->render('page/contact');

    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage()
      ]);
    }
  }	

  protected function legal()
  {
    try {
      $this->render('page/legal');

    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage()
      ]);
    }
  }	

  protected function cgu()
  {
    try {
      $this->render('page/cgu');

    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage()
      ]);
    }
  }	

  protected function private()
  {
    try {
      $this->render('page/private');

    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage()
      ]);
    }
  }	

}
