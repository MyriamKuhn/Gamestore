<?php 
ob_start(); // Démarre la mise en tampon de sortie

require _ROOTPATH_ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Dotenv\Dotenv;

use App\Tools\Security;
use App\Repository\UserRepository;
use App\Repository\VerificationRepository;

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
      <h2 class="text-uppercase">Activation de votre compte</h2>
    </div>
    <div class="my-3">
      <p class="text-uppercase title-show">Bienvenue <?= $user->getFirst_name() . ' ' . $user->getLast_name() ?></p>
      <p>Veuillez générer un code que nous vous enverrons par mail à l'adresse email indiquée lors de votre inscription puis saisir ce code ci-dessous afin d'activer votre compte.</p>
      
      <?php
        // Vérifier si le formulaire a été soumis
        if (isset($_POST["verifyUser"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
          // Vérification du token CSRF
          Security::checkCSRF($_POST['csrf_token']);
          switch ($_POST["verifyUser"]) {
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
                            <p class='code'>". $verificationCode . "</p>
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
                // Mise à jour du statut de l'utilisateur
                $userRepository = new UserRepository();
                $userRepository->updateUserStatus($user);
                // Suppression du code de vérification
                $verificationRepository = new VerificationRepository();
                $verificationRepository->deleteAllCodesFromUser($user->getId());
                $verificationRepository->deleteAllExpiredCodes();
                echo '<div class="alert alert-warning py-5 my-5">Votre compte a été activé avec succès !<br>Vous serez redirigés dans 10 secondes, vous pouvez également cliquer sur ce lien pour vous connecter : <a href="index.php?controller=auth&action=login">Vers l\'espace client</a></div>';
                header('refresh:10;url=index.php?controller=auth&action=login');
                // Envoi du mail de bienvenue
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
                  $mail->Subject = "[Gamestore] Bienvenue $userName !";
                  $mail->Body = "
                    <html>
                    <head>
                        <style>
                            body { font-family: Arial, sans-serif; line-height: 1.6; }
                            .container { padding: 20px; }
                            .header { font-size: 24px; font-weight: bold; }
                            .footer { margin-top: 20px; font-size: 12px; color: gray; }
                        </style>
                    </head>
                    <body>
                        <div class='container'>
                          <img src='cid:logo_cid' alt='Logo Gamestore' style='width: 100px; height: auto;' /> 
                          <div class='header'>Bienvenue chez Gamestore, " . $userName . "!</div>
                          <p>Votre compte a été activé avec succès.</p>
                          <p>Vous pouvez désormais vous connecter à votre compte à l'aide de votre adresse e-mail : <strong>" . $userEmail . "</strong> et du mot de passe que vous avez défini lors de votre inscription.</p>
                          <p>À bientôt sur Gamestore !</p>
                          <div class='footer'>Ceci est un message automatique, veuillez ne pas répondre.</div>
                        </div>
                    </body>
                    </html>
                  ";
                  $mail->AltBody = "Bienvenue chez Gamestore ". $userName . " !\n\nVotre compte a été activé avec succès.\n\nVous pouvez désormais vous connecter à votre compte à l'aide de votre adresse mail : " . $userEmail . " et du mot de passe que vous avez défini lors de votre inscription.\n\nÀ bientôt sur Gamestore !";

                  // Envoyer l'email
                  $mail->send();
                } catch (Exception $e) {
                  $error = "Le message de bienvenue n'a pas pu être envoyé. Erreur: " . $mail->ErrorInfo . ".";
                }
              } else {
                echo '<div class="alert alert-danger py-5 my-5">Le code de vérification est incorrect. Veuillez réessayer.</div>';
              }
              break;
          }
        }
          
      ?>

      <form method="post" class="my-4" action="index.php?controller=user&action=activation">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
          <input type="hidden" name="user_id" value="<?= $userId ?>">
          <input type="submit" name="verifyUser" class="btn btn-gamestore text-uppercase" value="<?= $is_resend ? 'Renvoyer le code' : 'Envoyer le code' ?>">
      </form>

      <form method="post" class="was-validated" action="index.php?controller=user&action=activation">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
        <input type="hidden" name="user_id" value="<?= $userId ?>">
        <div class="form-floating mb-3">
          <input type="number" class="form-control" id="code_verification" name="code_entered" required>
          <label for="code_verification">Code de vérification</label>
          <div class="invalid-feedback">
            Entrez le code de vérification reçu par email.
          </div>
        </div>
        <input type="submit" name="verifyUser" class="btn btn-gamestore text-uppercase" value="Valider">
      </form>
    </div>
  </section>

<?php 

require_once _TEMPLATEPATH_ . '/footer.php'; 

ob_end_flush(); // Vide le tampon de sortie et envoie tout le contenu au navigateur

?>

