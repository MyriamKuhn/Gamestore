/***********/

/* IMPORT */

/**********/
import { secureInput } from './utils.js';
import { validateJSONStructure } from './utils.js';


/****************************************************************************/

/* VISIBILITE DU MOT DE PASSE ET ADAPTATION DE L'ICONE OEIL DU MOT DE PASSE */

/****************************************************************************/
// Récupération des éléments
const subscribeIcon = document.querySelector(".toggleIconSubscribe");
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

// Pour le mot de passe
subscribeIcon.addEventListener("click", () => {
    togglePassIcon({
        icon: document.querySelector(".toggleIconSubscribe"),
        input: document.getElementById('password'),
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
const passwordInput = document.getElementById('password');
const confirmPasswordInput = document.getElementById('password-confirm');
const passwordError = document.querySelector('.password-error');

// Fonction qui vérifie si les mots de passe correspondent et respectent les règles de sécurité
function checkPasswords() {
  const password = passwordInput.value;
  const confirmPassword = confirmPasswordInput.value;  
  // Règles pour le mot de passe
  const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{15,}$/;
  if (!passwordRegex.test(secureInput(password))) {
    passwordError.textContent = 'Le mot de passe doit contenir au moins 15 caractères, avec une majuscule, une minuscule, un chiffre, et un caractère spécial.';
    passwordInput.classList.add("is-invalid");
    confirmPasswordInput.classList.add("is-invalid");
  } else if (secureInput(password) !== secureInput(confirmPassword)) {
    passwordError.textContent = 'Les mots de passe ne correspondent pas.';
    passwordInput.classList.add("is-invalid");
    confirmPasswordInput.classList.add("is-invalid");
  } else {
    passwordError.textContent = '';
    passwordInput.classList.remove("is-invalid");
    confirmPasswordInput.classList.remove("is-invalid");
  }
}

// Vérification des mots de passe à chaque saisie
passwordInput.addEventListener('input', checkPasswords);
confirmPasswordInput.addEventListener('input', checkPasswords);


/***********************************/

/* VERIFICATIONS DES AUTRES CHAMPS */

/***********************************/
// Récupération des éléments
const lastNameInput = document.getElementById('last_name');
const firstNameInput = document.getElementById('first_name');
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
emailInput.addEventListener('input', checkEmail);


/***********************************************************************************/

/* AUTOCOMPLETION DE L'ADRESSE COMPLETE AVEC RECHERCHE DU GAMESTORE LE PLUS PROCHE */

/**********************************************************************************/
// Récupération des éléments
const addressSearchInput = document.getElementById('address-search');
const suggestionsContainer = document.getElementById('suggestions');
const addressInput = document.getElementById('address');
const postcodeInput = document.getElementById('postcode');
const cityInput = document.getElementById('city');
const nearestStore = document.getElementById('nearest_store');
const form = document.getElementById('register-form');

// Recherche d'adresse avec OpenStreetMap Nominatim API  avec un délai de 500 ms pour limiter les requêtes et mise en cache des résultats
let debounceTimeout; // Variable pour stocker le débounce timeout qui permet de retarder l'excécution de la fonction
const cache = {};

addressSearchInput.addEventListener('input', () => {
  clearTimeout(debounceTimeout);  // Annule le délai précédent
  debounceTimeout = setTimeout(async () => {
    const query = addressSearchInput.value;
    // Vérifie si la recherche contient moins de 3 caractères et vide les suggestions
    if (query.length < 3) {
      suggestionsContainer.innerHTML = '';
      suggestionsContainer.style.display = 'none';
      return;
    }
    // Vérifie dans le cache si la réponse ne s'y trouve pas déjà pour une recherche précédente et l'affiche
    if (cache[query]) {
      displaySuggestions(cache[query]);
      return;
    }
    // Requête à l'API OpenStreetMap Nominatim
    try {
      const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&q=${encodeURIComponent(query)}`);
      const results = await response.json();
      // Vérifie si les données sont bien formatées et les sécurise
      if (validateJSONStructure(results)) {
      // Stocke les résultats dans le cache pour éviter de refaire la requête
      cache[query] = results;
      } else {
        console.error('Format inattendu des données');
      }
      // Affiche les suggestions
      displaySuggestions(results);
    } catch (error) {
      console.error('Error fetching data:', error);
    }
  }, 500);  // Permet d'attendre que l'utilisateur ne tappe plus sur son clavier pendant 500 ms avant d'excécuter la fonction
});

// Affichage des suggestions et remplissage des champs qui sont en readonly
function displaySuggestions(results) {
  suggestionsContainer.innerHTML = '';
  // Crée un élément div pour chaque suggestion
  results.forEach(result => {
    const suggestion = document.createElement('div');
    suggestion.classList.add('suggestion');
    suggestion.textContent = result.display_name;
    // Ajoute un écouteur d'événement pour remplir les champs avec les informations de la suggestion en cliquant dessus
    suggestion.addEventListener('click', () => {
      addressInput.value = result.address.house_number + ' ' + result.address.road + ((result.address.hamlet) ? ' (' + result.address.hamlet + ')' : '') || ''; 
      postcodeInput.value = result.address.postcode || ''; 
      cityInput.value = result.address.city || result.address.town || result.address.village || result.address.hamlet || ''; 
      // Vide les suggestions
      suggestionsContainer.innerHTML = '';
      suggestionsContainer.style.display = 'none';
      // Crée un objet avec les coordonnées de la ville recherchée
      const searchedCity = {
        name: cityInput.value,
        lat: result.lat,
        lon: result.lon
      };
      // Appelle la fonction pour trouver la ville la plus proche du Gamestore
      findNearestCity(searchedCity, predefinedCities);
    });
    suggestionsContainer.appendChild(suggestion);
  });
  if (results.length > 0) {
    suggestionsContainer.style.display = 'block';
  } else {
    suggestionsContainer.style.display = 'none';
  }
}

// Coordonnées des 5 villes Gamestore prédéfinies
const predefinedCities = [
  { name: 'Nantes', lat: 47.10641425, lon: -1.5318722879425417 },
  { name: 'Lille', lat: 50.61806395, lon: 3.045303691127442 },
  { name: 'Bordeaux', lat: 44.79384015, lon: -0.6063085906819762 },
  { name: 'Paris', lat: 48.8534951, lon: 2.3483915 },
  { name: 'Toulouse', lat: 43.579275100000004, lon: 1.5032717763608257 }
];

// Formule de Haversine pour calculer la distance en kilomètres entre deux points géographiques
function haversine(lat1, lon1, lat2, lon2) {
  const R = 6371; // Rayon de la Terre en km
  const dLat = (lat2 - lat1) * Math.PI / 180;
  const dLon = (lon2 - lon1) * Math.PI / 180;
  const a = 
    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
    Math.sin(dLon / 2) * Math.sin(dLon / 2);
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  return R * c;
}

// Fonction pour trouver la ville la plus proche
function findNearestCity(inputCoords, predefinedCities) {
  let nearestCity = null;
  let shortestDistance = Infinity;

  // Parcourir les villes prédéfinies pour calculer la distance
  for (const city of predefinedCities) {
    const distance = haversine(inputCoords.lat, inputCoords.lon, city.lat, city.lon);
    // Comparer pour trouver la ville la plus proche
    if (distance < shortestDistance) {
      shortestDistance = distance;
      nearestCity = city;
    }
  }
  // Affiche le Gamestore le plus proche dans le input en readonly prévu à cet effet
  switch (nearestCity.name) {
    case 'Nantes':
      nearestStore.value = 'Gamestore Nantes, 42 Rue des Joueurs, 44000 Nantes';
      break;
    case 'Lille':
      nearestStore.value = 'Gamestore Lille, 15 Rue du Pixel, 59000 Lille';
      break;
    case 'Bordeaux':
      nearestStore.value = 'Gamestore Bordeaux, 23 Place du Geek, 33000 Bordeaux';
      break;
    case 'Paris':
      nearestStore.value = 'Gamestore Paris, 12 Rue du Gamer, 75001 Paris';
      break;
    case 'Toulouse':
      nearestStore.value = 'Gamestore Toulouse, 67 Avenue du Game, 31000 Toulouse';
      break;
    default:
      nearestStore.value = nearestCity.name;
  }
  checkAddress();
  checkPostcode();
  checkCity();
  gamestoreCity();
}

// Vérification des champs de l'autocomplétion pour la validation de Bootstrap
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

function gamestoreCity() {
  const city = nearestStore.value;
  const cityRegex = /^[a-zA-Z0-9À-ÿ\-\s,]{3,}$/;
  if (!cityRegex.test(secureInput(city).trim())) {
    nearestStore.classList.add("is-invalid");
  } else {
    nearestStore.classList.remove("is-invalid");
  }
}

// Au clic sur le bouton de validation, vérification des champs et ajout de la classe was-validated pour la validation de Bootstrap et empêcher l'envoi du formulaire si les champs ne sont pas valides
form.addEventListener('submit', e => {
  if (!e.target.checkValidity()) {
    e.preventDefault();
    e.stopPropagation();
  }
  e.target.classList.add('was-validated');
});