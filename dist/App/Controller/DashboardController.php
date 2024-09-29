<?php

namespace App\Controller;

use App\Tools\Security;
use App\Repository\UserRepository;
use App\Tools\UserValidator;

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
        'error' => $e->getMessage() . "(Erreur : " . $e->getCode() . ")"
      ]);
    }
  }

  protected function home()
  {
    $this->render('dashboard/home');
  }

  protected function modify()
  {
    try {
      // Si modification des données personnelles
      if (isset($_POST['modifyUser'])) {
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
          $this->render('errors/default', [
            'error' => 'Veuillez vous connecter pour accéder à cette page.'
          ]);
        }
        // Si aucune erreur, modification des données
        if (empty($errors)) {
          $userRepository = new UserRepository();
          $user = $userRepository->updateUser($userId, $first_name, $last_name, $address, $postcode, $city);
          // Si mise en place en base de données réussie
          if ($user) {
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
            ];
            $this->render('dashboard/modify', [
              'user' => $user,
              'success' => 'Vos données personnelles ont bien été modifiées.'
            ]);
          } else {
            $this->render('errors/default', [
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
      } else if (isset($_POST['modifyStore'])) {
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
          $this->render('errors/default', [
            'error' => 'Veuillez vous connecter pour accéder à cette page.'
          ]);
        }
        // Si aucune erreur, modification des données
        if (empty($errors)) {
          $userRepository = new UserRepository();
          $user = $userRepository->updateUserStore($userId, $store_id);
          // Si mise en place en base de données réussie
          if ($user) {
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
            ];
            $this->render('dashboard/modify', [
              'user' => $user,
              'success' => 'Votre Gamestore a bien été modifié.'
            ]);
          } else {
            $this->render('errors/default', [
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
      } else if (isset($_POST['modifyPassword'])) {
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
          $this->render('errors/default', [
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
              $this->render('errors/default', [
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
          $this->render('errors/default', [
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

      $this->render('dashboard/cart');
    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage() . "(Erreur : " . $e->getCode() . ")"
      ]);
    }
  }

  protected function orders()
  {
    try {
      $this->render('dashboard/orders');
    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage() . "(Erreur : " . $e->getCode() . ")"
      ]);
    }
  }
  
}