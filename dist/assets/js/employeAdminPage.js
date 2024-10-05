/***********/

/* IMPORT */

/**********/
import { secureInput } from './utils.js';


/*******************************************/

/* VERIFICATIONS DES DONNEES PERSONNELLES */

/******************************************/
// Récupération des éléments
const lastNameInput = document.getElementById('last_name');
const firstNameInput = document.getElementById('first_name');
const addressInput = document.getElementById('address');
const postcodeInput = document.getElementById('postcode');
const cityInput = document.getElementById('city');
const emailInput = document.getElementById('email');

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

function checkEmail() {
  const email = emailInput.value;
  const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  if (!emailRegex.test(secureInput(email).trim())) {
    emailInput.classList.add("is-invalid");
  } else {
    emailInput.classList.remove("is-invalid");
  }
}

// Vérification des champs à chaque saisie
lastNameInput.addEventListener('input', checkLastName);
firstNameInput.addEventListener('input', checkFirstName);
addressInput.addEventListener('input', checkAddress);
postcodeInput.addEventListener('input', checkPostcode);
cityInput.addEventListener('input', checkCity);
emailInput.addEventListener('input', checkEmail);

// Au clic sur le bouton de validation, vérification des champs et ajout de la classe was-validated pour la validation de Bootstrap et empêcher l'envoi du formulaire si les champs ne sont pas valides
const employeForm = document.getElementById('employe-form');

employeForm.addEventListener('submit', e => {
  if (!e.target.checkValidity()) {
    e.preventDefault();
    e.stopPropagation();
  }
  e.target.classList.add('was-validated');
});