/***********/

/* IMPORT */

/**********/
import { secureInput } from './utils.js';


/****************************************************************************/

/* VISIBILITE DU MOT DE PASSE ET ADAPTATION DE L'ICONE OEIL DU MOT DE PASSE */

/****************************************************************************/
// Récupération des éléments
const oldIcon = document.querySelector(".toggleIconOld");
const newIcon = document.querySelector(".toggleIconNew");
const confirmIcon = document.querySelector(".toggleIconConfirm");

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

// Pour l'ancien mot de passe
oldIcon.addEventListener("click", () => {
    togglePassIcon({
        icon: document.querySelector(".toggleIconOld"),
        input: document.getElementById('passwordOld'),
    });
});

// Pour le nouveau mot de passe
newIcon.addEventListener("click", () => {
  togglePassIcon({
      icon: document.querySelector(".toggleIconNew"),
      input: document.getElementById('passwordNew'),
  });
});

// Pour la confirmation du mot de passe
confirmIcon.addEventListener("click", () => {
    togglePassIcon({
        icon: document.querySelector(".toggleIconConfirm"),
        input: document.getElementById('password-confirm'),
    });
});


/**************************************************/

/* VERIFICATIONS ET SECURISATIONS DU MOT DE PASSE */

/**************************************************/
// Récupération des éléments
const oldPasswordInput = document.getElementById('passwordOld');
const newPasswordInput = document.getElementById('passwordNew');
const confirmPasswordInput = document.getElementById('password-confirm');
const passwordError = document.querySelector('.password-error');

// Fonction qui vérifie si les mots de passe correspondent et respectent les règles de sécurité
function checkPasswords() {
  const oldPassword = oldPasswordInput.value;
  const newPassword = newPasswordInput.value;
  const confirmPassword = confirmPasswordInput.value;  
  // Règles pour le mot de passe
  const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{15,}$/;
  if (!passwordRegex.test(secureInput(newPassword))) {
    passwordError.textContent = 'Le mot de passe doit contenir au moins 15 caractères, avec une majuscule, une minuscule, un chiffre, et un caractère spécial.';
    newPasswordInput.classList.add("is-invalid");
    confirmPasswordInput.classList.add("is-invalid");
  } else if (secureInput(newPassword) !== secureInput(confirmPassword)) {
    passwordError.textContent = 'Les mots de passe ne correspondent pas.';
    newPasswordInput.classList.add("is-invalid");
    confirmPasswordInput.classList.add("is-invalid");
  } else {
    passwordError.textContent = '';
    newPasswordInput.classList.remove("is-invalid");
    confirmPasswordInput.classList.remove("is-invalid");
  }
  if (!passwordRegex.test(secureInput(oldPassword))) {
    oldPasswordInput.classList.add("is-invalid");
  } else {
    oldPasswordInput.classList.remove("is-invalid");
  }
}

// Vérification des mots de passe à chaque saisie
oldPasswordInput.addEventListener('input', checkPasswords);
newPasswordInput.addEventListener('input', checkPasswords);
confirmPasswordInput.addEventListener('input', checkPasswords);


// Au clic sur le bouton de validation, vérification des champs et ajout de la classe was-validated pour la validation de Bootstrap et empêcher l'envoi du formulaire si les champs ne sont pas valides
const passwordForm = document.getElementById('password-form');

passwordForm.addEventListener('submit', e => {
  if (!e.target.checkValidity()) {
    e.preventDefault();
    e.stopPropagation();
  }
  e.target.classList.add('was-validated');
});