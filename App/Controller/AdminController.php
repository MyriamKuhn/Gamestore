<?php

namespace App\Controller;

use App\Tools\Security;
use App\Tools\UserValidator;
use App\Repository\UserRepository;
use App\Repository\UserOrderRepository;
use App\Repository\GameGenreRepository;
use App\Repository\GamePlatformRepository;
use App\Repository\SalesRepository;
use DateTime;
use DateTimeZone;
use App\Model\Sale;
use App\Repository\GameUserOrderRepository;
use App\Repository\PlatformRepository;
use App\Model\User;
use App\Repository\StoreRepository;
use App\Repository\GenreRepository;
use App\Repository\PegiRepository;
use App\Repository\GamesRepository;
use App\Repository\ImageRepository;
use App\Tools\StringTools;

class AdminController extends RoutingController
{

  public function route(): void
  {
    try {
      if (isset($_GET['action'])) {
        switch ($_GET['action']) {
          case 'home':
            $this->home();
            break;
          case 'password':
            $this->password();
            break;
          case 'orders':
            $this->orders();
            break;
          case 'order':
            $this->order();
            break; 
          case 'buying':
            $this->buying();
            break;
          case 'employes':
            $this->employes();
            break;
          case 'employe':
            $this->employe();
            break;
          case 'users':
            $this->users();
            break;
          case 'sales':
            $this->sales();
            break;
          case 'details':
            $this->details();
            break;
          case 'products':
            $this->products();
            break;
          case 'product':
            $this->product();
            break;
          default:
            throw new \Exception("Cette action n'existe pas : " . $_GET['action']);
            break;
          }
      } else {
        throw new \Exception("Aucune action détectée");
      }
    } catch (\Exception $e) {
      $this->render('admin/error', [
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function home(): void
  {
    try {
      if (Security::isAdmin()) {
        $this->render('admin/home');
      } else {
        throw new \Exception("Vous n'avez pas les droits pour accéder à cette page, veuillez vous connecter");
      }
    } catch (\Exception $e) {
      $this->render('admin/error', [
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function password(): void
  {
    try {
      if (Security::isAdmin()) {
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
            $this->render('admin/error', [
              'error' => 'Veuillez vous connecter pour accéder à cette page.'
            ]);
            exit;
          }
          $userRepository = new UserRepository();
          $user = $userRepository->getUserById($userId);
          if ($user && Security::verifyPassword($oldPassword, $user->getPassword())) {
            // Si aucune erreur, modification des données
            if (empty($errors)) {
              $user = $userRepository->updateUserPassword($userId, $newPassword);
              // Si mise en place en base de données réussie
              if ($user) {
                $this->render('admin/password', [
                  'user' => $user,
                  'success' => 'Votre mot de passe a bien été modifié.'
                ]);
              } else {
                $this->render('admin/error', [
                  'error' => 'Erreur lors de la modification de votre mot de passe.'
                ]);
              }
            } else {
              $userRepository = new UserRepository();
              $user = $userRepository->getUserById($userId);
              if (!$user) {
                throw new \Exception("Erreur lors de la récupération de vos données personnelles.");
              } else {
                $this->render('admin/password', [
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
              $this->render('admin/password', [
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
              $this->render('admin/password', [
                'user' => $user
              ]);
            }
          } else {
            $this->render('admin/error', [
              'error' => 'Veuillez vous connecter pour accéder à cette page.'
            ]);
          }
        }
      } else {
        throw new \Exception("Vous n'avez pas les droits pour accéder à cette page, veuillez vous connecter");
      }
    } catch (\Exception $e) {
      $this->render('admin/error', [
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function orders(): void
  {
    try {
      if (Security::isAdmin()) {
        // Pour mettre le statut d'une commande à Livrée
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
          // Uniquement enregistrer la vente et mettre à jour le stock si la commande était validée ou annulée car les commandes en magasin ont déjà été actualisées dans la base
          if ($orderStatus === 'Validée' || $orderStatus === 'Annulée') {
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
          }          
          header('Location: index.php?controller=admin&action=orders');
          exit;
        // Pour mettre le statut d'une commande à Annulée
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancelOrder'])) {
          // Vérification du token CSRF
          Security::checkCSRF($_POST['csrf_token']);
          // Récupération des données du formulaire et sécurisation
          $orderId = Security::secureInput($_POST['order_id']);
          $orderStatus = Security::secureInput($_POST['order_status']);
          $userOrderRepository = new UserOrderRepository();
          $order = $userOrderRepository->validateOrderByEmployee($orderId, 'Annulée');
          if (!$order) {
            throw new \Exception("Erreur lors de la mise à jour du statut de la commande.");
          }
          // Retirer la commande de la base de données uniquement si la commande était en statut 'Magasin' ou en statut 'Livrée'
          if ($orderStatus === 'Magasin' || $orderStatus === 'Livrée') {
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
              $sale->setDate(new DateTime($order['order_date'], new DateTimeZone('Europe/Paris')));
              $sale->setOrderId($orderId);
              // Suppression des ventes en base de données
              $salesRepository = new SalesRepository();
              $resultSale = $salesRepository->deleteSale($sale);
              if ($resultSale === false) {
                throw new \Exception("Erreur lors de l'enregistrement de la vente." . $resultSale);
              }
              // Mise à jour du stock des jeux
              $gamePlatformRepository = new GamePlatformRepository();
              $isStockUpdated = $gamePlatformRepository->updateGameStock($game['game_id'], $game['platform_id'], $order['store_id'], $game['quantity'], 'add');
              if (!$isStockUpdated) {
                throw new \Exception("Erreur lors de la mise à jour du stock du jeu.");
              }
            }
          }
          header('Location: index.php?controller=admin&action=orders');
          exit;
        // Pour placer une commande en statut magasin
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['shopOrder'])) {
          // Vérification du token CSRF
          Security::checkCSRF($_POST['csrf_token']);
          // Récupération des données du formulaire et sécurisation
          $orderId = Security::secureInput($_POST['order_id']);
          $orderStatus = Security::secureInput($_POST['order_status']);
          $userOrderRepository = new UserOrderRepository();
          $order = $userOrderRepository->validateOrderByEmployee($orderId, 'Magasin');
          if (!$order) {
            throw new \Exception("Erreur lors de la mise à jour du statut de la commande.");
          }
          //Uniquement si la commande est en Annulée ou Validée, on met à jour le stock des jeux et on enregistre les ventes en base de données
          if ($orderStatus === 'Validée' || $orderStatus === 'Annulée') {
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
          }
          header('Location: index.php?controller=admin&action=orders');
          exit;
        // Comportement par défaut : affichage de la liste des commandes de toutes les villes
        } else {
          $userOrderRepository = new UserOrderRepository();
          $orders = $userOrderRepository->findAllOrders();
          $this->render('admin/orders', [
            'orders' => $orders
          ]);
        }
      } else {
        throw new \Exception("Vous n'avez pas les droits pour accéder à cette page, veuillez vous connecter");
      }
    } catch (\Exception $e) {
      $this->render('admin/error', [
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function order() : void
  {
    try {
      if (Security::isAdmin()) {
        if (!isset($_GET['id'])) {
          throw new \Exception("Aucune commande sélectionnée.");
        }
        $orderId = Security::secureInput($_GET['id']);
        $userOrderRepository = new UserOrderRepository();
        $order = $userOrderRepository->findOrderById($orderId);
        if (!$order) {
          throw new \Exception("La commande n'existe pas.");
        }
        $this->render('admin/order', [
          'order' => $order
        ]);
      } else {
        throw new \Exception("Vous n'avez pas les droits pour accéder à cette page, veuillez vous connecter");
      }
    } catch (\Exception $e) {
      $this->render('admin/error', [
        'error' => $e->getMessage()
      ]);
    } 
  }

  protected function buying(): void
  {
    try {
      // Si validation d'une vente
      if (Security::isAdmin()) {
        // Si validation d'une vente
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orderStore'])) {
          // Vérification du token CSRF
          Security::checkCSRF($_POST['csrf_token']);
          // Récupération des données du formulaire et sécurisation
          $userId = Security::secureInput($_POST['user_id']);
          if ($userId == 0) {
            $userId = $_SESSION['user']['id'];
          }
          $gameId = Security::secureInput($_POST['gameId']);
          $platformId = Security::secureInput($_POST['platformId']);
          $quantity = Security::secureInput($_POST['quantity']);
          $price = Security::secureInput($_POST['price']);
          $discount = Security::secureInput($_POST['discount']);
          $storeId = Security::secureInput($_POST['store_id']);
          // Vérification des données du jeu
          $gamePlatformRepository = new GamePlatformRepository();
          $isChecked = $gamePlatformRepository->checkGameDatas($gameId, $platformId, $price, $discount, $storeId);
          if (!$isChecked) {
            throw new \Exception("Les données du jeu ne sont pas correctes, veuillez rafraichir la page.");
          }
          // Vérification si le jeu est encore en stock
          $stock = $gamePlatformRepository->checkGameStock($gameId, $platformId, $storeId);
          if ($stock < $quantity) {
            throw new \Exception("Veuillez vérifier le stock du jeu.");
          }
          // Changer le store de référence de l'administrateur afin qu'il corresponde à celui de la commande
          $userRepository = new UserRepository();
          $user = $userRepository->updateUserStore($_SESSION['user']['id'], $storeId);
          if (!$user) {
            throw new \Exception("Erreur lors de la modification du Gamestore de l'administrateur.");
          }
          // Création d'une commande magasin vide
          $userOrderRepository = new UserOrderRepository();
          $orderId = $userOrderRepository->createEmptyOrder($userId, $storeId);
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
          header('Location: index.php?controller=admin&action=buying');
          exit;
        // Comportement par défaut : affichage de la liste des jeux
        } else {
          $gamePlatformRepository = new GamePlatformRepository();
          $allGames = $gamePlatformRepository->getAllGamesForAdmin();
          $platformRepository = new PlatformRepository();
          $platforms = $platformRepository->getAllPlatforms();
          $userRepository = new UserRepository();
          $allUsers = $userRepository->findAllUsers();
          if (empty($platforms)) {
            throw new \Exception("Erreur lors de la récupération des plateformes.");
          }
          if (empty($allGames)) {
            throw new \Exception("Erreur lors de la récupération des jeux.");
          }
          if (empty($allUsers)) {
            throw new \Exception("Erreur lors de la récupération des utilisateurs.");
          }
          $this->render('admin/buying', [
            'games' => $allGames,
            'platforms' => $platforms,
            'users' => $allUsers
          ]);
        }
      } else {
        throw new \Exception("Vous n'avez pas les droits pour accéder à cette page, veuillez vous connecter");
      }
    } catch (\Exception $e) {
      $this->render('admin/error', [
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function employes(): void
  {
    try {
      if (Security::isAdmin()) {
        // Si modification de l'employé
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editEmploye'])) {
          // Vérification du token CSRF
          Security::checkCSRF($_POST['csrf_token']);
          // Récupération des données du formulaire et sécurisation
          $userId = Security::secureInput($_POST['userId']);
          // Renvoi vers la page de modification de l'employé
          header('Location: index.php?controller=admin&action=employe&id=' . $userId);
          exit;
        // Si blocage de l'employé
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['blockEmploye'])) {
          // Vérification du token CSRF
          Security::checkCSRF($_POST['csrf_token']);
          // Récupération des données du formulaire et sécurisation
          $userId = Security::secureInput($_POST['userId']);
          $userRepository = new UserRepository();
          $employe = $userRepository->blockUser($userId);
          if (!$employe) {
            throw new \Exception("Erreur lors du blocage de l'employé.");
          }
          header('Location: index.php?controller=admin&action=employes');
          exit;
        // Si déblocage de l'employé
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unblockEmploye'])) {
          // Vérification du token CSRF
          Security::checkCSRF($_POST['csrf_token']);
          // Récupération des données du formulaire et sécurisation
          $userId = Security::secureInput($_POST['userId']);
          $userRepository = new UserRepository();
          $employe = $userRepository->unblockUser($userId);
          if (!$employe) {
            throw new \Exception("Erreur lors du déblocage de l'employé.");
          }
          header('Location: index.php?controller=admin&action=employes');
          exit;
        // Comportement par défaut : affichage de la liste des employés
        } else {
          $userRepository = new UserRepository();
          $employes = $userRepository->findAllUsers('employe');
          if (!$employes) {
            throw new \Exception("Erreur lors de la récupération des employés.");
          }
          $this->render('admin/employes', [
            'employes' => $employes
          ]);
        }
      } else {
        throw new \Exception("Vous n'avez pas les droits pour accéder à cette page, veuillez vous connecter");
      }
    } catch (\Exception $e) {
      $this->render('admin/error', [
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function employe(): void
  {
    try {
      if (Security::isAdmin()) {
        // Si modification de l'employé
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifyEmploye'])) {
        // Vérification du token CSRF
        Security::checkCSRF($_POST['csrf_token']);
        // Récupération des données du formulaire et sécurisation
        $userId = Security::secureInput($_POST['user_id']);
        $firstName = Security::secureInput($_POST['first_name']);
        $lastName = Security::secureInput($_POST['last_name']);
        $email = Security::secureInput($_POST['email']);
        $address = Security::secureInput($_POST['address']);
        $postcode = Security::secureInput($_POST['postcode']);
        $city = Security::secureInput($_POST['city']);
        $storeId = Security::secureInput($_POST['store_id']);
        // Vérification des données
        $errors = [];
        if (empty($storeId)) {
          $errors['store_id'] = 'Veuillez sélectionner un Gamestore.';
        }
        UserValidator::validateLastName($lastName) ?: $errors['last_name'] = 'Le champ nom n\'est pas valide';
        UserValidator::validateFirstName($firstName) ?: $errors['first_name'] = 'Le champ prénom n\'est pas valide';
        UserValidator::validateAddress($address) ?: $errors['address'] = 'Le champ adresse n\'est pas valide';
        UserValidator::validatePostcode($postcode) ?: $errors['postcode'] = 'Le champ code postal n\'est pas valide';
        UserValidator::validateCity($city) ?: $errors['city'] = 'Le champ ville n\'est pas valide';
        UserValidator::validateEmail($email) ?: $errors['email'] = 'Le champ email n\'est pas valide';
        // Si aucune erreur, modification des données
        if (empty($errors)) {
          $userRepository = new UserRepository();
          $user = $userRepository->updateUserByAdmin($userId, $firstName, $lastName, $address, $postcode, $city, $email, $storeId);
          // Si mise en place en base de données réussie
          if ($user) {
            header('Location: index.php?controller=admin&action=employes');
            exit;
          } else {
            $this->render('admin/error', [
              'error' => 'Erreur lors de la modification des données de l\'employé.'
            ]);
          }
        } else {
          $userRepository = new UserRepository();
          $user = $userRepository->getUserById($userId);
          if (!$user) {
            throw new \Exception("Erreur lors de la récupération des données de l'employé.");
          } else {
            $this->render('admin/employe', [
              'employe' => $user,
              'isModify' => true,
              'errors' => $errors
            ]);
          }
        }
        // Si ajout d'un employé
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addEmploye'])) {
          // Vérification du token CSRF
          Security::checkCSRF($_POST['csrf_token']);
          // Récupération des données du formulaire et sécurisation
          $firstName = Security::secureInput($_POST['first_name']);
          $lastName = Security::secureInput($_POST['last_name']);
          $email = Security::secureInput($_POST['email']);
          $address = Security::secureInput($_POST['address']);
          $postcode = Security::secureInput($_POST['postcode']);
          $city = Security::secureInput($_POST['city']);
          $storeId = Security::secureInput($_POST['store_id']);
          $password = 'Q8b-mLNYB7tVjGeUqf9g6*';
          $errors = [];
          // Création d'un objet User
          $user = new User();
          // Hydratation de l'objet User
          $user->setFirst_name($firstName);
          $user->setLast_name($lastName);
          $user->setAddress($address);
          $user->setPostcode($postcode);
          $user->setCity($city);
          $user->setEmail($email);
          $user->setPassword($password);
          $user->setRole(_ROLE_EMPLOYE_);
          $user->setFk_store_id($storeId);
          // Validation des données
          $errors = UserValidator::validate($user);
          // Si aucune erreur, ajout des données
          if (empty($errors)) {
            $userRepository = new UserRepository();
            $user = $userRepository->addUser($user);
            // Si mise en place en base de données réussie
            if ($user) {
              header('Location: index.php?controller=admin&action=employes');
              exit;
            } else {
              $this->render('admin/error', [
                'error' => 'Erreur lors de l\'ajout de l\'employé.'
              ]);
            }
          } else {
            $this->render('admin/employe', [
              'isModify' => false,
              'errors' => $errors
            ]);
          }
        // Si demande de modification
        } else if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
          $userId = Security::secureInput($_GET['id']);
          $userRepository = new UserRepository();
          $employe = $userRepository->getUserById($userId);
          if (!$employe) {
            throw new \Exception("L'employé n'existe pas.");
          }
          $this->render('admin/employe', [
            'employe' => $employe,
            'isModify' => true
          ]);
        // Comportement par défaut au chargement de la page pour un ajout d'employé
        } else {
          $this->render('admin/employe', [
            'isModify' => false
          ]);
        }
      } else {
        throw new \Exception("Vous n'avez pas les droits pour accéder à cette page, veuillez vous connecter");
      }
    } catch (\Exception $e) {
      $this->render('admin/error', [
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function users():void
  {
    try {
      if (Security::isAdmin()) {
        // Si modification du mail du client
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editUser'])) {
          // Vérification du token CSRF
          Security::checkCSRF($_POST['csrf_token']);
          // Récupération des données du formulaire et sécurisation
          $userId = Security::secureInput($_POST['userId']);
          $email = Security::secureInput($_POST['email']);
          // Vérification des données
          $errors = [];
          UserValidator::validateEmail($email) ?: $errors['email'] = 'Le champ email n\'est pas valide';
          // Si aucune erreur, modification des données
          if (empty($errors)) {
            $userRepository = new UserRepository();
            $user = $userRepository->updateUserEmail($userId, $email);
            // Si mise en place en base de données réussie
            if ($user) {
              header('Location: index.php?controller=admin&action=users');
              exit;
            } else {
              $this->render('admin/error', [
                'error' => 'Erreur lors de la modification des données de l\'utilisateur.'
              ]);
            }
          } else {
            $userRepository = new UserRepository();
            $users = $userRepository->findAllUsers('user');
            if (!$users) {
              throw new \Exception("Erreur lors de la récupération des données de l'utilisateur.");
            } else {
              $this->render('admin/users', [
                'users' => $users,
                'errors' => $errors
              ]);
            }
          }
        // Si blocage du client
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['blockUser'])) {
          // Vérification du token CSRF
          Security::checkCSRF($_POST['csrf_token']);
          // Récupération des données du formulaire et sécurisation
          $userId = Security::secureInput($_POST['userId']);
          $userRepository = new UserRepository();
          $user = $userRepository->blockUser($userId);
          if (!$user) {
            throw new \Exception("Erreur lors du blocage de l'utilisateur.");
          }
          header('Location: index.php?controller=admin&action=users');
          exit;
        // Si déblocage du client
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unblockUser'])) {
          // Vérification du token CSRF
          Security::checkCSRF($_POST['csrf_token']);
          // Récupération des données du formulaire et sécurisation
          $userId = Security::secureInput($_POST['userId']);
          $userRepository = new UserRepository();
          $user = $userRepository->unblockUser($userId);
          if (!$user) {
            throw new \Exception("Erreur lors du déblocage de l'utilisateur.");
          }
          header('Location: index.php?controller=admin&action=users');
          exit;
        // Comportement par défaut au chargement de la page
        } else {
          $userRepository = new UserRepository();
          $users = $userRepository->findAllUsers('user');
          if (!$users) {
            throw new \Exception("Erreur lors de la récupération des utilisateurs.");
          }
          $this->render('admin/users', [
            'users' => $users
          ]);
        }
      } else {
        throw new \Exception("Vous n'avez pas les droits pour accéder à cette page, veuillez vous connecter");
      }
    } catch (\Exception $e) {
      $this->render('admin/error', [
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function sales(): void
  {
    try {
      if (Security::isAdmin()) {
        $this->render('admin/sales');
      } else {
        throw new \Exception("Vous n'avez pas les droits pour accéder à cette page, veuillez vous connecter");
      }
    } catch (\Exception $e) {
      $this->render('admin/error', [
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function details(): void
  {
    try {
      if (Security::isAdmin()) {
        $salesRepository = new SalesRepository();
        $sales = $salesRepository->getAllSalesDatas();
        if (!$sales) {
          throw new \Exception("Erreur lors de la récupération des ventes.");
        }
        $platformRepository = new PlatformRepository();
        $platforms = $platformRepository->getAllPlatforms();
        if (!$platforms) {
          throw new \Exception("Erreur lors de la récupération des plateformes.");
        }
        $this->render('admin/details', [
          'sales' => $sales,
          'platforms' => $platforms
        ]);
      } else {
        throw new \Exception("Vous n'avez pas les droits pour accéder à cette page, veuillez vous connecter");
      }
    } catch (\Exception $e) {
      $this->render('admin/error', [
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function products(): void
  {
    try {
      if (Security::isAdmin()) {
        // Si ajout de stock
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addStock'])) {
          // Vérification du token CSRF
          Security::checkCSRF($_POST['csrf_token']);
          // Récupération des données du formulaire et sécurisation
          $gameId = Security::secureInput($_POST['game_id']);
          $platformId = Security::secureInput($_POST['platform_id']);
          $storeId = Security::secureInput($_POST['store_id']);
          // Vérification des données
          $gamePlatformRepository = new GamePlatformRepository();
          $isStockUpdated = $gamePlatformRepository->updateGameStock($gameId, $platformId, $storeId, 1, 'add');
          if (!$isStockUpdated) {
            throw new \Exception("Erreur lors de l'ajout du stock.");
          }
          header('Location: index.php?controller=admin&action=products');
          exit;
        // Si suppression de stock
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['removeStock'])) {
          // Vérification du token CSRF
          Security::checkCSRF($_POST['csrf_token']);
          // Récupération des données du formulaire et sécurisation
          $gameId = Security::secureInput($_POST['game_id']);
          $platformId = Security::secureInput($_POST['platform_id']);
          $storeId = Security::secureInput($_POST['store_id']);
          // Vérification des données
          $gamePlatformRepository = new GamePlatformRepository();
          $isStockUpdated = $gamePlatformRepository->updateGameStock($gameId, $platformId, $storeId, 1, 'remove');
          if (!$isStockUpdated) {
            throw new \Exception("Erreur lors de la suppression du stock.");
          }
          header('Location: index.php?controller=admin&action=products');
          exit;
        // Si modification d'un jeu
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editGame'])) {
          // Vérification du token CSRF
          Security::checkCSRF($_POST['csrf_token']);
          // Récupération des données du formulaire et sécurisation
          $gameId = Security::secureInput($_POST['game_id']);
          // Renvoi vers la page de modification de l'employé
          header('Location: index.php?controller=admin&action=product&id=' . $gameId);
          exit;
        // Comportement par défaut : affichage de la liste des jeux
        } else {
        $gamePlatformRepository = new GamePlatformRepository();
        $games = $gamePlatformRepository->getGamesStockList();
        $platformRepository = new PlatformRepository();
        $platforms = $platformRepository->getAllPlatforms();
        if (!$games) {
          throw new \Exception("Erreur lors de la récupération des jeux.");
        }
        if (!$platforms) {
          throw new \Exception("Erreur lors de la récupération des plateformes.");
        }
          $this->render('admin/products', [
            'games' => $games,
            'platforms' => $platforms
          ]);
        }
      } else {
      throw new \Exception("Vous n'avez pas les droits pour accéder à cette page, veuillez vous connecter");
      }
    } catch (\Exception $e) {
      $this->render('admin/error', [
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function product(): void
  {
    try {
      if (Security::isAdmin()) {
        // Si demande de modification
        if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
          $gameId = Security::secureInput($_GET['id']);
          $gamePlatformRepository = new GamePlatformRepository();
          $game = $gamePlatformRepository->getGameById($gameId);
          if (!$game) {
            throw new \Exception("Le jeu n'existe pas.");
          }
          $platformRepository = new PlatformRepository();
          $platforms = $platformRepository->getAllPlatforms();
          if (!$platforms) {
            throw new \Exception("Erreur lors de la récupération des plateformes.");
          }
          $storeRepository = new StoreRepository();
          $stores = $storeRepository->getAllStores();
          if (!$stores) {
            throw new \Exception("Erreur lors de la récupération des Gamestores.");
          }
          $genreRepositosry = new GenreRepository();
          $genres = $genreRepositosry->getAllGenres();
          if (!$genres) {
            throw new \Exception("Erreur lors de la récupération des genres.");
          }
          $pegiRepository = new PegiRepository();
          $pegis = $pegiRepository->getAllPegi();
          if (!$pegis) {
            throw new \Exception("Erreur lors de la récupération des Pegis.");
          }
          $this->render('admin/product', [
            'game' => $game,
            'platforms' => $platforms,
            'stores' => $stores,
            'genres' => $genres,
            'pegis' => $pegis,
            'isModify' => true
          ]);
        // Si modification d'un jeu
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifyGame'])) {
          // Vérification du token CSRF
          Security::checkCSRF($_POST['csrf_token']);
          // Récupération des données simples du formulaire, sécurisation et validation
          $formDatas = $_POST;
          $filesDatas = $_FILES;
          $errors = [];
          $gameId = Security::secureInput($formDatas['game_id']);
          // Nom du jeu
          $gameName = $formDatas['game_name'];
          if (empty($gameName)) {
            $errors['game_name'] = 'Veuillez renseigner le nom du jeu.';
          }
          if (strlen($gameName) > 100) {
            $errors['game_name_length'] = 'Le nom du jeu est trop long.';
          }
          if (!preg_match('/^[a-zA-ZÀ-ÿœŒæÆ0-9\-\s\'\’\&\!\?\.\(\)\[\]:]{3,}$/', $gameName)) {
            $errors['game_name'] = 'Le nom n\'est pas valide.';
          }
          $gameName = Security::secureInput($formDatas['game_name']);
          // Description du jeu
          $gameDescription = $formDatas['game_description'];
          if (empty($gameDescription)) {
            $errors['game_description'] = 'Veuillez renseigner la description du jeu.';
          }
          if (!preg_match('/^[a-zA-ZÀ-ÿœŒæÆ0-9\-\s\'\’\&\!\?\.\,\(\)\[\]:;\"\n]{3,}$/', $gameDescription)) {
            $errors['game_description'] = 'La description n\'est pas valide.';
          }
          $gameDescription = Security::secureInput($formDatas['game_description']);
          // PEGI du jeu
          $pegiId = Security::secureInput($formDatas['pegi_id']);
          if (empty($pegiId)) {
            $errors['game_pegi'] = 'Veuillez sélectionner un PEGI.';
          }
          // Genres du jeu
          $genresId = [];
          foreach ($formDatas['genres_id'] as $genreId) {
            $genresId[] = Security::secureInput($genreId);
          }
          if (empty($genresId)) {
            $errors['game_genres'] = 'Veuillez sélectionner au moins un genre.';
          }
          // Formats autorisés pour les images
          $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'];
          $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
          $maxSize = 2 * 1024 * 1024; // 2 Mo
          // Image spotlight
          $deleteSpotlight = false;
          $spotlightToDelete = '';
          $spotlighToUpload = [];
          if (isset($formDatas['delete-spotlight']) && $formDatas['delete-spotlight'] === 'on') {
            $deleteSpotlight = true;
            $spotlightToDelete = $formDatas['delete-spotlight-image'];
            $spotlighToUpload = $filesDatas['game_spotlight'];
            if (empty($spotlighToUpload) && empty($spotlighToUpload['name'])) {
              $errors['game_spotlight'] = 'Veuillez ajouter au moins une image spotlight ou ne pas supprimer l\'existante.';
            } else {
              if ($spotlighToUpload['error'] !== 0) {
                $errors['game_spotlight_error'] = 'Erreur lors de l\'upload de l\'image spotlight.';
              } else {
                if (!in_array($spotlighToUpload['type'], $allowedTypes)) {
                  $errors['game_spotlight_format'] = 'Le format de l\'image spotlight n\'est pas autorisé.';
                }
                $fileExtension = strtolower(pathinfo($spotlighToUpload['name'], PATHINFO_EXTENSION));
                if (!in_array($fileExtension, $allowedExtensions)) {
                  $errors['game_spotlight_extension'] = 'L\'extension de l\'image spotlight n\'est pas autorisée.';
                }
                if ($spotlighToUpload['size'] > $maxSize) {
                  $errors['game_spotlight_size'] = 'L\'image spotlight est trop lourde.';
                }
                if (getimagesize($spotlighToUpload["tmp_name"]) === false) {
                  $errors['game_spotlight_data'] = 'Le fichier spotlight n`\'est pas une image valide.';
                }
              }
            }
          } else {
            $deleteSpotlight = false;
            $spotlighToUpload = $filesDatas['game_spotlight'];
            if (!empty($spotlighToUpload) && !empty($spotlighToUpload['name'])) {
              $errors['game_spotlight'] = 'Vous ne pouvez avoir qu\'une seule image spotlight.';
            } 
          }
          // Image de présentation
          $deletePresentation = false;
          $presentationToDelete = '';
          $presentationToUpload = [];
          if (isset($formDatas['delete-presentation']) && $formDatas['delete-presentation'] === 'on') {
            $deletePresentation = true;
            $presentationToDelete = $formDatas['delete-presentation-image'];
            $presentationToUpload = $filesDatas['game_presentation'];
            if (empty($presentationToUpload) && empty($presentationToUpload['name'])) {
              $errors['game_presentation'] = 'Veuillez ajouter au moins une image de présentation ou ne pas supprimer l\'existante.';
            } else {
              if ($presentationToUpload['error'] !== 0) {
                $errors['game_presentation_error'] = 'Erreur lors de l\'upload de l\'image de présentation.';
              } else {
                if (!in_array($presentationToUpload['type'], $allowedTypes)) {
                  $errors['game_presentation_format'] = 'Le format de l\'image de présentation n\'est pas autorisé.';
                }
                $fileExtension = strtolower(pathinfo($presentationToUpload['name'], PATHINFO_EXTENSION));
                if (!in_array($fileExtension, $allowedExtensions)) {
                  $errors['game_presentation_extension'] = 'L\'extension de l\'image de présentation n\'est pas autorisée.';
                }
                if ($presentationToUpload['size'] > $maxSize) {
                  $errors['game_presentation_size'] = 'L\'image de présentation est trop lourde.';
                }
                if (getimagesize($presentationToUpload["tmp_name"]) === false) {
                  $errors['game_presentation_data'] = 'Le fichier de présentation n`\'est pas une image valide.';
                }
              }
            }
          } else {
            $deletePresentation = false;
            $presentationToUpload = $filesDatas['game_presentation'];
            if (!empty($presentationToUpload) && !empty($presentationToUpload['name'])) {
              $errors['game_presentation'] = 'Vous ne pouvez avoir qu\'une seule image de présentation.';
            } 
          }
          // Images de carousel
          $carouselImagesToDelete = [];
          foreach ($formDatas as $key => $value) {
            if (strpos($key, 'delete-carousel-') === 0 && $value === 'on') {
              $index = str_replace('delete-carousel-', '', $key);
              $carouselImagesToDelete[] = $formDatas['delete-carousel-image-' . $index];
            }
          }
          $carouselImagesToUpload = [];
          if (!empty($filesDatas['game_carousel']) && !empty($filesDatas['game_carousel']['name'][0])) {
            foreach ($filesDatas['game_carousel'] as $key => $values) {
              foreach ($values as $index => $value) {
                if (!isset($carouselImagesToUpload[$index])) {
                  $carouselImagesToUpload[$index] = []; 
                }
                $carouselImagesToUpload[$index][$key] = $value;
              }
            }
          }
          $existingImages = 0;
          foreach ($formDatas as $key => $value) {
            if (strpos($key, 'delete-carousel-image-') === 0) {
              $existingImages++;
            }
          }
          if ($existingImages - count($carouselImagesToDelete) + count($carouselImagesToUpload) < 2) {
            $errors['game_carousel'] = 'Vous devez avoir au moins 2 images de carousel au total.';
          }
          if (!empty($carouselImagesToUpload)) {
            foreach ($carouselImagesToUpload as $index => $carouselImageToUpload) {
              if ($carouselImageToUpload['error'] !== 0) {
                $errors['game_carousel_error_' . $index] = 'Erreur lors de l\'upload de l\'image de carousel.';
              } else {
                if (!in_array($carouselImageToUpload['type'], $allowedTypes)) {
                  $errors['game_carousel_format_' . $index] = 'Le format de l\'image de carousel n\'est pas autorisé.';
                }
                $fileExtension = strtolower(pathinfo($carouselImageToUpload['name'], PATHINFO_EXTENSION));
                if (!in_array($fileExtension, $allowedExtensions)) {
                  $errors['game_carousel_extension'] = 'L\'extension de l\'image de carousel n\'est pas autorisée.';
                }
                if ($carouselImageToUpload['size'] > $maxSize) {
                  $errors['game_carousel_size_' . $index] = 'L\'image de carousel est trop lourde.';
                }
                if (getimagesize($carouselImageToUpload["tmp_name"]) === false) {
                  $errors['game_carousel_data'] = 'Le fichier de carousel n`\'est pas une image valide.';
                }
              }
            }
          }
          // Données spécifiques à la plateforme et au magasin
          $specificDatas = [];
          foreach ($formDatas as $key => $value) {
            if (preg_match('/^(\d+)-(\d+)-(price|new|reduced|discount|stock)$/', $key, $matches)) {
              $storeId = $matches[1];
              $platformId = $matches[2];
              $type = $matches[3];

              $found = false;
              foreach ($specificDatas as &$data) {
                if ($data['store_id'] == $storeId && $data['platform_id'] == $platformId) {
                  $found = true;
                  break;
                }
              }
              if (!$found) {
                $specificDatas[] = [
                  'store_id' => Security::secureInput($storeId),
                  'platform_id' => Security::secureInput($platformId),
                  'price' => 0,
                  'new' => 0,
                  'reduced' => 0,
                  'discount' => 0,
                  'stock' => 0
                ];
                $data = &$specificDatas[count($specificDatas) - 1];
              }
              if ($type === 'price' && $value == 0) {
                $data[$type] = null;
              } elseif (($type === 'new' || $type === 'reduced') && $value == 'on') {
                $data[$type] = 1;
              } else {
                $data[$type] = Security::secureInput($value);
              }
            }
          }
          $specificDatas = array_filter($specificDatas, function($entry) {
            return $entry['price'] !== null;
          });
          
          foreach ($specificDatas as &$specificData) {
            if ($specificData['discount'] > 0 && $specificData['reduced'] == 0) {
              $specificData['reduced'] = 1;
            } elseif ($specificData['discount'] == 0 && $specificData['reduced'] == 1) {
              $specificData['reduced'] = 0;
            }
          }
          unset($specificData);
          if (empty($specificDatas)) {
            $errors['game_platforms'] = 'Veuillez renseigner au moins un prix pour une plateforme.';
          }
          // Si aucune erreur, modification des données
          if (empty($errors)) {
            // Nom, description et PEGI en base de données
            $gameRepository = new GamesRepository();
            $game = $gameRepository->updateGameById($gameId, $gameName, $gameDescription, $pegiId);
            if (!$game) {
              throw new \Exception("Erreur lors de la modification des données du jeu.");
            }
            // Genres en base de données
            $gameGenreRepository = new GameGenreRepository();
            $isGenresDeleted = $gameGenreRepository->deleteGameGenres($gameId);
            if (!$isGenresDeleted) {
              throw new \Exception("Erreur lors de la suppression des genres du jeu.");
            }
            foreach ($genresId as $genreId) {
              $isGenreAdded = $gameGenreRepository->addGameGenre($gameId, $genreId);
              if (!$isGenreAdded) {
                throw new \Exception("Erreur lors de l'ajout des genres du jeu.");
              }
            }
            // Si suppression de l'image spotlight, suppression et ajout de la nouvelle image sur serveur et en base de données
            if ($deleteSpotlight) {
              // Suppression du fichier spotlight avec sécurisation du chemin d'accès avec basename pour éviter les attaques de type LFI (Local File Inclusion) ou path traversal
              $spotlightPath = dirname(__DIR__, 2) . '/uploads/games/' . basename($spotlightToDelete);
              if (file_exists($spotlightPath)) {
                if (!unlink($spotlightPath)) {
                  throw new \Exception("Erreur lors de la suppression de l'image spotlight.");
                }
              }
              // Suppression de l'entrée en base de données
              $imageRepository = new ImageRepository();
              $isSpotlightDeleted = $imageRepository->deleteGameImage($spotlightToDelete);
              if (!$isSpotlightDeleted) {
                throw new \Exception("Erreur lors de la suppression de l'image spotlight.");
              }
              // Vérification de l'upload de la nouvelle image
              if (isset($spotlighToUpload["tmp_name"]) && is_uploaded_file($spotlighToUpload["tmp_name"])) {
                // Sécurisation du nom de l'image avec basename pour éviter les attaques de type LFI (Local File Inclusion) ou path traversal, mise en minuscules et ajout d'un identifiant unique pour éviter les doublons
                $extensionSpotlight = pathinfo($spotlighToUpload["name"], PATHINFO_EXTENSION);
                $spotlightnameWithoutExtensions = basename($spotlighToUpload["name"], '.'.$extensionSpotlight);
                $uniqueSpotlightImage = StringTools::slugify($spotlightnameWithoutExtensions);
                $uniqueSpotlightImage = 'spotlight-' . uniqid() . '-' . $uniqueSpotlightImage . '.' . $extensionSpotlight;
                // Déplacement du fichier uploadé vers le dossier des images de jeux
                if (!move_uploaded_file($spotlighToUpload["tmp_name"], dirname(__DIR__, 2) . '/uploads/games/' . $uniqueSpotlightImage)) {
                  throw new \Exception("Erreur lors de l'upload de l'image spotlight.");
                }
                // Ajout de l'entrée en base de données
                if (!$imageRepository) {
                  $imageRepository = new ImageRepository();
                }
                $isSpotlightAdded = $imageRepository->addGameImage($gameId, $uniqueSpotlightImage);
                if (!$isSpotlightAdded) {
                  throw new \Exception("Erreur lors de l'ajout de l'image spotlight.");
                }
              } else {
                throw new \Exception("Erreur lors de l'upload de l'image spotlight.");
              }
            }
            // Si suppression de l'image de présentation, suppression et ajout de la nouvelle image sur serveur et en base de données
            if ($deletePresentation) {
              // Suppression du fichier de présentation avec sécurisation du chemin d'accès avec basename pour éviter les attaques de type LFI (Local File Inclusion) ou path traversal
              $presentationPath = dirname(__DIR__, 2) . '/uploads/games/' . basename($presentationToDelete);
              if (file_exists($presentationPath)) {
                if (!unlink($presentationPath)) {
                  throw new \Exception("Erreur lors de la suppression de l'image de présentation.");
                }
              }
              // Suppression de l'entrée en base de données
              if (!$imageRepository) {
                $imageRepository = new ImageRepository();
              }
              $isPresentationDeleted = $imageRepository->deleteGameImage($presentationToDelete);
              if (!$isPresentationDeleted) {
                throw new \Exception("Erreur lors de la suppression de l'image de présentation.");
              }
              // Vérification de l'upload de la nouvelle image
              if (isset($presentationToUpload["tmp_name"]) && is_uploaded_file($presentationToUpload["tmp_name"])) {
                // Sécurisation du nom de l'image avec basename pour éviter les attaques de type LFI (Local File Inclusion) ou path traversal, mise en minuscules et ajout d'un identifiant unique pour éviter les doublons
                $extensionPresentation = pathinfo($presentationToUpload["name"], PATHINFO_EXTENSION);
                $presentationnameWithoutExtensions = basename($presentationToUpload["name"], '.'.$extensionPresentation);
                $uniquePresentationImage = StringTools::slugify($presentationnameWithoutExtensions);
                $uniquePresentationImage = 'presentation-' . uniqid() . '-' . $uniquePresentationImage . '.' . $extensionPresentation;
                // Déplacement du fichier uploadé vers le dossier des images de jeux
                if (!move_uploaded_file($presentationToUpload["tmp_name"], dirname(__DIR__, 2) . '/uploads/games/' . $uniquePresentationImage)) {
                  throw new \Exception("Erreur lors de l'upload de l'image de présentation.");
                }
                // Ajout de l'entrée en base de données
                if (!$imageRepository) {
                  $imageRepository = new ImageRepository();
                }
                $isPresentationAdded = $imageRepository->addGameImage($gameId, $uniquePresentationImage);
                if (!$isPresentationAdded) {
                  throw new \Exception("Erreur lors de l'ajout de l'image de présentation.");
                }
              } else {
                throw new \Exception("Erreur lors de l'upload de l'image de présentation.");
              }
            }
            // Si suppression d'une image de carousel, suppression sur serveur et mise à jour de la base de données
            if (!empty($carouselImagesToDelete)) {
              foreach ($carouselImagesToDelete as $carouselImageToDelete) {
                // Suppression du fichier de carousel avec sécurisation du chemin d'accès avec basename pour éviter les attaques de type LFI (Local File Inclusion) ou path traversal
                $carouselPath = dirname(__DIR__, 2) . '/uploads/games/' . basename($carouselImageToDelete);
                if (file_exists($carouselPath)) {
                  if (!unlink($carouselPath)) {
                    throw new \Exception("Erreur lors de la suppression de l'image de carousel.");
                  }
                }
                // Suppression de l'entrée en base de données
                if (!$imageRepository) {
                  $imageRepository = new ImageRepository();
                }
                $isCarouselDeleted = $imageRepository->deleteGameImage($carouselImageToDelete);
                if (!$isCarouselDeleted) {
                  throw new \Exception("Erreur lors de la suppression de l'image de carousel.");
                }
              }
            }
            // Si ajout d'une image de carousel, ajout de l'image sur serveur et en base de données
            if (!empty($carouselImagesToUpload)) {
              foreach ($carouselImagesToUpload as $carouselImageToUpload) {
                // Vérification de l'upload de la nouvelle image
                if (isset($carouselImageToUpload["tmp_name"]) && is_uploaded_file($carouselImageToUpload["tmp_name"])) {
                  // Sécurisation du nom de l'image avec basename pour éviter les attaques de type LFI (Local File Inclusion) ou path traversal, mise en minuscules et ajout d'un identifiant unique pour éviter les doublons
                  $extensionCarousel = pathinfo($carouselImageToUpload["name"], PATHINFO_EXTENSION);
                  $carouselnameWithoutExtensions = basename($carouselImageToUpload["name"], '.'.$extensionCarousel);
                  $uniqueCarouselImage = StringTools::slugify($carouselnameWithoutExtensions);
                  $uniqueCarouselImage = 'carousel-' . uniqid() . '-' . $uniqueCarouselImage . '.' . $extensionCarousel;
                  // Déplacement du fichier uploadé vers le dossier des images de jeux
                  if (!move_uploaded_file($carouselImageToUpload["tmp_name"], dirname(__DIR__, 2) . '/uploads/games/' . $uniqueCarouselImage)) {
                    throw new \Exception("Erreur lors de l'upload de l'image de carousel.");
                  }
                  // Ajout de l'entrée en base de données
                  if (!$imageRepository) {
                    $imageRepository = new ImageRepository();
                  }
                  $isCarouselAdded = $imageRepository->addGameImage($gameId, $uniqueCarouselImage);
                  if (!$isCarouselAdded) {
                    throw new \Exception("Erreur lors de l'ajout de l'image de carousel.");
                  }
                } else {
                  throw new \Exception("Erreur lors de l'upload de l'image de carousel.");
                }
              }
            }
            // Données spécifiques à la plateforme et au magasin en base de données
            $gamePlatformRepository = new GamePlatformRepository();
            $isSpecificDatasDeleted = $gamePlatformRepository->deleteGameDatas($gameId);
            if (!$isSpecificDatasDeleted) {
              throw new \Exception("Erreur lors de la suppression des données spécifiques du jeu.");
            }
            foreach ($specificDatas as $specificData) {
              $isSpecificDatasUpdated = $gamePlatformRepository->addGameDatas($gameId, $specificData['platform_id'], $specificData['store_id'], $specificData['price'], $specificData['new'], $specificData['reduced'], ($specificData['discount'] / 100), $specificData['stock']);
              if (!$isSpecificDatasUpdated) {
                throw new \Exception("Erreur lors de la modification des données spécifiques du jeu.");
              }
            }
            header('Location: index.php?controller=admin&action=products');
            exit;      
          // Si erreur, renvoi vers la page de modification
          } else {
            $gamePlatformRepository = new GamePlatformRepository();
            $game = $gamePlatformRepository->getGameById($gameId);
            if (!$game) {
              throw new \Exception("Le jeu n'existe pas.");
            }
            $platformRepository = new PlatformRepository();
            $platforms = $platformRepository->getAllPlatforms();
            if (!$platforms) {
              throw new \Exception("Erreur lors de la récupération des plateformes.");
            }
            $storeRepository = new StoreRepository();
            $stores = $storeRepository->getAllStores();
            if (!$stores) {
              throw new \Exception("Erreur lors de la récupération des Gamestores.");
            }
            $genreRepositosry = new GenreRepository();
            $genres = $genreRepositosry->getAllGenres();
            if (!$genres) {
              throw new \Exception("Erreur lors de la récupération des genres.");
            }
            $pegiRepository = new PegiRepository();
            $pegis = $pegiRepository->getAllPegi();
            if (!$pegis) {
              throw new \Exception("Erreur lors de la récupération des Pegis.");
            }
            $this->render('admin/product', [
              'game' => $game,
              'platforms' => $platforms,
              'stores' => $stores,
              'genres' => $genres,
              'pegis' => $pegis,
              'isModify' => true,
              'errors' => $errors
            ]);
          }  
        // Si ajout de jeu
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addGame'])) {
          // Vérification du token CSRF
          Security::checkCSRF($_POST['csrf_token']);
          // Récupération des données simples du formulaire, sécurisation et validation
          $formDatas = $_POST;
          $filesDatas = $_FILES;
          $errors = [];
          // Nom du jeu
          $gameName = $formDatas['game_name'];
          if (empty($gameName)) {
            $errors['game_name'] = 'Veuillez renseigner le nom du jeu.';
          }
          if (strlen($gameName) > 100) {
            $errors['game_name_length'] = 'Le nom du jeu est trop long.';
          }
          if (!preg_match('/^[a-zA-ZÀ-ÿœŒæÆ0-9\-\s\'\’\&\!\?\.\(\)\[\]:]{3,}$/', $gameName)) {
            $errors['game_name'] = 'Le nom n\'est pas valide.';
          }
          $gameName = Security::secureInput($formDatas['game_name']);
          // Description du jeu
          $gameDescription = $formDatas['game_description'];
          if (empty($gameDescription)) {
            $errors['game_description'] = 'Veuillez renseigner la description du jeu.';
          }
          if (!preg_match('/^[a-zA-ZÀ-ÿœŒæÆ0-9\-\s\'\’\&\!\?\.\,\(\)\[\]:;\"\n]{3,}$/', $gameDescription)) {
            $errors['game_description'] = 'La description n\'est pas valide.';
          }
          $gameDescription = Security::secureInput($formDatas['game_description']);
          // PEGI du jeu
          $pegiId = Security::secureInput($formDatas['pegi_id']);
          if (empty($pegiId)) {
            $errors['game_pegi'] = 'Veuillez sélectionner un PEGI.';
          }
          // Genres du jeu
          $genresId = [];
          foreach ($formDatas['genres_id'] as $genreId) {
            $genresId[] = Security::secureInput($genreId);
          }
          if (empty($genresId)) {
            $errors['game_genres'] = 'Veuillez sélectionner au moins un genre.';
          }
          // Formats autorisés pour les images
          $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'];
          $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
          $maxSize = 2 * 1024 * 1024; // 2 Mo
          // Image spotlight
          $spotlighToUpload = [];
          $spotlighToUpload = $filesDatas['game_spotlight'];
          if (empty($spotlighToUpload) && empty($spotlighToUpload['name'])) {
            $errors['game_spotlight'] = 'Veuillez ajouter au moins une image spotlight.';
          } else {
            if ($spotlighToUpload['error'] !== 0) {
              $errors['game_spotlight_error'] = 'Erreur lors de l\'upload de l\'image spotlight.';
            } else {
              if (!in_array($spotlighToUpload['type'], $allowedTypes)) {
                $errors['game_spotlight_format'] = 'Le format de l\'image spotlight n\'est pas autorisé.';
              }
              $fileExtension = strtolower(pathinfo($spotlighToUpload['name'], PATHINFO_EXTENSION));
              if (!in_array($fileExtension, $allowedExtensions)) {
                $errors['game_spotlight_extension'] = 'L\'extension de l\'image spotlight n\'est pas autorisée.';
              }
              if ($spotlighToUpload['size'] > $maxSize) {
                $errors['game_spotlight_size'] = 'L\'image spotlight est trop lourde.';
              }
              if (getimagesize($spotlighToUpload["tmp_name"]) === false) {
                $errors['game_spotlight_data'] = 'Le fichier spotlight n`\'est pas une image valide.';
              }
            }
          }
          // Image de présentation
          $presentationToUpload = [];
          $presentationToUpload = $filesDatas['game_presentation'];
          if (empty($presentationToUpload) && empty($presentationToUpload['name'])) {
            $errors['game_presentation'] = 'Veuillez ajouter au moins une image de présentation ou ne pas supprimer l\'existante.';
          } else {
            if ($presentationToUpload['error'] !== 0) {
              $errors['game_presentation_error'] = 'Erreur lors de l\'upload de l\'image de présentation.';
            } else {
              if (!in_array($presentationToUpload['type'], $allowedTypes)) {
                $errors['game_presentation_format'] = 'Le format de l\'image de présentation n\'est pas autorisé.';
              }
              $fileExtension = strtolower(pathinfo($presentationToUpload['name'], PATHINFO_EXTENSION));
              if (!in_array($fileExtension, $allowedExtensions)) {
                $errors['game_presentation_extension'] = 'L\'extension de l\'image de présentation n\'est pas autorisée.';
              }
              if ($presentationToUpload['size'] > $maxSize) {
                $errors['game_presentation_size'] = 'L\'image de présentation est trop lourde.';
              }
              if (getimagesize($presentationToUpload["tmp_name"]) === false) {
                $errors['game_presentation_data'] = 'Le fichier de présentation n`\'est pas une image valide.';
              }
            }
          }
          // Images de carousel
          $carouselImagesToUpload = [];
          if (!empty($filesDatas['game_carousel']) && !empty($filesDatas['game_carousel']['name'][0])) {
            foreach ($filesDatas['game_carousel'] as $key => $values) {
              foreach ($values as $index => $value) {
                if (!isset($carouselImagesToUpload[$index])) {
                  $carouselImagesToUpload[$index] = []; 
                }
                $carouselImagesToUpload[$index][$key] = $value;
              }
            }
          }
          if (count($carouselImagesToUpload) < 2) {
            $errors['game_carousel'] = 'Vous devez avoir au moins 2 images de carousel au total.';
          }
          if (!empty($carouselImagesToUpload)) {
            foreach ($carouselImagesToUpload as $index => $carouselImageToUpload) {
              if ($carouselImageToUpload['error'] !== 0) {
                $errors['game_carousel_error_' . $index] = 'Erreur lors de l\'upload de l\'image de carousel.';
              } else {
                if (!in_array($carouselImageToUpload['type'], $allowedTypes)) {
                  $errors['game_carousel_format_' . $index] = 'Le format de l\'image de carousel n\'est pas autorisé.';
                }
                $fileExtension = strtolower(pathinfo($carouselImageToUpload['name'], PATHINFO_EXTENSION));
                if (!in_array($fileExtension, $allowedExtensions)) {
                  $errors['game_carousel_extension'] = 'L\'extension de l\'image de carousel n\'est pas autorisée.';
                }
                if ($carouselImageToUpload['size'] > $maxSize) {
                  $errors['game_carousel_size_' . $index] = 'L\'image de carousel est trop lourde.';
                }
                if (getimagesize($carouselImageToUpload["tmp_name"]) === false) {
                  $errors['game_carousel_data'] = 'Le fichier de carousel n`\'est pas une image valide.';
                }
              }
            }
          }
          // Données spécifiques à la plateforme et au magasin
          $specificDatas = [];
          foreach ($formDatas as $key => $value) {
            if (preg_match('/^(\d+)-(\d+)-(price|new|reduced|discount|stock)$/', $key, $matches)) {
              $storeId = $matches[1];
              $platformId = $matches[2];
              $type = $matches[3];

              $found = false;
              foreach ($specificDatas as &$data) {
                if ($data['store_id'] == $storeId && $data['platform_id'] == $platformId) {
                  $found = true;
                  break;
                }
              }
              if (!$found) {
                $specificDatas[] = [
                  'store_id' => Security::secureInput($storeId),
                  'platform_id' => Security::secureInput($platformId),
                  'price' => 0,
                  'new' => 0,
                  'reduced' => 0,
                  'discount' => 0,
                  'stock' => 0
                ];
                $data = &$specificDatas[count($specificDatas) - 1];
              }
              if ($type === 'price' && $value == 0) {
                $data[$type] = null;
              } elseif (($type === 'new' || $type === 'reduced') && $value == 'on') {
                $data[$type] = 1;
              } else {
                $data[$type] = Security::secureInput($value);
              }
            }
          }
          $specificDatas = array_filter($specificDatas, function($entry) {
            return $entry['price'] !== null;
          });
          
          foreach ($specificDatas as &$specificData) {
            if ($specificData['discount'] > 0 && $specificData['reduced'] == 0) {
              $specificData['reduced'] = 1;
            } elseif ($specificData['discount'] == 0 && $specificData['reduced'] == 1) {
              $specificData['reduced'] = 0;
            }
          }
          unset($specificData);
          if (empty($specificDatas)) {
            $errors['game_platforms'] = 'Veuillez renseigner au moins un prix pour une plateforme.';
          }
          // Si aucune erreur, modification des données
          $gameId = 0;
          if (empty($errors)) {
            // Nom, description et PEGI en base de données
            $gameRepository = new GamesRepository();
            $gameId = $gameRepository->addGame($gameName, $gameDescription, $pegiId);
            if ($gameId == null) {
              throw new \Exception("Erreur lors de la modification des données du jeu.");
            }
            // Genres en base de données
            $gameGenreRepository = new GameGenreRepository();
            foreach ($genresId as $genreId) {
              $isGenreAdded = $gameGenreRepository->addGameGenre($gameId, $genreId);
              if (!$isGenreAdded) {
                throw new \Exception("Erreur lors de l'ajout des genres du jeu.");
              }
            }
            // Vérification de l'upload de la nouvelle image
            if (isset($spotlighToUpload["tmp_name"]) && is_uploaded_file($spotlighToUpload["tmp_name"])) {
              // Sécurisation du nom de l'image avec basename pour éviter les attaques de type LFI (Local File Inclusion) ou path traversal, mise en minuscules et ajout d'un identifiant unique pour éviter les doublons
              $extensionSpotlight = pathinfo($spotlighToUpload["name"], PATHINFO_EXTENSION);
              $spotlightnameWithoutExtensions = basename($spotlighToUpload["name"], '.'.$extensionSpotlight);
              $uniqueSpotlightImage = StringTools::slugify($spotlightnameWithoutExtensions);
              $uniqueSpotlightImage = 'spotlight-' . uniqid() . '-' . $uniqueSpotlightImage . '.' . $extensionSpotlight;
              // Déplacement du fichier uploadé vers le dossier des images de jeux
              if (!move_uploaded_file($spotlighToUpload["tmp_name"], dirname(__DIR__, 2) . '/uploads/games/' . $uniqueSpotlightImage)) {
                throw new \Exception("Erreur lors de l'upload de l'image spotlight.");
              }
              // Ajout de l'entrée en base de données
              $imageRepository = new ImageRepository();
              $isSpotlightAdded = $imageRepository->addGameImage($gameId, $uniqueSpotlightImage);
              if (!$isSpotlightAdded) {
                throw new \Exception("Erreur lors de l'ajout de l'image spotlight.");
              }
            } else {
              throw new \Exception("Erreur lors de l'upload de l'image spotlight.");
            }
            // Vérification de l'upload de la nouvelle image
            if (isset($presentationToUpload["tmp_name"]) && is_uploaded_file($presentationToUpload["tmp_name"])) {
              // Sécurisation du nom de l'image avec basename pour éviter les attaques de type LFI (Local File Inclusion) ou path traversal, mise en minuscules et ajout d'un identifiant unique pour éviter les doublons
              $extensionPresentation = pathinfo($presentationToUpload["name"], PATHINFO_EXTENSION);
              $presentationnameWithoutExtensions = basename($presentationToUpload["name"], '.'.$extensionPresentation);
              $uniquePresentationImage = StringTools::slugify($presentationnameWithoutExtensions);
              $uniquePresentationImage = 'presentation-' . uniqid() . '-' . $uniquePresentationImage . '.' . $extensionPresentation;
              // Déplacement du fichier uploadé vers le dossier des images de jeux
              if (!move_uploaded_file($presentationToUpload["tmp_name"], dirname(__DIR__, 2) . '/uploads/games/' . $uniquePresentationImage)) {
                throw new \Exception("Erreur lors de l'upload de l'image de présentation.");
              }
              // Ajout de l'entrée en base de données
              if (!$imageRepository) {
                $imageRepository = new ImageRepository();
              }
              $isPresentationAdded = $imageRepository->addGameImage($gameId, $uniquePresentationImage);
              if (!$isPresentationAdded) {
                throw new \Exception("Erreur lors de l'ajout de l'image de présentation.");
              }
            } else {
              throw new \Exception("Erreur lors de l'upload de l'image de présentation.");
            }
            // Si ajout d'une image de carousel, ajout de l'image sur serveur et en base de données
            if (!empty($carouselImagesToUpload)) {
              foreach ($carouselImagesToUpload as $carouselImageToUpload) {
                // Vérification de l'upload de la nouvelle image
                if (isset($carouselImageToUpload["tmp_name"]) && is_uploaded_file($carouselImageToUpload["tmp_name"])) {
                  // Sécurisation du nom de l'image avec basename pour éviter les attaques de type LFI (Local File Inclusion) ou path traversal, mise en minuscules et ajout d'un identifiant unique pour éviter les doublons
                  $extensionCarousel = pathinfo($carouselImageToUpload["name"], PATHINFO_EXTENSION);
                  $carouselnameWithoutExtensions = basename($carouselImageToUpload["name"], '.'.$extensionCarousel);
                  $uniqueCarouselImage = StringTools::slugify($carouselnameWithoutExtensions);
                  $uniqueCarouselImage = 'carousel-' . uniqid() . '-' . $uniqueCarouselImage . '.' . $extensionCarousel;
                  // Déplacement du fichier uploadé vers le dossier des images de jeux
                  if (!move_uploaded_file($carouselImageToUpload["tmp_name"], dirname(__DIR__, 2) . '/uploads/games/' . $uniqueCarouselImage)) {
                    throw new \Exception("Erreur lors de l'upload de l'image de carousel.");
                  }
                  // Ajout de l'entrée en base de données
                  if (!$imageRepository) {
                    $imageRepository = new ImageRepository();
                  }
                  $isCarouselAdded = $imageRepository->addGameImage($gameId, $uniqueCarouselImage);
                  if (!$isCarouselAdded) {
                    throw new \Exception("Erreur lors de l'ajout de l'image de carousel.");
                  }
                } else {
                  throw new \Exception("Erreur lors de l'upload de l'image de carousel.");
                }
              }
            }
            // Données spécifiques à la plateforme et au magasin en base de données
            $gamePlatformRepository = new GamePlatformRepository();
            foreach ($specificDatas as $specificData) {
              $isSpecificDatasUpdated = $gamePlatformRepository->addGameDatas($gameId, $specificData['platform_id'], $specificData['store_id'], $specificData['price'], $specificData['new'], $specificData['reduced'], ($specificData['discount'] / 100), $specificData['stock']);
              if (!$isSpecificDatasUpdated) {
                throw new \Exception("Erreur lors de la modification des données spécifiques du jeu.");
              }
            }
            header('Location: index.php?controller=admin&action=products');
            exit;      
          // Si erreur, renvoi vers la page de modification
          } else {
            $platformRepository = new PlatformRepository();
            $platforms = $platformRepository->getAllPlatforms();
            if (!$platforms) {
              throw new \Exception("Erreur lors de la récupération des plateformes.");
            }
            $storeRepository = new StoreRepository();
            $stores = $storeRepository->getAllStores();
            if (!$stores) {
              throw new \Exception("Erreur lors de la récupération des Gamestores.");
            }
            $genreRepositosry = new GenreRepository();
            $genres = $genreRepositosry->getAllGenres();
            if (!$genres) {
              throw new \Exception("Erreur lors de la récupération des genres.");
            }
            $pegiRepository = new PegiRepository();
            $pegis = $pegiRepository->getAllPegi();
            if (!$pegis) {
              throw new \Exception("Erreur lors de la récupération des Pegis.");
            }
            $this->render('admin/product', [
              'platforms' => $platforms,
              'stores' => $stores,
              'genres' => $genres,
              'pegis' => $pegis,
              'isModify' => false,
              'errors' => $errors
            ]);
          }  
        // Comportement par défaut au chargement de la page pour un ajout de jeu
        } else {
          $platformRepository = new PlatformRepository();
          $platforms = $platformRepository->getAllPlatforms();
          if (!$platforms) {
            throw new \Exception("Erreur lors de la récupération des plateformes.");
          }
          $storeRepository = new StoreRepository();
          $stores = $storeRepository->getAllStores();
          if (!$stores) {
            throw new \Exception("Erreur lors de la récupération des Gamestores.");
          }
          $genreRepositosry = new GenreRepository();
          $genres = $genreRepositosry->getAllGenres();
          if (!$genres) {
            throw new \Exception("Erreur lors de la récupération des genres.");
          }
          $pegiRepository = new PegiRepository();
          $pegis = $pegiRepository->getAllPegi();
          if (!$pegis) {
            throw new \Exception("Erreur lors de la récupération des Pegis.");
          }
          $this->render('admin/product', [
            'platforms' => $platforms,
            'stores' => $stores,
            'genres' => $genres,
            'pegis' => $pegis,
            'isModify' => false
          ]);
        }
      } else {
      throw new \Exception("Vous n'avez pas les droits pour accéder à cette page, veuillez vous connecter");
      }
    } catch (\Exception $e) {
      $this->render('admin/error', [
        'error' => $e->getMessage()
      ]);
    }
  }

}