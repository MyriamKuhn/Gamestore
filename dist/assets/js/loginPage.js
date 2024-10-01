/***********/

/* IMPORT */

/**********/
import { secureInput } from './utils.js';


/****************************************************************************/

/* VISIBILITE DU MOT DE PASSE ET ADAPTATION DE L'ICONE OEIL DU MOT DE PASSE */

/****************************************************************************/
// Récupération des éléments
const passwordIcon = document.querySelector(".toggleIconPasword");

// Fonction qui permet de changer l'icône de l'oeil du mot de passe et de passer l'input en mode texte ou en mode password
const togglePassIcon = (props) => {
  const toggleIcon = props.icon;
  const inputPassword = props.input;
  if (inputPassword.type === "password") {
      inputPassword.type = "text";
      toggleIcon.classList.remove("bi-eye-slash");
      toggleIcon.classList.add("bi-eye");
  } else {
      inputPassword.type = "password";
      toggleIcon.classList.add("bi-eye-slash");
      toggleIcon.classList.remove("bi-eye");
  };
};

// Pour le mot de passe
passwordIcon.addEventListener("click", () => {
    togglePassIcon({
        icon: document.querySelector(".toggleIconPasword"),
        input: document.getElementById('password'),
    });
});

/*****************************/

/* VERIFICATIONS DES ENTREES */

/*****************************/
// Récupération des éléments
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('password');
const form = document.getElementById('login-form');

function checkEmail() {
  const email = emailInput.value;
  const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  if (!emailRegex.test(secureInput(email).trim())) {
    emailInput.classList.add("is-invalid");
  } else {
    emailInput.classList.remove("is-invalid");
  }
}

function checkPassword() {
  const password = passwordInput.value;
  const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{15,}$/;
  if (!passwordRegex.test(secureInput(password))) {
    passwordInput.classList.add("is-invalid");
  } else {
    passwordInput.classList.remove("is-invalid");
  }
}

emailInput.addEventListener('input', checkEmail);
passwordInput.addEventListener('input', checkPassword);

// Au clic sur le bouton de validation, vérification des champs et ajout de la classe was-validated pour la validation de Bootstrap et empêcher l'envoi du formulaire si les champs ne sont pas valides
form.addEventListener('submit', e => {
  if (!e.target.checkValidity()) {
    e.preventDefault();
    e.stopPropagation();
  }
  e.target.classList.add('was-validated');
});
