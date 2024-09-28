<?php

namespace App\Controller;


class DashboardController extends RoutingController
{

  public function route(): void
  {
    try {
      if (isset($_GET['action'])) {
        switch ($_GET['action']) {
          case 'home':
            $this->home();
            break;
          case 'modify':
            $this->modify();
            break;
          case 'cart':
            $this->cart();
            break;
          case 'orders':
            $this->orders();
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
        'error' => _ERORR_MESSAGE_ . "(Erreur : " . $e->getCode() . ")"
      ]);
    }
  }

  protected function home()
  {
    $this->render('dashboard/home');
  }

  protected function modify()
  {
    $this->render('dashboard/modify');
  }

  protected function cart()
  {
    $this->render('dashboard/cart');
  }

  protected function orders()
  {
    $this->render('dashboard/orders');
  }
  
}