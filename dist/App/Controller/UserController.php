<?php

namespace App\Controller;

require './vendor/autoload.php';

use App\Repository\UserRepository;
use App\Model\User;
use App\Tools\UserValidator;
use App\Tools\Security;
use App\Repository\StoreRepository;
use Dotenv\Dotenv;



class UserController extends RoutingController
{
  public function route(): void
  {
    try {
      if (isset($_GET['action'])) {
        switch ($_GET['action']) {
          case 'register':
            $this->register();
            break;
          case 'delete':
            // Appeler méthode delete()
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
        'error' => $e->getMessage()
      ]);
    }
  }

  protected function register()
  {
    try {
      $errors = [];
      $user = new User();

        if (isset($_POST['registerUser'])) {
          // Récupération des données du formulaire et sécurisation
          $first_name = Security::secureInput($_POST['first_name']);
          $last_name = Security::secureInput($_POST['last_name']);
          $address = Security::secureInput($_POST['address']);
          $postcode = Security::secureInput($_POST['postcode']);
          $city = Security::secureInput($_POST['city']);
          $email = Security::secureEmail($_POST['email']);
          $password = Security::secureInput($_POST['password']);
          $nearest_store_address = Security::secureInput($_POST['nearest_store']);
          // Récupération de la ville du magasin le plus proche (2ème élément de la chaine envoyée par le formulaire)
          $nearest_store_name = explode(',', $nearest_store_address);
          $nearest_store_city = explode(' ', trim($nearest_store_name[0]));
          $nearest_store = $nearest_store_city[1];
          var_dump($nearest_store);
          // Récupération de l'ID du magasin le plus proche
          $storeRepository = new StoreRepository();
          $store = $storeRepository->getStoreIdByName($nearest_store);
          // Hydratation de l'objet User
          $user->setFirst_name($first_name);
          $user->setLast_name($last_name);
          $user->setAddress($address);
          $user->setPostcode($postcode);
          $user->setCity($city);
          $user->setEmail($email);
          $user->setPassword($password);
          $user->setRole(_ROLE_USER_);
          $user->setFk_store_id($store);
          // Validation des données
          $errors = UserValidator::validate($user);
          // Permettre l'utilisation des variables d'environnement
          $dotenv = new Dotenv(_ROOTPATH_);
          $dotenv->load();
          // Clé secrète reCAPTCHA
          $recaptchaSecret = $_ENV['SITE_RECAPTCHA_SECRET'];
          // Vérification du reCAPTCHA
          $recaptchaResponse = $_POST['g-recaptcha-response'];
          $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
          $response = file_get_contents($recaptchaUrl . '?secret=' . $recaptchaSecret . '&response=' . $recaptchaResponse);
          $responseKeys = json_decode($response, true);
          if (intval($responseKeys["success"]) !== 1) {
            $errors['captcha'] = 'Échec de la vérification reCAPTCHA. Veuillez réessayer';
          } else {
            // Si la vérification CAPTCHA est réussie et si aucune erreur n'est détectée, enregistrement en base de données
            if (empty($errors)) {
              $userRepository = new UserRepository();
              $userRepository->addUser($user);
              header('Location: index.php');
            }
          }
        }
      $this->render('user/register', [
        'errors' => $errors
      ]);

    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage()
      ]);
    } 
  }

}
