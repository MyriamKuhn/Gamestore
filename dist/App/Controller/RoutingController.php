<?php

namespace App\Controller;

use App\Tools\Security;

class RoutingController
{

  public function route(): void
  {
    try {
      if (isset($_GET['controller'])) {
        switch ($_GET['controller']) {
          case 'page':
            $controller = new PageController();
            $controller->route();
            break;
          case 'auth':
            $controller = new AuthController();
            $controller->route();
            break;
          case 'user':
            $controller = new UserController();
            $controller->route();
            break;
          case 'games':
            $controller = new GamesController();
            $controller->route();
            break;
          case 'datas':
            $controller = new DatasController();
            break;
          case 'dashboard':
            if (Security::isUser()) {
              $controller = new DashboardController();
              $controller->route();
            } else {
              throw new \Exception("Vous n'avez pas les droits pour accéder à cette page, veuillez vous connecter");
            }
            break;
          default:
            throw new \Exception("Le controleur n'existe pas");
            break;
        }
      } else {
        //Chargement la page d'accueil si pas de controleur dans l'url
        $controller = new PageController();
        $controller->home();
      }
    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage() . "(Erreur : " . $e->getCode() . ")"
      ]);
    }
  }

  protected function render(string $path, array $params = []): void
  {
    $filePath = _ROOTPATH_.'/templates/'.$path.'.php';

    try {
      if (!file_exists($filePath)) {
        throw new \Exception("Fichier non trouvé : ".$filePath);
      } else {
        // Extrait chaque ligne du tableau et crée des variables pour chacune
        extract($params);
        require_once $filePath;
      }
    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage() . "(Erreur : " . $e->getCode() . ")"
      ]);
    }
  }

}