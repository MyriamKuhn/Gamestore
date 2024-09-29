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
  const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{15,}$/;
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


/*******************************************/

/* VERIFICATIONS DES DONNEES PERSONNELLES */

/******************************************/
// Récupération des éléments
const lastNameInput = document.getElementById('last_name');
const firstNameInput = document.getElementById('first_name');
const addressInput = document.getElementById('address');
const postcodeInput = document.getElementById('postcode');
const cityInput = document.getElementById('city');

// Fonctions de vérification des champs
function checkLastName() {
  const lastName = lastNameInput.value;
  const lastNameRegex = /^[a-zA-ZÀ-ÿœŒæÆ\-\s\'\’]{3,}$/;
  if (!lastNameRegex.test(secureInput(lastName).trim())) {
    lastNameInput.classList.add("is-invalid");
  } else {
    lastNameInput.classList.remove("is-invalid");
  }
}

function checkFirstName() {
  const firstName = firstNameInput.value;
  const firstNameRegex = /^[a-zA-ZÀ-ÿœŒæÆ\-\s\'\’]{3,}$/;
  if (!firstNameRegex.test(secureInput(firstName).trim())) {
    firstNameInput.classList.add("is-invalid");
  } else {
    firstNameInput.classList.remove("is-invalid");
  }
}

function checkAddress() {
  const address = addressInput.value;
  const addressRegex = /^[a-zA-Z0-9À-ÿœŒæÆ\-\s\(\)\'\’]{3,}$/;
  if (!addressRegex.test(secureInput(address).trim())) {
    addressInput.classList.add("is-invalid");
  } else {
    addressInput.classList.remove("is-invalid");
  }
}

function checkPostcode() {
  const postcode = postcodeInput.value;
  const postcodeRegex = /^[0-9]{5}$/;
  if (!postcodeRegex.test(secureInput(postcode))) {
    postcodeInput.classList.add("is-invalid");
  } else {
    postcodeInput.classList.remove("is-invalid");
  }
}

function checkCity() {
  const city = cityInput.value;
  const cityRegex = /^[a-zA-ZÀ-ÿœŒæÆ\-\s\(\)\'\’\.\,]{3,}$/;
  if (!cityRegex.test(secureInput(city).trim())) {
    cityInput.classList.add("is-invalid");
  } else {
    cityInput.classList.remove("is-invalid");
  }
}

// Vérification des champs à chaque saisie
lastNameInput.addEventListener('input', checkLastName);
firstNameInput.addEventListener('input', checkFirstName);
addressInput.addEventListener('input', checkAddress);
postcodeInput.addEventListener('input', checkPostcode);
cityInput.addEventListener('input', checkCity);


// Au clic sur le bouton de validation, vérification des champs et ajout de la classe was-validated pour la validation de Bootstrap et empêcher l'envoi du formulaire si les champs ne sont pas valides
const personalForm = document.getElementById('personal-form');
const gamestoreForm = document.getElementById('gamestore-form');
const passwordForm = document.getElementById('password-form');

personalForm.addEventListener('submit', e => {
  if (!e.target.checkValidity()) {
    e.preventDefault();
    e.stopPropagation();
  }
  e.target.classList.add('was-validated');
});

gamestoreForm.addEventListener('submit', e => {
  if (!e.target.checkValidity()) {
    e.preventDefault();
    e.stopPropagation();
  }
  e.target.classList.add('was-validated');
});

passwordForm.addEventListener('submit', e => {
  if (!e.target.checkValidity()) {
    e.preventDefault();
    e.stopPropagation();
  }
  e.target.classList.add('was-validated');
});