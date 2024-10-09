<?php

namespace App\Controller;

use App\Repository\GamePlatformRepository;
use App\Tools\Security;
use App\Repository\GameUserOrderRepository;
use App\Repository\PlatformRepository;
use App\Repository\SalesRepository;
use App\Repository\UserRepository;

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
            if ($data['action'] === 'getListDatasNantes') {
              $this->getListDatas(1);
            } else if ($data['action'] === 'getListDatasLille') {
              $this->getListDatas(2);
            } else if ($data['action'] === 'getListDatasBordeaux') {
              $this->getListDatas(3);
            } else if ($data['action'] === 'getListDatasParis') {
              $this->getListDatas(4);
            } else if ($data['action'] === 'getListDatasToulouse') {
              $this->getListDatas(5);
            } elseif ($data['action'] === 'getPromoDatas') {
                $this->getPromoDatas();
            } elseif ($data['action'] === 'getGameDatas') {
                $this->getGameDatas($data['gameId']);
            } elseif ($data['action'] === 'addCart') {
                $this->getAddCart($data['gameId'], $data['platform'], $data['price'], $data['discountRate'], $data['oldPrice'], $data['location'], $data['userId']);
            } elseif ($data['action'] === 'getCartContent') {
                $this->getCartContent();
            } elseif ($data['action'] === 'getSaleDatas') {
                $this->getSaleDatas();
            } elseif ($data['action'] === 'getSalesNantesDatas') {
              $this->getSalesDatas('Nantes');
            } elseif ($data['action'] === 'getSalesLilleDatas') {
              $this->getSalesDatas('Lille');
            } elseif ($data['action'] === 'getSalesBordeauxDatas') {
              $this->getSalesDatas('Bordeaux');
            } elseif ($data['action'] === 'getSalesParisDatas') {
              $this->getSalesDatas('Paris');
            } elseif ($data['action'] === 'getSalesToulouseDatas') {
              $this->getSalesDatas('Toulouse');
            } elseif ($data['action'] === 'getSalesAllDatas') {
              $this->getSalesDatas();
            } elseif ($data['action'] === 'getSalesGenreNantesDatas') {
              $this->getSalesGenreDatas('Nantes');
            } elseif ($data['action'] === 'getSalesGenreLilleDatas') {
              $this->getSalesGenreDatas('Lille');
            } elseif ($data['action'] === 'getSalesGenreBordeauxDatas') {
              $this->getSalesGenreDatas('Bordeaux');
            } elseif ($data['action'] === 'getSalesGenreParisDatas') {
              $this->getSalesGenreDatas('Paris');
            } elseif ($data['action'] === 'getSalesGenreToulouseDatas') {
              $this->getSalesGenreDatas('Toulouse');
            } elseif ($data['action'] === 'getSalesGenreAllDatas') {
              $this->getSalesGenreDatas();
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

  protected function getPromoDatas(): void
  {
    $gpRepository = new GamePlatformRepository();
    $reducedGames = $gpRepository->getAllReducedGames();
    
    $this->sendResponse(true, $reducedGames, 200);
  }

  protected function getListDatas(int $storeId): void
  {
    $gpRepository = new GamePlatformRepository();
    $games = $gpRepository->getAllGamesByStore($storeId);

    if (empty($games)) {
      $this->sendResponse(false, "Aucun jeu n'a été trouvé", 404);
    } else {
      $this->sendResponse(true, $games, 200);
    }
  }

  protected function getGameDatas(int $gameId): void
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

  protected function getAddCart($gameId, $platform, $price, $discountRate, $oldPrice, $location, $userId): void
  {
    // Vérification de la connexion de l'utilisateur
    $userRepository = new UserRepository();
    $user = $userRepository->getUserById($userId);
    if (!$user) {
      $this->sendResponse(false, "Utilisateur inconnu", 400);
      exit;
    }
    if ($user->getIs_blocked() === 1) {
      $this->sendResponse(false, "Votre compte est bloqué, veuillez contacter un administrateur", 403);
      exit;
    }
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

    $platformRepository = new PlatformRepository();
    $platformId = $platformRepository->getPlatformIdByName($platform);
    if ($platformId === 0) {
      $this->sendResponse(false, "Plateforme inconnue", 400);
      exit;
    }
    // Calcul du prix total
    $price_at_order = $price * $quantity;
    // Vérification des données du jeu
    if ($location !== $_SESSION['user']['store_id']) {
      $this->sendResponse(false, "Vous ne pouvez pas ajouter un jeu d'une autre boutique", 400);
      exit;
    }
    $gpRepository = new GamePlatformRepository();
    if ($discountRate > 0) {
      $game = $gpRepository->checkGameDatas($gameId, $platformId, $oldPrice, $discountRate, $location);
    } else {
      $game = $gpRepository->checkGameDatas($gameId, $platformId, $price, $discountRate, $location);
    }
    if ($game) {
      // Ajout du jeu dans le panier
      $guoRepository = new GameUserOrderRepository();
      $isAdded = $guoRepository->addGameInCart($gameId, $platformId, $orderId, $quantity, $price_at_order, 'add');
      if (!$isAdded) {
        $this->sendResponse(false, "Erreur lors de l'ajout du jeu dans le panier", 500);
        exit;
      } else {
        $this->sendResponse(true, $orderId, 200);
        exit;
      }
    } else {
      $this->sendResponse(false, "Données du jeu incorrectes", 400);
      exit;
    }
  }

  protected function getCartContent(): void
  {
    if (empty($_SESSION['user']) || empty($_SESSION['user']['cart_id'])) {
      $this->sendResponse(false, "Aucun panier n'a été trouvé", 404);
      exit;
    }
    $cartId = $_SESSION['user']['cart_id'];
    $guoRepository = new GameUserOrderRepository();
    $cartContent = $guoRepository->findCartContent($cartId);

    if (empty($cartContent)) {
      $this->sendResponse(false, "Le panier est vide", 404);
      exit;
    } else {
      $this->sendResponse(true, $cartContent, 200);
    }
  }

  protected function getSaleDatas(): void
  {
    if (Security::isEmploye()) {
      $salesRepository = new SalesRepository();
      $sales = $salesRepository->getAllSalesByDate(Security::getEmployeStore());
      if (empty($sales)) {
        $this->sendResponse(false, "Aucune vente n'a été trouvée", 404);
        exit;
      } else {
        $this->sendResponse(true, $sales, 200);
      }
    } else {
      $this->sendResponse(false, "Vous n'êtes pas autorisé à accéder à cette ressource", 403);
      exit;
    }
  }

  protected function getSalesDatas(string|null $store = null): void
  {
    if (Security::isAdmin()) {
      $salesRepository = new SalesRepository();
      $sales = $salesRepository->getAllSalesByDate($store);
      if (empty($sales)) {
        $this->sendResponse(false, "Aucune vente n'a été trouvée", 404);
        exit;
      } else {
        $this->sendResponse(true, $sales, 200);
      }
    } else {
      $this->sendResponse(false, "Vous n'êtes pas autorisé à accéder à cette ressource", 403);
      exit;
    }
  }

  protected function getSalesGenreDatas(string|null $store = null): void
  {
    if (Security::isAdmin()) {
      $salesRepository = new SalesRepository();
      $sales = $salesRepository->getAllSalesByGenre($store);
      if (empty($sales)) {
        $this->sendResponse(false, "Aucune vente n'a été trouvée", 404);
        exit;
      } else {
        $this->sendResponse(true, $sales, 200);
      }
    } else {
      $this->sendResponse(false, "Vous n'êtes pas autorisé à accéder à cette ressource", 403);
      exit;
    }
  }
    
  // Fonction pour envoyer une réponse JSON
  protected function sendResponse(bool $success, string|array $datas, int $statusCode = 200): void
  {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => $success,
        'datas' => $datas
    ]);
  }
  
}