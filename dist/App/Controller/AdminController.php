<?php

namespace App\Controller;

use App\Tools\Security;
use App\Tools\UserValidator;
use App\Repository\UserRepository;

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

}