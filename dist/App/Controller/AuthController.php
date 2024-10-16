<?php

namespace App\Controller;

require _ROOTPATH_ . '/vendor/autoload.php';

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
        'error' => $e->getMessage() 
      ]);
    }
  }


  protected function login()
  {
    try {
      $errors = [];

      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loginUser'])) {
        // Vérification du token CSRF
        Security::checkCSRF($_POST['csrf_token']);
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
        // Vérification si l'utilisateur est bloqué
        $userRepository = new UserRepository();
        $user = $userRepository->getUserByEmail($email);
        if ($user && $user->getIs_blocked() == 1) {
          $errors['blocked'] = 'Votre compte a été bloqué. Veuillez contacter un administrateur pour plus d\'informations.';
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
            if ($user && Security::verifyPassword($password, $user->getPassword())) {
              // Si l'activation n'est pas effectuée, renvoi vers la page d'activation
              if ($user->getIs_verified() == 0) {
                // Pour les employés à leur première connexion forcer le changement de mot de passe
                if ($user->getRole() == 'employe') {
                  header('Location: /index.php?controller=auth&action=reset&id=' . $user->getId());
                  exit();
                } else {
                header('Location: /index.php?controller=user&action=activation&id=' . $user->getId());
                exit();
                }
              } else {
                // Génération d'un code de vérification du mail
                $verification_code = random_int(100000, 999999);
                // Enregistrement du code de vérification en base de données
                $verificationRepository = new VerificationRepository();
                $verificationRepository->createVerification($verification_code, $user->getId());
                $verificationRepository->deleteAllExpiredCodes();
                // Envoi de l'utilisateur sur la page de vérification
                header('Location: /index.php?controller=auth&action=check&id=' . $user->getId());
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
    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage() 
      ]);
    }
  }

  protected function logout()
  {
    try {
      $this->render('auth/logout');
    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage() 
      ]);
    }
  }
  
  protected function check()
  {
    try {
      if (!empty($_SESSION['authenticateUser'])) {
        $datas = $_SESSION['authenticateUser'];
        $userId = Security::secureInput($datas['userId']);
        $action = Security::secureInput($datas['action']);
        $enteredCode = Security::secureInput($datas['enteredCode']);
        unset($_SESSION['verifyUser']);
        $is_resend = true;
      }
      if (isset($_GET['id'])) {
        $userId = intval($_GET['id']);
        $is_resend = false;
        $action = '';
        $enteredCode = '';
      }

      $this->render('auth/check', [
        'is_resend' => $is_resend,
        'userId' => $userId,
        'action' => $action,
        'enteredCode' => $enteredCode
      ]);
    } catch (\Exception $e) {
      $this->render('errors/default', [
        'error' => $e->getMessage() 
      ]);
    }
  }

  protected function password()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
      // Vérification du token CSRF
      Security::checkCSRF($_POST['csrf_token']);
      $errors = [];
      $success = "";
      $email = Security::secureEmail($_POST['email']);
      if (!UserValidator::validateEmail($email)) {
        $errors['email'] = 'L\'adresse mail n\'est pas valide';
      }
      // Vérification si l'utilisateur est bloqué
      $userRepository = new UserRepository();
      $user = $userRepository->getUserByEmail($email);
      if ($user && $user->getIs_blocked() == 1) {
        $errors['blocked'] = 'Votre compte a été bloqué. Veuillez contacter un administrateur pour plus d\'informations.';
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
        // Si la vérification CAPTCHA est réussie et si aucune erreur n'est détectée, envoi du mail
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
                header('refresh:5;url=/index.php?controller=auth&action=login');
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
    ob_start(); 
    // A l'ouverture du lien de réinitialisation de mot de passe
    if (isset($_GET['token']) && empty($_POST['resetPassword'])) {
      $token = Security::secureInput($_GET['token']);
      $userRepository = new UserRepository();
      $tokenCheck = $userRepository->checkToken($token);
      if ($tokenCheck) {
        $this->render('auth/reset', [
          'token' => $token
        ]);
      } else {
        $this->render('errors/default', [
          'error' => 'Votre demande de réinitialisation de mot de passe a expiré. Veuillez recommencer la procédure.'
      ]);
      }
    // A la validation du formulaire pour modifier le mot de passe
    } else if (isset($_POST['resetPassword'])) {
      // Vérification du token CSRF
      Security::checkCSRF($_POST['csrf_token']);
      $errors = [];
      $password = Security::secureInput($_POST['password']);
      $token = Security::secureInput($_POST['token']);
      if (!UserValidator::validatePassword($password)) {
        $errors['password'] = 'Le mot de passe n\'est pas valide';
      }
      if (empty($errors)) {
        $userRepository = new UserRepository();
        $isReset = $userRepository->resetPassword($password, $token);
        if ($isReset) {
          // Si c'est un employé lors de sa première connexion, on force le changement de mot de passe
          if (strpos($token, '3m47013t0k3n-') !== false) {
            $tokenParts = explode('-', $token);
            $userId = intval($tokenParts[1]);
            $user = $userRepository->getUserById($userId);
            $isVerified = $userRepository->updateUserStatus($user);
            if ($isVerified) {
              $this->render('auth/reset', [
                'success' => 'Votre mot de passe a été réinitialisé avec succès ! Votre compte a été activé. Vous serez redirigé vers la page de connexion dans 10 secondes.',
                'token' => $token
              ]);
              header('refresh:10;url=/index.php?controller=auth&action=login');
              exit();
            } else {
              $this->render('errors/default', [
                'error' => 'Erreur lors de la réinitialisation de votre mot de passe. Veuillez réessayer.'
              ]);
            }
          } else {
            $this->render('auth/reset', [
              'success' => 'Votre mot de passe a été réinitialisé avec succès ! Vous serez redirigé vers la page de connexion dans 10 secondes.',
              'token' => $token
            ]);
            header('refresh:10;url=/index.php?controller=auth&action=login');
            exit();
          }
        } else {
          $this->render('errors/default', [
            'error' => 'Erreur lors de la réinitialisation de votre mot de passe. Veuillez réessayer.'
          ]);
        }
      } else {
        $this->render('auth/reset', [
          'errors' => $errors,
          'token' => $token
        ]);
      }
    // Si c'est un employé lors de sa première connexion, on force le changement de mot de passe
    } else if (isset($_GET['id'])) {
      $userRepository = new UserRepository();
      $user = $userRepository->getUserById(intval($_GET['id']));
      if ($user->getRole() == 'employe') {
        $userId = intval($_GET['id']);
        // Génération d'un token unique pour la réinitialisation du mot de passe
        $token = '3m47013t0k3n-' . intval($_GET['id']);
        $userRepository->setToken($token, $userId);
        $this->render('auth/reset', [
          'token' => $token
        ]);
      } else {
        $this->render('errors/default', [
          'error' => 'Vous n\'avez pas les droits pour accéder à cette page.'
        ]);
      }
    } else {
      $this->render('errors/default', [
        'error' => 'Votre demande de réinitialisation de mot de passe est invalide. Veuillez recommencer'
      ]);
    }
    ob_end_flush();
  }

}

