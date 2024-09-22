<?php

namespace App\Controller;

class AuthController extends RoutingController
{

  public function route(): void
  {
    try {
      if (isset($_GET['action'])) {
        switch ($_GET['action']) {
          case 'login':
            $this->login();
            break;
          case 'logout':
            $this->logout();
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


  protected function login()
  {
    $this->render('auth/login');
  }

  protected function logout()
  {
    $this->render('auth/logout');
  }
  
}