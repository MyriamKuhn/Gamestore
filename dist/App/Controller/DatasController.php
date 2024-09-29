<?php

namespace App\Controller;

use App\Repository\GamePlatformRepository;
use App\Tools\Security;
use App\Repository\GameUserOrderRepository;

class DatasController extends RoutingController
{

  protected function __construct()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Lire les données JSON envoyées
      $csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'];
      $data = json_decode(file_get_contents('php://input'), true);
      if (hash_equals($_SESSION['csrf_token'], $csrfToken)) {
        // Vérifier l'action demandée
        if (isset($data['action'])) {
            // Appeler une fonction spécifique en fonction de l'action
            if ($data['action'] === 'getListDatas') {
                // Appeler la fonction getData()
                $this->getListDatas();
            } elseif ($data['action'] === 'getPromoDatas') {
                // Appeler la fonction getPromoDatas()
                $this->getPromoDatas();
            } elseif ($data['action'] === 'getGameDatas') {
                // Appeler la fonction getGameDatas()
                $this->getGameDatas($data['gameId']);
            } elseif ($data['action'] === 'addCart') {
                // Appeler la fonction getGameDatas()
                $this->getAddCart($data['gameId'], $data['platform'], $data['price'], $data['discountRate'], $data['oldPrice'], $data['location'], $data['userId']);
            } else {
                // Si l'action n'est pas reconnue
                $this->sendResponse(false, "Action inconnue", 400);
            }
        } else {
          $this->sendResponse(false, "Aucune action spécifiée", 400);
        }
      } else {
        $this->sendResponse(false, "Invalid CSRF token", 403);
      }
    }
  }

  protected function getPromoDatas()
  {
    $gpRepository = new GamePlatformRepository();
    $reducedGames = $gpRepository->getAllReducedGames();
    
    $this->sendResponse(true, $reducedGames, 200);
  }

  protected function getListDatas()
  {
    $gpRepository = new GamePlatformRepository();
    $gamesNantes = $gpRepository->getAllGamesByStore(1);
    $gamesLille = $gpRepository->getAllGamesByStore(2);
    $gamesBordeaux = $gpRepository->getAllGamesByStore(3);
    $gamesParis = $gpRepository->getAllGamesByStore(4);
    $gamesToulouse = $gpRepository->getAllGamesByStore(5);

    if (empty($gamesNantes) || empty($gamesLille) || empty($gamesBordeaux) || empty($gamesParis) || empty($gamesToulouse)) {
      $this->sendResponse(false, "Aucun jeu n'a été trouvé", 404);
    } else {
      $this->sendResponse(true, [
        'datasNantes' => $gamesNantes,
        'datasLille' => $gamesLille,
        'datasBordeaux' => $gamesBordeaux,
        'datasParis' => $gamesParis,
        'datasToulouse' => $gamesToulouse
      ], 200);
    }
  }

  protected function getGameDatas($gameId)
  {
    $gameId = (int) $gameId;
    $gpRepository = new GamePlatformRepository();
    $game = $gpRepository->getGameById($gameId);

    if (empty($game)) {
      $this->sendResponse(false, "Aucun jeu n'a été trouvé", 404);
    } else {
      $this->sendResponse(true, $game, 200);
    }
  }

  protected function getAddCart($gameId, $platform, $price, $discountRate, $oldPrice, $location, $userId)
  {
    // Récupération de toutes les données nécessaires
    $gameId = (int) $gameId;
    $platform = Security::secureInput($platform);
    $price = (float) $price;
    $discountRate = (float) $discountRate;
    $oldPrice = (float) $oldPrice;
    $location = (int) $location;
    $userId = (int) $userId;
    $quantity = 1;
    $orderId = $_SESSION['user']['cart_id'];

    // Faire la récupération de la plateforme ID à partir du nom

    // Calcul du prix total
    $price_at_order = $price * $quantity;
    // Vérification des données du jeu
    if ($location !== $_SESSION['user']['store_id']) {
      $this->sendResponse(false, "Vous ne pouvez pas ajouter un jeu d'une autre boutique", 400);
    }
    $gpRepository = new GamePlatformRepository();
    if ($discountRate > 0) {
      $game = $gpRepository->checkGameDatas($gameId, $platform, $oldPrice, $discountRate, $location);
    } else {
      $game = $gpRepository->checkGameDatas($gameId, $platform, $price, $discountRate, $location);
    }
    if ($game) {
      // Ajout du jeu dans le panier
      $guoRepository = new GameUserOrderRepository();
      $guoRepository->addGameInCart($gameId, $orderId, $quantity, $price_at_order);
      $this->sendResponse(true, "Le jeu a bien été ajouté au panier", 200);
    } else {
      $this->sendResponse(false, "Données du jeu incorrectes", 400);
    }
  }
    
  // Fonction pour envoyer une réponse JSON
  protected function sendResponse($success, $datas, $statusCode = 200) 
  {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => $success,
        'datas' => $datas
    ]);
  }
  
}