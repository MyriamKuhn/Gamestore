<?php
ob_start(); 

require './vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Dotenv\Dotenv;

use App\Tools\Security;
use App\Repository\UserRepository;
use App\Repository\VerificationRepository;
use App\Repository\UserOrderRepository;

$dotenv = new Dotenv(_ROOTPATH_);
$dotenv->load();

$userRepository = new UserRepository();
$user = $userRepository->getUserById($userId);

require_once _TEMPLATEPATH_ . '/header.php'; 

?>

<!-- START : Main -->
<main class="container my-4 main">
  <section class="mt-2">
    <div class="d-flex justify-content-between gamestore-title">
      <h2 class="text-uppercase">Authenfication</h2>
    </div>
    <div class="my-3">
      <p class="text-uppercase title-show">Bienvenue <?= $user->getFirst_name() . ' ' . $user->getLast_name() ?></p>
      <p>Veuillez générer un code que nous vous enverrons par mail puis saisir ce code ci-dessous afin de vous authentifier.</p>

      <?php
        // Vérifier si le formulaire a été soumis
        if (isset($_POST["authenticateUser"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
          // Vérification du token CSRF
          Security::checkCSRF($_POST['csrf_token']);
          switch ($_POST["authenticateUser"]) {
            case 'Envoyer le code':
              // Récupération des données du formulaire
              $userEmail = $user->getEmail();
              $userName = $user->getFirst_name() . ' ' . $user->getLast_name();
              $verificationRepository = new VerificationRepository();
              $verification = $verificationRepository->getLastVerificationByUserId($userId);
              $verificationCode = $verification ? $verification->getVerification_code() : null;
              // Si le code est déjà expiré ou inexistant, en générer un nouveau
              if ($verificationCode == null) {
                $verificationCode = rand(100000, 999999);
                $verificationCode = $verificationRepository->createVerification($verificationCode, $userId);
              } 
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
                $mail->addAddress($userEmail, $userName);
                $mail->isHTML(true);
                $mail->addEmbeddedImage(_ROOTPATH_ . '/assets/images/logo_png.png', 'logo_cid');
                $mail->Subject = "[Gamestore] Votre code d'activation";
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
                        .code {
                            font-size: 24px;
                            font-weight: bold;
                            color: #007bff; /* Couleur du code d'activation */
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
                        <h1>Bienvenue chez Gamestore !</h1>
                        <p>Voici votre code d'activation :</p>
                        <p class='code'>" . $verificationCode . "</p>
                    </div>
                    <div class='footer'>
                        <p>Merci de faire partie de notre communauté !</p>
                    </div>
                </body>
                </html>
                ";
                $mail->AltBody = "Bienvenue chez Gamestore !\n\nVoici votre code d'activation : " . $verificationCode . ".";

                // Envoyer l'email
                $mail->send();
                echo '<div class="alert alert-success py-5 my-5">Le message a été envoyé avec succès ! <br> Si vous ne recevez pas l\'email dans quelques minutes, veuillez vérifier votre dossier de spam ou courrier indésirable.</div>';
              } catch (Exception $e) {
                echo '<div class="alert alert-danger py-5 my-5">Le message n\'a pas pu être envoyé. Erreur: ' . $mail->ErrorInfo . '</div>';
              }
              break;

            case 'Renvoyer le code':
              // Récupération des données du formulaire
              $userEmail = $user->getEmail();
              $userName = $user->getFirst_name() . ' ' . $user->getLast_name();
              $verificationRepository = new VerificationRepository();
              $verification = $verificationRepository->getLastVerificationByUserId($userId);
              $verificationCode = $verification ? $verification->getVerification_code() : null;
              // Si le code est déjà expiré ou inexistant, en générer un nouveau
              if ($verificationCode == null) {
                $verificationCode = rand(100000, 999999);
                $verificationCode = $verificationRepository->createVerification($verificationCode, $userId);
              }
              // Si renvoi du code
              $lastSent = $verification->getCreated_at();
              $lastSentDateTime = new DateTime($lastSent);
              $now = new DateTime();
              $limit = (clone $now)->modify('-5 minutes');
              if ($verification && $lastSentDateTime > $limit) {
                echo '<div class="alert alert-danger py-5 my-5">Veuillez patienter avant de renvoyer un nouveau code.</div>';
              } else  {
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
                  $mail->addAddress($userEmail, $userName);
                  $mail->isHTML(true);
                  $mail->addEmbeddedImage(_ROOTPATH_ . '/assets/images/logo_png.png', 'logo_cid');
                  $mail->Subject = "[Gamestore] Votre code d'activation";
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
                            .code {
                                font-size: 24px;
                                font-weight: bold;
                                color: #007bff; /* Couleur du code d'activation */
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
                            <h1>Bienvenue chez Gamestore !</h1>
                            <p>Voici votre code d'activation :</p>
                            <p class='code'>" . $verificationCode . "</p>
                        </div>
                        <div class='footer'>
                            <p>Merci de faire partie de notre communauté !</p>
                        </div>
                    </body>
                    </html>
                    ";
                  $mail->AltBody = "Bienvenue chez Gamestore !\n\nVoici votre code d'activation : " . $verificationCode . ".";

                  // Envoyer l'email
                  $mail->send();
                  echo '<div class="alert alert-success py-5 my-5">Le message a été envoyé avec succès ! <br> Si vous ne recevez pas l\'email dans quelques minutes, veuillez vérifier votre dossier de spam ou courrier indésirable.</div>';
                } catch (Exception $e) {
                  echo '<div class="alert alert-danger py-5 my-5">Le message n\'a pas pu être envoyé. Erreur: ' . $mail->ErrorInfo . '</div>';
                }
              }
              break;

              case 'Valider':
                // Récupération des données du formulaire
                $userEmail = $user->getEmail();
                $userName = $user->getFirst_name() . ' ' . $user->getLast_name();
                $verificationRepository = new VerificationRepository();
                $verification = $verificationRepository->getLastVerificationByUserId($userId);
                $verificationCode = $verification ? $verification->getVerification_code() : null;
                $enteredCode = Security::secureInput($_POST['code_entered']);
                // Vérification du code
                if ($verificationCode == $enteredCode) {
                  // Suppression du code de vérification
                  //$verificationRepository = new VerificationRepository();
                  $verificationRepository->deleteAllCodesFromUser($user->getId());
                  $verificationRepository->deleteAllExpiredCodes();
                  // Récupération du panier de l'utilisateur
                  $userOrderRepository = new UserOrderRepository();
                  $cartId = $userOrderRepository->findCartId($user->getId());
                  if ($cartId == 0) {
                    $isCardCreated = $userOrderRepository->createEmptyCart($user->getId(), $user->getFk_store_id());
                    if ($isCardCreated) {
                      $cartId = $userOrderRepository->findCartId($user->getId());
                    } else {
                      echo '<div class="alert alert-danger py-5 my-5">Une erreur est survenue lors de la création du panier. Veuillez réessayer.</div>';
                    }
                  }
                  // Régénère l'identifiant de session pour éviter les attaques de fixation de session (vol de cookie de session)
                  session_regenerate_id(true);
                  // Enregistrement des données de l'utilisateur en session
                  $_SESSION['user'] = [
                    'id' => $user->getId(),
                    'first_name' => $user->getFirst_name(),
                    'last_name' => $user->getLast_name(),
                    'role' => $user->getRole(),
                    'store_id' => $user->getFk_store_id(),
                    'cart_id' => $cartId
                  ];
                  // Redirection vers la page espace client
                  header('Location: index.php?controller=dashboard&action=home');
                  exit();
                } else {
                  echo '<div class="alert alert-danger py-5 my-5">Le code de vérification est incorrect. Veuillez réessayer.</div>';
                }
              break;
          }
        }
      ?>

      <form method="post" class="my-4" action="index.php?controller=auth&action=check">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
          <input type="hidden" name="user_id" value="<?= $userId ?>">
          <input type="submit" name="authenticateUser" class="btn btn-gamestore text-uppercase" value="<?= $is_resend ? 'Renvoyer le code' : 'Envoyer le code' ?>">
      </form>

      <form method="post" class="was-validated" action="index.php?controller=auth&action=check">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
        <input type="hidden" name="user_id" value="<?= $userId ?>">
        <div class="form-floating mb-3">
          <input type="number" class="form-control" id="code_verification" name="code_entered" required>
          <label for="code_verification">Code de vérification</label>
          <div class="invalid-feedback">
            Entrez le code de vérification reçu par email.
          </div>
        </div>
        <input type="submit" name="authenticateUser" class="btn btn-gamestore text-uppercase" value="Valider">
      </form>
    </div>
  </section>

<?php 

require_once _TEMPLATEPATH_ . '/footer.php'; 

ob_end_flush()

?>