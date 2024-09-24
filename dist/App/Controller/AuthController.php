<?php

namespace App\Controller;

require './vendor/autoload.php';

use App\Tools\Security;
use App\Tools\UserValidator;
use App\Repository\UserRepository;
use Dotenv\Dotenv;
use App\Repository\VerificationRepository;

$dotenv = new Dotenv(_ROOTPATH_);
$dotenv->load();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthController extends RoutingController
{

  public function route(): void
  {
    try {
      if (isset($_GET['action'])) {
        switch ($_GET['action']) {
          case 'login':
            $this->login();
            break;
          case 'logout':
            $this->logout();
            break;
          case 'check':
            $this->check();
            break;
          case 'password':
            $this->password();
            break;
          case 'reset':
            $this->reset();
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
        'error' => _ERORR_MESSAGE_ . "(Erreur : " . $e->getCode() . ")"
      ]);
    }
  }


  protected function login()
  {
    $errors = [];

    if (isset($_POST['loginUser'])) {
      if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
      }
      // Récupération des données
      $email = Security::secureEmail($_POST['email']);
      $password = Security::secureInput($_POST['password']);
      // Validation des données
      if (!UserValidator::validateEmail($email)) {
        $errors['email'] = 'L\'adresse mail n\'est pas valide';
      }
      if (!UserValidator::validatePassword($password)) {
        $errors['password'] = 'Le mot de passe n\'est pas valide';
      }
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
          $user = $userRepository->getUserByEmail($email);
          if ($user && Security::verifyPassword($password, $user->getPassword())) {
            // Si l'activation n'est pas effectuée, renvoi vers la page d'activation
            if ($user->getIs_verified() == 0) {
              header('Location: index.php?controller=user&action=activation&id=' . $user->getId());
              exit();
            } else {
              // Génération d'un code de vérification du mail
              $verification_code = random_int(100000, 999999);
              // Enregistrement du code de vérification en base de données
              $verificationRepository = new VerificationRepository();
              $verificationRepository->createVerification($verification_code, $user->getId());
              $verificationRepository->deleteAllExpiredCodes();
              // Envoi de l'utilisateur sur la page de vérification
              header('Location: index.php?controller=auth&action=check&id=' . $user->getId());
              exit();
            }
          } else {
            $errors['password_check'] = 'Email ou mot de passe incorrect, veuillez réessayer ou vous inscrire si vous n\'avez pas de compte';
          } 
        }
      }
    }
    $this->render('auth/login', [
      'errors' => $errors,
    ]);
  }

  protected function logout()
  {
    $this->render('auth/logout');
  }
  
  protected function check()
  {
    if (isset($_POST["authenticateUser"])) {
      if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
      }
      $userId = Security::secureInput($_POST['user_id']);
      $is_resend = true;
    }
    if (isset($_GET['id'])) {
      $userId = intval($_GET['id']);
      $is_resend = false;
    }
    try {
      $this->render('auth/check', [
        'is_resend' => $is_resend,
        'userId' => $userId
      ]);
    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => _ERORR_MESSAGE_ . "(Erreur : " . $e->getCode() . ")"
      ]);
    }
  }

  protected function password()
  {
    if (isset($_POST['password'])) {
      if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
      }
      $errors = [];
      $success = "";
      $email = Security::secureEmail($_POST['email']);
      if (!UserValidator::validateEmail($email)) {
        $errors['email'] = 'L\'adresse mail n\'est pas valide';
      }
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
          $user = $userRepository->getUserByEmail($email);
          $userName = $user->getFirst_name() . ' ' . $user->getLast_name();
          if ($user) {
            // Génération d'un token unique pour la réinitialisation du mot de passe
            $token = bin2hex(random_bytes(50));
            // Enregistrement du token en base de données
            $userRepository->setToken($token, $user->getId());
            $resetLink = $_SERVER['HTTP_ORIGIN'] . '/index.php?controller=auth&action=reset&token=' . $token;
            // Envoi du mail de réinitialisation
            $mail = new PHPMailer(true);
              try {
                // Configuration du serveur SMTP
                $mail->isSMTP();
                $mail->Host       = $_ENV['MAILER_HOST']; 
                $mail->SMTPAuth   = true;
                $mail->Username   = $_ENV['MAILER_EMAIL']; 
                $mail->Password   = $_ENV['MAILER_PASSWORD']; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = $_ENV['MAILER_PORT']; 

                // Paramètres de l'email
                $mail->CharSet = 'UTF-8';
                $mail->setFrom($_ENV['MAILER_EMAIL'], 'Gamestore');
                $mail->addAddress($email, $userName);
                $mail->isHTML(true);
                $mail->addEmbeddedImage(_ROOTPATH_ . '/assets/images/logo_png.png', 'logo_cid');
                $mail->Subject = "[Gamestore] Réinitialisation de votre mot de passe";
                $mail->Body = "
                  <html>
                  <head>
                      <style>
                          body {
                              font-family: Arial, sans-serif;
                              color: #333;
                          }
                          .header {
                              text-align: center;
                              margin-bottom: 20px;
                          }
                          .content {
                              margin: 0 20px;
                          }
                          .footer {
                              margin-top: 20px;
                              text-align: center;
                              font-size: 12px;
                              color: #777;
                          }
                      </style>
                  </head>
                  <body>
                      <div class='header'>
                          <img src='cid:logo_cid' alt='Logo Gamestore' style='width: 150px; height: auto;' />
                      </div>
                      <div class='content'>
                          <h1>Bonjour " . $userName . " !</h1>
                          <p>Nous avons reçu une demande de réinitialisation de votre mot de passe. Si c'est bien vous, cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :</p>
                          <a href='" . $resetLink . "'>" . $resetLink . "</a>
                          <p>Si vous n'avez pas demandé de réinitialisation de mot de passe, ignorez simplement cet email.</p>
                      </div>
                      <div class='footer'>
                          <p>Merci de faire partie de notre communauté !</p>
                      </div>
                  </body>
                  </html>
                  ";
                $mail->AltBody = "Bonjour " . $userName . " !\n\nNous avons reçu une demande de réinitialisation de votre mot de passe. Si c'est bien vous, cliquez sur le lien pour réinitialiser votre mot de passe : " . $resetLink . "\n\nSi vous n'avez pas demandé de réinitialisation de mot de passe, ignorez simplement cet email.\n\nMerci de faire partie de notre communauté !";
                // Envoyer l'email
                $mail->send();
                $success = "Le message a été envoyé avec succès ! Si vous ne recevez pas l'email dans quelques minutes, veuillez vérifier votre dossier de spam ou courrier indésirable.";
              } catch (Exception $e) {
                $errors['send'] = 'Le message n\'a pas pu être envoyé. Erreur: ' . $mail->ErrorInfo . '. Veuillez réessayer.';
              }
          } 
        }
      }
      $this->render('auth/password', [
        'errors' => $errors,
        'success' => $success
      ]);
    } else {
      $this->render('auth/password');
    }
  }

  protected function reset()
  {
    
  }

}