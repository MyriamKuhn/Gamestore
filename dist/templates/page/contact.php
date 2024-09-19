<?php 

require './vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Dotenv\Dotenv;

use App\Tools\Security;

$dotenv = new Dotenv(__DIR__ . '../../..');
$dotenv->load();

// Clé secrète reCAPTCHA
$recaptchaSecret = $_ENV['SITE_RECAPTCHA_SECRET'];

require_once _TEMPLATEPATH_ . '/header.php'; 
?>

<!-- START : Main -->
<main class="container my-4 main" id="hero">
  <section class="mt-2">
    <div class="d-flex justify-content-between gamestore-title">
      <h2 class="text-uppercase">Nous contacter</h2>
    </div>
    <div class="my-3">
      <p>Vous avez une question, une suggestion ou une demande particulière ? N'hésitez pas à nous contacter en remplissant le formulaire ci-dessous. Nous vous répondrons dans les plus brefs délais.</p>
      <?php
        // Vérifier si le formulaire a été soumis
        if (isset($_POST["sendContact"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
          // Capturer les données du formulaire
          $name = Security::secureInput($_POST['name']);
          $email = Security::secureEmail($_POST['email']);
          $subject = Security::secureInput($_POST['subject']);
          $message = Security::secureInput($_POST['message']);
          $recaptchaResponse = $_POST['g-recaptcha-response'];

          // Vérification du reCAPTCHA
          $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
          $response = file_get_contents($recaptchaUrl . '?secret=' . $recaptchaSecret . '&response=' . $recaptchaResponse);
          $responseKeys = json_decode($response, true);

          if (intval($responseKeys["success"]) !== 1) {
            echo '<div class="alert alert-danger py-5 my-5">Échec de la vérification reCAPTCHA. Veuillez réessayer.</div>';
            
          } else {

            // Si la vérification CAPTCHA est réussie, envoyer l'email avec PHPMailer
            $mail = new PHPMailer(true);
            try {
                // Configuration du serveur SMTP
                $mail->isSMTP();
                $mail->Host       = $_ENV['MAILER_HOST']; // Utilisez votre serveur SMTP
                $mail->SMTPAuth   = true;
                $mail->Username   = $_ENV['MAILER_EMAIL']; // Votre email Gmail
                $mail->Password   = $_ENV['MAILER_PASSWORD']; // Utilisez un mot de passe d'application
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = $_ENV['MAILER_PORT']; // Le port SMTP de votre serveur

                // Paramètres de l'email
                $mail->setFrom($email, $name);
                $mail->addAddress($_ENV['MAILER_EMAIL'], 'Gamestore');
                $mail->isHTML(true);
                $mail->Subject = "[Gamestore] Demande de contact de ".$name;
                $mail->Body    = "<b>Nom :</b> $name <br><b>Email :</b> $email <br><br><b>Objet :</b><br>$subject <br><br><b>Message :</b><br>$message";
                $mail->AltBody = "Nom : $name\nEmail : $email\n\nObjet : $subject\n\nMessage:\n$message";

                // Envoyer l'email
                $mail->send();
                echo '<div class="alert alert-success py-5 my-5">Le message a été envoyé avec succès !</div>';
            } catch (Exception $e) {
                echo '<div class="alert alert-danger py-5 my-5">Le message n\'a pas pu être envoyé. Erreur: {$mail->ErrorInfo}';
            }
          }
        }
      ?>
      <!-- START : Formulaire de contact -->
      <form method="post" class="was-validated">
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="name" name="name" minlength="3" maxlength="100" required>
          <label for="name">Nom complet</label>
          <div class="invalid-feedback">
            Entrez votre nom.
          </div>
        </div>
        <div class="form-floating mb-3">
          <input type="email" class="form-control" id="email" name="email" required>
          <label for="email">Adresse mail</label>
          <div class="invalid-feedback">
            Entrez votre adresse mail.
          </div>
        </div>
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="subject" name="subject" minlength="10" maxlength="200" required>
          <label for="subject">Objet</label>
          <div class="invalid-feedback">
            Entrez un sujet.
          </div>
        </div>
        <div class="form-floating mb-3">
          <textarea class="form-control" id="message" name="message" rows="5" style="height: 100px" minlength="50" maxlength="1000" required></textarea>
          <label for="message">Message</label>
          <div class="invalid-feedback">
            Entrez votre demande.
          </div>
        </div>

        <!-- Widget reCAPTCHA -->
        <div class="g-recaptcha" data-sitekey="<?= $_ENV['SITE_RECAPTCHA_KEY'] ?>"></div>

        <div class="text-center mt-3">
          <input type="submit" class="btn btn-gamestore text-uppercase" name="sendContact" value="Envoyer">
        </div>
      </form>
      <!-- END : Formulaire de contact -->
  </section>

  <?php require_once _TEMPLATEPATH_ . '/footer.php'; ?>