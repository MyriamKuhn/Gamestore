/***********/

/* IMPORT */

/**********/
import { secureInput } from '../utils.js';


/*****************************/

/* VERIFICATIONS DES ENTREES */

/*****************************/
// Récupération des éléments
const emailInput = document.getElementById('email');
const form = document.getElementById('password');

function checkEmail() {
  const email = emailInput.value;
  const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  if (!emailRegex.test(secureInput(email).trim())) {
    emailInput.classList.add("is-invalid");
  } else {
    emailInput.classList.remove("is-invalid");
  }
}

emailInput.addEventListener('input', checkEmail);

// Au clic sur le bouton de validation, vérification des champs et ajout de la classe was-validated pour la validation de Bootstrap et empêcher l'envoi du formulaire si les champs ne sont pas valides
form.addEventListener('submit', e => {
  if (!e.target.checkValidity()) {
    e.preventDefault();
    e.stopPropagation();
  }
  e.target.classList.add('was-validated');
});