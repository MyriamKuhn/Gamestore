<?php

namespace App\Controller;

use App\Repository\GamePlatformRepository;
use App\Tools\Security;
use App\Repository\UserRepository;
use App\Tools\UserValidator;
use App\Repository\UserOrderRepository;
use App\Repository\GameUserOrderRepository;

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
      $this->render('dashboard/error', [
        'error' => $e->getMessage() . "(Erreur : " . $e->getCode() . ")"
      ]);
    }
  }

  protected function home()
  {
    try {
      if (!isset($_SESSION['user'])) {
        $this->render('dashboard/error', [
          'error' => 'Veuillez vous connecter pour accéder à cette page.'
        ]);
      }
      // Récupération du contenu du panier de l'utilisateur
      $cartId = $_SESSION['user']['cart_id'];
      if ($cartId === 0) {
        throw new \Exception("Erreur lors de la récupération de votre panier.");
      }
      $gameUserOrderRepository = new GameUserOrderRepository();
      $cartContent = $gameUserOrderRepository->findCartContent($cartId);
      $this->render('dashboard/home', [
        'cartContent' => $cartContent
      ]);
    } catch (\Exception $e) {
      $this->render('dashboard/error', [
        'error' => $e->getMessage() . "(Erreur : " . $e->getCode() . ")"
      ]);
    }
  }

  protected function modify()
  {
    try {
      // Si modification des données personnelles
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifyUser'])) {
        // Vérification du token CSRF
        Security::checkCSRF($_POST['csrf_token']);
        // Récupération des données du formulaire et sécurisation
        $last_name = Security::secureInput($_POST['last_name']);
        $first_name = Security::secureInput($_POST['first_name']);
        $address = Security::secureInput($_POST['address']);
        $postcode = Security::secureInput($_POST['postcode']);
        $city = Security::secureInput($_POST['city']);
        $userId = Security::getCurrentUserId();
        // Vérification des données
        $errors = [];
        UserValidator::validateLastName($last_name) ?: $errors['last_name'] = 'Le champ nom n\'est pas valide';
        UserValidator::validateFirstName($first_name) ?: $errors['first_name'] = 'Le champ prénom n\'est pas valide';
        UserValidator::validateAddress($address) ?: $errors['address'] = 'Le champ adresse n\'est pas valide';
        UserValidator::validatePostcode($postcode) ?: $errors['postcode'] = 'Le champ code postal n\'est pas valide';
        UserValidator::validateCity($city) ?: $errors['city'] = 'Le champ ville n\'est pas valide';
        if ($userId === false) {
          $this->render('dashboard/error', [
            'error' => 'Veuillez vous connecter pour accéder à cette page.'
          ]);
        }
        // Si aucune erreur, modification des données
        if (empty($errors)) {
          $userRepository = new UserRepository();
          $user = $userRepository->updateUser($userId, $first_name, $last_name, $address, $postcode, $city);
          // Si mise en place en base de données réussie
          if ($user) {
            // Mise à jour du panier de l'utilisateur
            $userOrderRepository = new UserOrderRepository();
            $cartId = $userOrderRepository->findCartId($userId);
            if ($cartId === 0) {
              $isCardCreated = $userOrderRepository->createEmptyCart($userId, $user->getFk_store_id());
              if (!$isCardCreated) {
                throw new \Exception("Erreur lors de la création de votre panier.");
              } else {
                $cartId = $userOrderRepository->findCartId($userId);
              }
            }
            if ($cartId === 0) {
              throw new \Exception("Erreur lors de la création de votre panier.");
            }
            // Régénère l'identifiant de session pour éviter les attaques de fixation de session (vol de cookie de session)
            session_regenerate_id(true);
            // Mise à jour des données de session
            $_SESSION['user'] = [
              'id' => $user->getId(),
              'first_name' => $user->getFirst_name(),
              'last_name' => $user->getLast_name(),
              'role' => $user->getRole(),
              'store_id' => $user->getFk_store_id(),
              'cart_id' => $cartId
            ];
            $this->render('dashboard/modify', [
              'user' => $user,
              'success' => 'Vos données personnelles ont bien été modifiées.'
            ]);
          } else {
            $this->render('dashboard/error', [
              'error' => 'Erreur lors de la modification de vos données personnelles.'
            ]);
          }
        } else {
          $userRepository = new UserRepository();
          $user = $userRepository->getUserById($userId);
          if (!$user) {
            throw new \Exception("Erreur lors de la récupération de vos données personnelles.");
          } else {
            $this->render('dashboard/modify', [
              'user' => $user,
              'errors' => $errors
            ]);
          }
        }
      // Si modification du Gamestore le plus proche
      } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifyStore'])) {
        // Vérification du token CSRF
        Security::checkCSRF($_POST['csrf_token']);
        // Récupération des données du formulaire et sécurisation
        $store_id = Security::secureInput($_POST['nearest_store']);
        $userId = Security::getCurrentUserId();
        // Vérification des données
        $errors = [];
        if (empty($store_id)) {
          $errors['store_id'] = 'Veuillez sélectionner un Gamestore.';
        }
        if ($userId === false) {
          $this->render('dashboard/error', [
            'error' => 'Veuillez vous connecter pour accéder à cette page.'
          ]);
        }
        // Si aucune erreur, modification des données
        if (empty($errors)) {
          $userRepository = new UserRepository();
          $user = $userRepository->updateUserStore($userId, $store_id);
          // Si mise en place en base de données réussie
          if ($user) {
            // Mise à jour du panier de l'utilisateur
            $userOrderRepository = new UserOrderRepository();
            $cartId = $userOrderRepository->findCartId($userId);
            if ($cartId === 0) {
              $isCardCreated = $userOrderRepository->createEmptyCart($userId, $store_id);
              if (!$isCardCreated) {
                throw new \Exception("Erreur lors de la création de votre panier.");
              } else {
                $cartId = $userOrderRepository->findCartId($userId);
              }
            } else {
              // Supprimer le panier actuel si l'utilisateur change de Gamestore et créer un nouveau panier
              $isDeleted = $userOrderRepository->deleteCart($cartId);
              if (!$isDeleted) {
                throw new \Exception("Erreur lors de la suppression de votre panier actuel.");
              }
              $isCardCreated = $userOrderRepository->createEmptyCart($userId, $store_id);
              if (!$isCardCreated) {
                throw new \Exception("Erreur lors de la création de votre nouveau panier.");
              } else {
                $cartId = $userOrderRepository->findCartId($userId);
              }
            }
            if ($cartId === 0) {
              throw new \Exception("Erreur lors de la création de votre nouveau panier.");
            }
            // Régénère l'identifiant de session pour éviter les attaques de fixation de session (vol de cookie de session)
            session_regenerate_id(true);
            // Mise à jour des données de session
            $_SESSION['user'] = [
              'id' => $user->getId(),
              'email' => $user->getEmail(),
              'first_name' => $user->getFirst_name(),
              'last_name' => $user->getLast_name(),
              'role' => $user->getRole(),
              'store_id' => $user->getFk_store_id(),
              'cart_id' => $cartId
            ];
            $this->render('dashboard/modify', [
              'user' => $user,
              'success' => 'Votre Gamestore a bien été modifié.'
            ]);
          } else {
            $this->render('dashboard/error', [
              'error' => 'Erreur lors de la modification de votre Gamestore.'
            ]);
          }
        } else {
          $userRepository = new UserRepository();
          $user = $userRepository->getUserById($userId);
          if (!$user) {
            throw new \Exception("Erreur lors de la récupération de vos données personnelles.");
          } else {
            $this->render('dashboard/modify', [
              'user' => $user,
              'errors' => $errors
            ]);
          }
        }
      // Si modification du mot de passe
      } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifyPassword'])) {
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
          $this->render('dashboard/error', [
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
              $this->render('dashboard/modify', [
                'user' => $user,
                'success' => 'Votre mot de passe a bien été modifié.'
              ]);
            } else {
              $this->render('dashboard/error', [
                'error' => 'Erreur lors de la modification de votre mot de passe.'
              ]);
            }
          } else {
            $userRepository = new UserRepository();
            $user = $userRepository->getUserById($userId);
            if (!$user) {
              throw new \Exception("Erreur lors de la récupération de vos données personnelles.");
            } else {
              $this->render('dashboard/modify', [
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
            $this->render('dashboard/modify', [
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
            $this->render('dashboard/modify', [
              'user' => $user
            ]);
          }
        } else {
          $this->render('dashboard/error', [
            'error' => 'Veuillez vous connecter pour accéder à cette page.'
          ]);
        }
      }
    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage() . "(Erreur : " . $e->getCode() . ")"
      ]);
    }
  }

  protected function cart()
  {
    try {
      // Si modification de la quantité dans le panier
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateQuantity'])) {
        // Vérification du token CSRF
        Security::checkCSRF($_POST['csrf_token']);
        // Vérification sur l'utilisateur connecté
        if (!isset($_SESSION['user'])) {
          throw new \Exception("Veuillez vous connecter pour accéder à cette page.");
        }
        // Récupération des données du formulaire
        $gameId = Security::secureInput($_POST['game_id']);
        $platformId = Security::secureInput($_POST['platform_id']);
        $price_at_order = Security::secureInput($_POST['price_at_order']);
        $quantity = Security::secureInput($_POST['quantity']); 
        // Vérification des données
        if (empty($gameId) || empty($platformId)) {
          throw new \Exception("Erreur lors de la modification, veuillez retenter.");
        }
        // Récupération de l'identifiant du panier de l'utilisateur
        $cartId = $_SESSION['user']['cart_id'];
        if ($cartId === 0) {
          throw new \Exception("Erreur lors de la récupération de votre panier.");
        }
        $gameUserOrderRepository = new GameUserOrderRepository();
        // Si ajout d'une quantité du panier
        if ($_POST['updateQuantity'] === 'increase') {
          // Vérification du stock du jeu
          $storeId = $_SESSION['user']['store_id'];
          $gamePlatformRepository = new GamePlatformRepository();
          $stock = $gamePlatformRepository->checkGameStock($gameId, $platformId, $storeId);
          if ($stock === 0) {
            throw new \Exception("Le jeu est en rupture de stock.");
          } else if (($quantity + 1) > $stock) {
            throw new \Exception("Le stock du jeu est insuffisant.");
          } else {
            $isAdded = $gameUserOrderRepository->addGameInCart($gameId, $platformId, $cartId, 1, $price_at_order, 'add');
            if (!$isAdded) {
              throw new \Exception("Erreur lors de l'ajout de la quantité.");
            } else {
              header('Location: index.php?controller=dashboard&action=cart');
              exit();
            }
          }
        } else if ($_POST['updateQuantity'] === 'decrease') {
          $isRemoved = $gameUserOrderRepository->addGameInCart($gameId, $platformId, $cartId, 1, $price_at_order, 'remove');
          if (!$isRemoved) {
            throw new \Exception("Erreur lors de la suppression de la quantité.");
          } else {
            header('Location: index.php?controller=dashboard&action=cart');
            exit();
          }
        } else {
          throw new \Exception("Erreur lors de la modification, veuillez retenter.");
        }
      // Si suppression d'un jeu complet du panier
      } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteGame'])) {
        // Vérification du token CSRF
        Security::checkCSRF($_POST['csrf_token']);
        // Vérification sur l'utilisateur connecté
        if (!isset($_SESSION['user'])) {
          throw new \Exception("Veuillez vous connecter pour accéder à cette page.");
        }
        // Récupération des données du formulaire
        $gameId = Security::secureInput($_POST['game_id']);
        $platformId = Security::secureInput($_POST['platform_id']);
        // Récupération de l'identifiant du panier de l'utilisateur
        $cartId = $_SESSION['user']['cart_id'];
        if ($cartId === 0) {
          throw new \Exception("Erreur lors de la récupération de votre panier.");
        }
        // Vérification des données
        if (empty($gameId) || empty($platformId)) {
          throw new \Exception("Erreur lors de la suppression, veuillez retenter.");
        }
        $gameUserOrderRepository = new GameUserOrderRepository();
        $isDeleted = $gameUserOrderRepository->removeGameFromCart($gameId, $platformId, $cartId);
        if (!$isDeleted) {
          throw new \Exception("Erreur lors de la suppression du jeu.");
        } else {
          header('Location: index.php?controller=dashboard&action=cart');
          exit();
        }
      // Si validation du panier
      } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['validateCart'])) {
        // Vérification du token CSRF
        Security::checkCSRF($_POST['csrf_token']);
        // Vérification sur l'utilisateur connecté
        if (!isset($_SESSION['user'])) {
          throw new \Exception("Veuillez vous connecter pour accéder à cette page.");
        }
        // Récupération des données du formulaire
        $cartId = $_SESSION['user']['cart_id'];
        if ($cartId === 0) {
          throw new \Exception("Erreur lors de la récupération de votre panier.");
        }
        $userId = $_SESSION['user']['id'];
        $storeId = $_SESSION['user']['store_id'];
        $gameUserOrderRepository = new GameUserOrderRepository();
        $cartContent = $gameUserOrderRepository->findCartContent($cartId);
        if (!$cartContent) {
          throw new \Exception("Votre panier est vide.");
        }
        // Vérification si le panier est tjs valide et qu'il n'y a pas de jeux en rupture de stock
        $gamePlatformRepository = new GamePlatformRepository();
        foreach ($cartContent as $content) {
          $stock = $gamePlatformRepository->checkGameStock($content['game_id'], $content['platform_id'], $storeId);
          if ($content['quantity'] === 0) {
            $isDeleted = $gameUserOrderRepository->removeGameFromCart($content['game_id'], $content['platform_id'], $cartId);
            if (!$isDeleted) {
              throw new \Exception("Erreur lors de la suppression du jeu.");
            }
          } else if ($stock === 0) {
            $isDeleted = $gameUserOrderRepository->removeGameFromCart($content['game_id'], $content['platform_id'], $cartId);
            if (!$isDeleted) {
              throw new \Exception("Erreur lors de la suppression du jeu.");
            }
          } else if ($content['quantity'] > $stock) {
            $quantity = $content['quantity'] - $stock;
            $isRemoved = $gameUserOrderRepository->addGameInCart($content['game_id'], $content['platform_id'], $cartId, $quantity, $content['price'], 'remove');
            if (!$isRemoved) {
              throw new \Exception("Erreur lors de la suppression de la quantité.");
            }
          }
        }
        // Récupération du contenu du panier mis à jour
        $cartContent = $gameUserOrderRepository->findCartContent($cartId);
        if (!$cartContent) {
          throw new \Exception("Votre panier est vide.");
        }
        // Vérification de la date
        $orderDate = new \DateTime();
        $pickupDate = new \DateTime($_POST['pickupDate']);
        $diff = $orderDate->diff($pickupDate);
        if ($diff->days > 7) {
          throw new \Exception("La date de retrait doit être inférieure à 7 jours.");
        }
        // Vérification que la date de retrait est un jour d'ouverture du magasin
        if ($pickupDate->format('N') === '1' || $pickupDate->format('N') === '7') {
          throw new \Exception("Le magasin est fermé le lundi et le dimanche.");
        }
        // Création de la commande
        $userOrderRepository = new UserOrderRepository();
        $isOrderCreated = $userOrderRepository->validateOrder($cartId, $pickupDate);
        if (!$isOrderCreated) {
          throw new \Exception("Erreur lors de la validation de votre commande.");
        }
        // Création d'un nouveau panier vide
        $isCartCreated = $userOrderRepository->createEmptyCart($userId, $storeId);
        if (!$isCartCreated) {
          throw new \Exception("Erreur lors de la création de votre nouveau panier.");
        }
        // Mise à jour du panier de l'utilisateur
        $cartId = $userOrderRepository->findCartId($userId);
        if ($cartId === 0) {
          throw new \Exception("Erreur lors de la récupération de votre panier.");
        }
        // Régénère l'identifiant de session pour éviter les attaques de fixation de session (vol de cookie de session)
        session_regenerate_id(true);
        // Mise à jour des données de session
        $_SESSION['user']['cart_id'] = $cartId;
        header('Location: index.php?controller=dashboard&action=orders');
        exit();
      // Au chargement de la page du panier
      } else {
        if (!isset($_SESSION['user'])) {
          $this->render('dashboard/error', [
            'error' => 'Veuillez vous connecter pour accéder à cette page.'
          ]);
        }
        $cartId = $_SESSION['user']['cart_id'];
        if ($cartId === 0) {
          throw new \Exception("Erreur lors de la récupération de votre panier.");
        }
        $storeId = $_SESSION['user']['store_id'];
        if ($storeId === 0) {
          throw new \Exception("Erreur lors de la récupération de votre Gamestore.");
        }
        $gameUserOrderRepository = new GameUserOrderRepository();
        $cartContent = $gameUserOrderRepository->findCartContent($cartId);
        if (!$cartContent) {
          throw new \Exception("Votre panier est vide.");
        }
        // Vérifier si le panier est tjs valide et qu'il n'y a pas de jeux en rupture de stock
        $gamePlatformRepository = new GamePlatformRepository();
        foreach ($cartContent as $content) {
          $stock = $gamePlatformRepository->checkGameStock($content['game_id'], $content['platform_id'], $storeId);
          if ($content['quantity'] === 0) {
            $isDeleted = $gameUserOrderRepository->removeGameFromCart($content['game_id'], $content['platform_id'], $cartId);
            if (!$isDeleted) {
              throw new \Exception("Erreur lors de la suppression du jeu.");
            }
          } else if ($stock === 0) {
            $isDeleted = $gameUserOrderRepository->removeGameFromCart($content['game_id'], $content['platform_id'], $cartId);
            if (!$isDeleted) {
              throw new \Exception("Erreur lors de la suppression du jeu.");
            }
          } else if ($content['quantity'] > $stock) {
            $quantity = $content['quantity'] - $stock;
            $isRemoved = $gameUserOrderRepository->addGameInCart($content['game_id'], $content['platform_id'], $cartId, $quantity, $content['price'], 'remove');
            if (!$isRemoved) {
              throw new \Exception("Erreur lors de la suppression de la quantité.");
            }
          }
        }
        // Récupération du contenu du panier mis à jour
        $cartContent = $gameUserOrderRepository->findCartContent($cartId);
        if (!$cartContent) {
          throw new \Exception("Votre panier est vide.");
        }
        $this->render('dashboard/cart', [
          'cartContent' => $cartContent
        ]);
      }
    } catch (\Exception $e) {
      $this->render('dashboard/error', [
        'error' => $e->getMessage() . "(Erreur : " . $e->getCode() . ")"
      ]);
    }
  }

  protected function orders()
  {
    try {
      $this->render('dashboard/orders');
    } catch (\Exception $e) {
      $this->render('dashboard/error', [
        'error' => $e->getMessage() . "(Erreur : " . $e->getCode() . ")"
      ]);
    }
  }
  
}