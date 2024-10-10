<?php

namespace App\Controller;

use App\Tools\Security;

class FormdatasController extends RoutingController
{

  protected function __construct()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST["verifyUser"])) {
        $this->verifyUser();
      } else if (isset($_POST["authenticateUser"])) {
        $this->authenticateUser();
      } 
    }
  }

  protected function verifyUser()
  {
    try {
      // Vérification du token CSRF
      Security::checkCSRF($_POST['csrf_token']);
      // Récupération des données du formulaire et sécurisation
      $userId = Security::secureInput($_POST['user_id']);
      $action = Security::secureInput($_POST['verifyUser']);
      if (isset($_POST['code_entered'])) {
        $enteredCode = Security::secureInput($_POST['code_entered']);
      } else {
        $enteredCode = '';
      }
      // Placement dans la session
      $_SESSION['verifyUser'] = [
        'userId' => $userId, 
        'action' => $action,
        'enteredCode' => $enteredCode
      ];
      // Redirection vers la page de vérification
      header('Location: /index.php?controller=user&action=activation');
    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage() 
      ]);
    }
  }

  protected function authenticateUser()
  {
    try {
      // Vérification du token CSRF
      Security::checkCSRF($_POST['csrf_token']);
      // Récupération des données du formulaire et sécurisation
      $userId = Security::secureInput($_POST['user_id']);
      $action = Security::secureInput($_POST['authenticateUser']);
      if (isset($_POST['code_entered'])) {
        $enteredCode = Security::secureInput($_POST['code_entered']);
      } else {
        $enteredCode = '';
      }
      // Placement dans la session
      $_SESSION['authenticateUser'] = [
        'userId' => $userId, 
        'action' => $action,
        'enteredCode' => $enteredCode
      ];
      // Redirection vers la page de connexion
      header('Location: /index.php?controller=auth&action=check');
    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage() 
      ]);
    }
  }

}