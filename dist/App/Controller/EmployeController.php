<?php

namespace App\Controller;

use App\Tools\Security;
use App\Tools\UserValidator;
use App\Repository\UserRepository;
use App\Repository\UserOrderRepository;
use App\Repository\SalesRepository;
use App\Model\Sale;
use DateTime;
use App\Repository\GameGenreRepository;
use App\Repository\GamePlatformRepository;
use App\Repository\PlatformRepository;
use App\Repository\GameUserOrderRepository;

class EmployeController extends RoutingController
{

  public function route(): void
  {
    try {
      if (isset($_GET['action'])) {
        switch ($_GET['action']) {
          case 'home':
            $this->home();
            break;
          case 'orders':
            $this->orders();
            break;
          case 'sales':
            $this->sales();
            break;
          case 'password':
            $this->password();
            break;
          case 'buying':
            $this->buying();
            break;
          case 'order':
            $this->order();
            break;
          case 'details':
            $this->details();
            break;
          default:
            throw new \Exception("Cette action n'existe pas : " . $_GET['action']);
            break;
          }
      } else {
        throw new \Exception("Aucune action détectée");
      }
    } catch (\Exception $e) {
      $this->render('employe/error', [
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function home(): void
  {
    try {
      if (Security::isEmploye()) {
        $this->render('employe/home');
      } else {
        throw new \Exception("Vous n'avez pas les droits pour accéder à cette page, veuillez vous connecter");
      }
    } catch (\Exception $e) {
      $this->render('employe/error', [
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function orders(): void
  {
    try {
      if (Security::isEmploye()) {
        // Si l'employé remet une commande au client, on change le statut de la commande à "Livrée"
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['validateOrder'])) {
          // Vérification du token CSRF
          Security::checkCSRF($_POST['csrf_token']);
          // Récupération des données du formulaire et sécurisation
          $orderId = Security::secureInput($_POST['order_id']);
          $orderStatus = Security::secureInput($_POST['order_status']);
          $userOrderRepository = new UserOrderRepository();
          $order = $userOrderRepository->validateOrderByEmployee($orderId, 'Livrée');
          if (!$order) {
            throw new \Exception("Erreur lors de la mise à jour du statut de la commande.");
          }
          // Sécurité uniquement si la commande est validée
          if ($orderStatus === 'Validée') {
            // Recherche des données de la commande
            $order = $userOrderRepository->findOrderById($orderId);
            if (!$order) {
              throw new \Exception("La commande n'existe pas.");
            }
            foreach ($order['games'] as $game) {
              //Pour chaque jeu de la commande, récupérer son genre
              $gameGenreRepository = new GameGenreRepository();
              $gameGenres = $gameGenreRepository->findAllGenresByGame($game['game_id']);
              // Création d'un objet Sale pour chaque jeu de la commande
              $sale = new Sale();
              $sale->setId($game['game_id']);
              $sale->setName($game['name']);
              $sale->setGenre($gameGenres);
              $sale->setPlatform($game['platform']);
              $sale->setStore($order['store_location']);
              $sale->setPrice($game['price']);
              $sale->setQuantity($game['quantity']);
              $sale->setDate(new DateTime());
              $sale->setOrderId($orderId);
              // Enregistrement des ventes en base de données
              $salesRepository = new SalesRepository();
              $salesRepository->setOneSale($sale);
              // Mise à jour du stock des jeux
              $gamePlatformRepository = new GamePlatformRepository();
              $isStockUpdated = $gamePlatformRepository->updateGameStock($game['game_id'], $game['platform_id'], $order['store_id'], $game['quantity'], 'remove');
              if (!$isStockUpdated) {
                throw new \Exception("Erreur lors de la mise à jour du stock du jeu.");
              }
            }
            header('Location: /index.php?controller=employe&action=orders');
            exit;
          } else {
            throw new \Exception("Vous ne pouvez pas modifier le statut de cette commande.");
          }
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancelOrder'])) {
          // Vérification du token CSRF
          Security::checkCSRF($_POST['csrf_token']);
          // Récupération des données du formulaire et sécurisation
          $orderId = Security::secureInput($_POST['order_id']);
          $orderStatus = Security::secureInput($_POST['order_status']);
          // Sécurité uniquement si la commande est validée
          if ($orderStatus === 'Validée') {
            $userOrderRepository = new UserOrderRepository();
            $order = $userOrderRepository->validateOrderByEmployee($orderId, 'Annulée');
            if (!$order) {
              throw new \Exception("Erreur lors de la mise à jour du statut de la commande.");
            }
            header('Location: /index.php?controller=employe&action=orders');
            exit;
          } else {
            throw new \Exception("Vous ne pouvez pas modifier le statut de cette commande.");
          }
        } else {
          // Comportement par défaut : affichage de la liste des commandes
          $storeId = $_SESSION['user']['store_id'];
          $userOrderRepository = new UserOrderRepository();
          $orders = $userOrderRepository->findAllOrdersByStore($storeId);
          $this->render('employe/orders', [
            'orders' => $orders
          ]);
        }
      } else {
        throw new \Exception("Vous n'avez pas les droits pour accéder à cette page, veuillez vous connecter");
      }
    } catch (\Exception $e) {
      $this->render('employe/error', [
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function sales(): void
  {
    try {
      if (Security::isEmploye()) {
        $this->render('employe/sales');
      } else {
        throw new \Exception("Vous n'avez pas les droits pour accéder à cette page, veuillez vous connecter");
      }
    } catch (\Exception $e) {
      $this->render('employe/error', [
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function password(): void
  {
    try {
      if (Security::isEmploye()) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifyPassword'])) {
          // Vérification du token CSRF
          Security::checkCSRF($_POST['csrf_token']);
          // Récupération des données du formulaire et sécurisation
          $oldPassword = Security::secureInput($_POST['passwordOld']);
          $newPassword = Security::secureInput($_POST['passwordNew']);
          $userId = Security::getCurrentUserId();
          // Vérification des données
          $errors = [];
          UserValidator::validatePassword($oldPassword) ?: $errors['old_password'] = 'Le champ mot de passe actuel n\'est pas valide';
          UserValidator::validatePassword($newPassword) ?: $errors['new_password'] = 'Le champ nouveau mot de passe n\'est pas valide';
          if ($userId === false) {
            $this->render('employe/error', [
              'error' => 'Veuillez vous connecter pour accéder à cette page.'
            ]);
          }
          $userRepository = new UserRepository();
          $user = $userRepository->getUserById($userId);
          if ($user && Security::verifyPassword($oldPassword, $user->getPassword())) {
            // Si aucune erreur, modification des données
            if (empty($errors)) {
              $user = $userRepository->updateUserPassword($userId, $newPassword);
              // Si mise en place en base de données réussie
              if ($user) {
                $this->render('employe/password', [
                  'user' => $user,
                  'success' => 'Votre mot de passe a bien été modifié.'
                ]);
              } else {
                $this->render('employe/error', [
                  'error' => 'Erreur lors de la modification de votre mot de passe.'
                ]);
              }
            } else {
              $userRepository = new UserRepository();
              $user = $userRepository->getUserById($userId);
              if (!$user) {
                throw new \Exception("Erreur lors de la récupération de vos données personnelles.");
              } else {
                $this->render('employe/password', [
                  'user' => $user,
                  'errors' => $errors
                ]);
              }
            }
          } else {
            $errors['old_password'] = 'Le mot de passe actuel est incorrect.';
            $userRepository = new UserRepository();
            $user = $userRepository->getUserById($userId);
            if (!$user) {
              throw new \Exception("Erreur lors de la récupération de vos données personnelles.");
            } else {
              $this->render('employe/password', [
                'user' => $user,
                'errors' => $errors
              ]);
            }
          }
        } else {
          // Comportement par défaut : affichage du formulaire de modification des données personnelles
          if (Security::getCurrentUserId()) {
            $userId = Security::getCurrentUserId();
            $userRepository = new UserRepository();
            $user = $userRepository->getUserById($userId);
            if (!$user) {
              throw new \Exception("Erreur lors de la récupération de vos données personnelles.");
            } else {
              $this->render('employe/password', [
                'user' => $user
              ]);
            }
          } else {
            $this->render('employe/error', [
              'error' => 'Veuillez vous connecter pour accéder à cette page.'
            ]);
          }
        }
      } else {
        throw new \Exception("Vous n'avez pas les droits pour accéder à cette page, veuillez vous connecter");
      }
    } catch (\Exception $e) {
      $this->render('employe/error', [
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function buying(): void
  {
    try {
      // Si validation d'une vente
      if (Security::isEmploye()) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orderStore'])) {
          // Vérification du token CSRF
          Security::checkCSRF($_POST['csrf_token']);
          // Récupération des données du formulaire et sécurisation
          $userId = Security::secureInput($_POST['user_id']);
          if ($userId == 0) {
            $userId = $_SESSION['user']['id'];
          }
          // Vérification de l'utilisateur
          $userRepository = new UserRepository();
          $user = $userRepository->getUserById($userId);
          if (!$user) {
            throw new \Exception("L'utilisateur n'existe pas.");
          }
          if ($user->getFk_store_id() != $_SESSION['user']['store_id']) {
            throw new \Exception("L'utilisateur n'est pas rattaché à votre magasin.");
          }
          $gameId = Security::secureInput($_POST['gameId']);
          $platformId = Security::secureInput($_POST['platformId']);
          $quantity = Security::secureInput($_POST['quantity']);
          $price = Security::secureInput($_POST['price']);
          $discount = Security::secureInput($_POST['discount']);
          // Vérification des données du jeu
          $gamePlatformRepository = new GamePlatformRepository();
          $isChecked = $gamePlatformRepository->checkGameDatas($gameId, $platformId, $price, $discount, $_SESSION['user']['store_id']);
          if (!$isChecked) {
            throw new \Exception("Les données du jeu ne sont pas correctes, veuillez rafraichir la page.");
          }
          // Vérification si le jeu est encore en stock
          $stock = $gamePlatformRepository->checkGameStock($gameId, $platformId, $_SESSION['user']['store_id']);
          if ($stock < $quantity) {
            throw new \Exception("Veuillez vérifier le stock du jeu.");
          }
          // Création d'une commande magasin vide
          $userOrderRepository = new UserOrderRepository();
          $orderId = $userOrderRepository->createEmptyOrder($userId, $_SESSION['user']['store_id']);
          if (!$orderId) {
            throw new \Exception("Erreur lors de la création de la commande.");
          }
          // Ajout du jeu dans la commande
          $gameUserOrderRepository = new GameUserOrderRepository();
          $isGameAdded = $gameUserOrderRepository->addGameInCart($gameId, $platformId, $orderId, $quantity, $price, 'add');
          if (!$isGameAdded) {
            throw new \Exception("Erreur lors de l'ajout du jeu dans la commande.");
          }
          // Recherche des données de la commande
          $order = $userOrderRepository->findOrderById($orderId);
          if (!$order) {
            throw new \Exception("La commande n'existe pas.");
          }
          foreach ($order['games'] as $game) {
            //Pour chaque jeu de la commande, récupérer son genre
            $gameGenreRepository = new GameGenreRepository();
            $gameGenres = $gameGenreRepository->findAllGenresByGame($game['game_id']);
            // Création d'un objet Sale pour chaque jeu de la commande
            $sale = new Sale();
            $sale->setId($game['game_id']);
            $sale->setName($game['name']);
            $sale->setGenre($gameGenres);
            $sale->setPlatform($game['platform']);
            $sale->setStore($order['store_location']);
            $sale->setPrice($game['price']);
            $sale->setQuantity($game['quantity']);
            $sale->setDate(new DateTime());
            $sale->setOrderId($orderId);
            // Enregistrement des ventes en base de données
            $salesRepository = new SalesRepository();
            $salesRepository->setOneSale($sale);
            // Mise à jour du stock des jeux
            $gamePlatformRepository = new GamePlatformRepository();
            $isStockUpdated = $gamePlatformRepository->updateGameStock($game['game_id'], $game['platform_id'], $order['store_id'], $game['quantity'], 'remove');
            if (!$isStockUpdated) {
              throw new \Exception("Erreur lors de la mise à jour du stock du jeu.");
            }
          }          
          header('Location: /index.php?controller=employe&action=buying');
          exit;
        } else {
          // Comportement par défaut : affichage de la liste des jeux
          $gamePlatformRepository = new GamePlatformRepository();
          $allGames = $gamePlatformRepository->getAllGamesByStoreForEmployes($_SESSION['user']['store_id']);
          $platformRepository = new PlatformRepository();
          $platforms = $platformRepository->getAllPlatforms();
          $userRepository = new UserRepository();
          $allUsers = $userRepository->findAllUsersByStore($_SESSION['user']['store_id']);
          if (empty($platforms)) {
            throw new \Exception("Erreur lors de la récupération des plateformes.");
          }
          if (empty($allGames)) {
            throw new \Exception("Erreur lors de la récupération des jeux.");
          }
          if (empty($allUsers)) {
            throw new \Exception("Erreur lors de la récupération des utilisateurs.");
          }
          $this->render('employe/buying', [
            'games' => $allGames,
            'platforms' => $platforms,
            'users' => $allUsers
          ]);
        }
      } else {
        throw new \Exception("Vous n'avez pas les droits pour accéder à cette page, veuillez vous connecter");
      }
    } catch (\Exception $e) {
      $this->render('employe/error', [
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function order() : void
  {
    try {
      if (Security::isEmploye()) {
        if (!isset($_GET['id'])) {
          throw new \Exception("Aucune commande sélectionnée.");
        }
        $orderId = Security::secureInput($_GET['id']);
        $userOrderRepository = new UserOrderRepository();
        $order = $userOrderRepository->findOrderById($orderId);
        if (!$order) {
          throw new \Exception("La commande n'existe pas.");
        }
        $this->render('employe/order', [
          'order' => $order
        ]);
      } else {
        throw new \Exception("Vous n'avez pas les droits pour accéder à cette page, veuillez vous connecter");
      }
    } catch (\Exception $e) {
      $this->render('employe/error', [
        'error' => $e->getMessage()
      ]);
    } 
  }

  protected function details(): void
  {
    try {
      if (Security::isEmploye()) {
        $salesRepository = new SalesRepository();
        $sales = $salesRepository->getAllSalesDatas(Security::getEmployeStore());
        if (!$sales) {
          throw new \Exception("Erreur lors de la récupération des ventes.");
        }
        $platformRepository = new PlatformRepository();
        $platforms = $platformRepository->getAllPlatforms();
        if (!$platforms) {
          throw new \Exception("Erreur lors de la récupération des plateformes.");
        }
        $this->render('employe/details', [
          'sales' => $sales,
          'platforms' => $platforms
        ]);
      } else {
        throw new \Exception("Vous n'avez pas les droits pour accéder à cette page, veuillez vous connecter");
      }
    } catch (\Exception $e) {
      $this->render('employe/error', [
        'error' => $e->getMessage()
      ]);
    }
  }

}