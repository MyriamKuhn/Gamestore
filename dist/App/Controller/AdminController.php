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

}