<?php

namespace App\Controller;

use App\Tools\Security;

class OrderController extends RoutingController
{

  public function route(): void
  {
    try {
      if (isset($_GET['action'])) {
        switch ($_GET['action']) {
          case 'add':
            if (!isset($_GET['id'])) {
              throw new \Exception("Aucun identifiant de jeu spécifié");
            }
            $this->add();
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

  protected function add()
  {
    try {
      if (Security::isUser()) {
        // Récupération des données de l'URL
        $gameId = Security::secureInput($_GET['id']);
        $gamePlatformId = Security::secureInput($_GET['platform']);
        $gamePrice = Security::secureInput($_GET['price']);
        $gameLocation = Security::secureInput($_GET['location']);
        $userId = Security::getCurrentUserId();
        $quantity = 1;
        // Calcul du prix total
        $price_at_order = $gamePrice * $quantity;
        // Vérification des données du jeu
        if (!$gameId || !$gamePlatformId || !$gamePrice || !$gameLocation) {
          throw new \Exception("Données du jeu incorrectes");
        }
      
      
      } else {
        throw new \Exception("Vous devez être connecté pour ajouter un jeu à votre panier");
      }
    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage() . "(Erreur : " . $e->getCode() . ")"
      ]);
    }
  }

}