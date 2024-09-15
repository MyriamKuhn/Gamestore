<?php 

require_once _TEMPLATEPATH_ . '/header.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Variables
  $name = htmlspecialchars($_POST['name']);
  $email = htmlspecialchars($_POST['email']);
  $subject = htmlspecialchars($_POST['subject']);
  $message = htmlspecialchars($_POST['message']);
  $to = "myriam.kuehn@free.fr";  
  $headers = "From: " . $email;
  
  // Validation simple des champs
  if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
    // Envoi de l'email
    if (mail($to, $subject, $message, $headers)) {
      echo "<div class='alert alert-success'>Votre message a bien été envoyé !</div>";
    } else {
      echo "<div class='alert alert-danger'>Une erreur est survenue lors de l'envoi du message. Veuillez réessayer.</div>";
    }
  } else {
    echo "<div class='alert alert-danger'>Tous les champs sont requis.</div>";
  }
}

?>

<!-- START : Main -->
<main class="container my-4 main" id="hero">
  <section class="mt-2">
    <div class="d-flex justify-content-between gamestore-title">
      <h2 class="text-uppercase">Nous contacter</h2>
    </div>
    <div class="my-3">
      <p>Vous avez une question, une suggestion ou une demande particulière ? N'hésitez pas à nous contacter en remplissant le formulaire ci-dessous. Nous vous répondrons dans les plus brefs délais.</p>
      <!-- START : Formulaire de contact -->
      <form method="post">
        <div class="mb-3">
          <label for="name" class="form-label">Nom complet</label>
          <input type="text" class="form-control" id="name" placeholder="Entrez votre nom" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Adresse email</label>
          <input type="email" class="form-control" id="email" placeholder="Entrez votre email" required>
        </div>
        <div class="mb-3">
          <label for="subject" class="form-label">Objet</label>
          <input type="text" class="form-control" id="subject" placeholder="Sujet de votre message" required>
        </div>
        <div class="mb-3">
          <label for="message" class="form-label">Message</label>
          <textarea class="form-control" id="message" rows="5" placeholder="Votre message" required></textarea>
        </div>
        <div class="text-center">
          <button type="submit" name="submit-mail" class="btn btn-gamestore text-uppercase">Envoyer</button>
        </div>
      </form>
      <!-- END : Formulaire de contact -->
  </section>

<?php require_once _TEMPLATEPATH_ . '/footer.php'; ?>