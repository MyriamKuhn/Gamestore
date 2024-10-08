/***********/

/* IMPORT */

/**********/
import { secureInput, validateNumberInput } from '../utils.js';


/*****************************/

/* INITIALISATION DE SELECT2 */

/*****************************/

$(document).ready(function() {
  function formatOption(option) {
    if (!option.id) {
      return option.text;
    }
    
    var imageUrl = $(option.element).data('image');
    var $option = $(
      '<span class="d-flex justify-content-center align-items-center">'
        + '<img src="' + imageUrl + '" class="img-flag" style="width: 30px; margin-right: 10px;" />' 
        + option.text 
        + '</span>'
    );
    return $option;
  }

  $('#pegi-select').select2({
    theme: 'bootstrap-5',  
    templateResult: formatOption,  
    templateSelection: formatOption,  
    minimumResultsForSearch: Infinity,
    width: 'resolve'
  });

  $('#genres-select').select2({
    theme: 'bootstrap-5',
    width: 'resolve'
  });

  $('.select2-selection').css({
    'display': 'flex',
    'align-items': 'center',
    'justify-content': 'center',
    'min-height': '50px'
  });

  $('.select2-selection--multiple').css({
    'display': 'flex',
    'align-items': 'center',
    'justify-content': 'center',
    'padding-left': '100px'
  });
});


/****************/

// AU DEMARRAGE //

/****************/
document.addEventListener('DOMContentLoaded', initializeFields);

// Fonction pour initialiser l'état des champs au chargement de la page
function initializeFields() {
  numberInputs.forEach(function(input) {
    const storePlatformId = input.name.split('-')[0] + '-' + input.name.split('-')[1];

    // Vérification si c'est un champ de prix
    if (input.name.includes('price')) {
      const newCheckbox = document.querySelector(`input[name="${storePlatformId}-new"]`);
      const reducedCheckbox = document.querySelector(`input[name="${storePlatformId}-reduced"]`);
      const discountInput = document.querySelector(`input[name="${storePlatformId}-discount"]`);
      const stockInput = document.querySelector(`input[name="${storePlatformId}-stock"]`);

      // Si le prix est 0, désactiver tous les champs associés
      if (validateNumberInput(input.value) === 0) {
        newCheckbox.checked = false;
        newCheckbox.disabled = true;
        reducedCheckbox.checked = false;
        reducedCheckbox.disabled = true;
        discountInput.value = 0;
        discountInput.disabled = true;
        stockInput.value = 0;
        stockInput.disabled = true;
      } else {
        // Si le prix n'est pas 0, réactiver les champs
        newCheckbox.disabled = false;
        reducedCheckbox.disabled = false;
        discountInput.disabled = false;
        stockInput.disabled = false;
      }
    }

    // Vérification si c'est un champ de réduction
    if (input.name.includes('discount')) {
      const reducedCheckbox = document.querySelector(`input[name="${storePlatformId}-reduced"]`);

      // Si la réduction est supérieure à 0, cocher la case "reduced"
      if (validateNumberInput(input.value) > 0) {
        reducedCheckbox.checked = true;
      } else {
        reducedCheckbox.checked = false;
      }
    }
  });
}



/*******************************************/

/* VERIFICATIONS DES DONNEES PERSONNELLES */

/******************************************/
// Récupération des éléments
const gameNameInput = document.getElementById('game_name');
const gameDescriptionInput = document.getElementById('game_description');
const genreSelect = document.getElementById('genres-select');
const deleteSpotlight = document.getElementById('delete-spotlight');
const imageSpotlightInput = document.getElementById('game_spotlight_input');
const imageSpotlight = document.getElementById('game_spotlight');
const deletePresentation = document.getElementById('delete-presentation');
const imagePresentationInput = document.getElementById('game_presentation_input');
const imagePresentation = document.getElementById('game_presentation');
const deletesCarousel = document.querySelectorAll('.carousels-deletes');
const imagesCarousel = document.getElementById('game_carousel');
const numberInputs = document.querySelectorAll('input[type="number"]');

// Fonctions de vérification des champs
function checkGameName() {
  const gameName = gameNameInput.value;
  const gameNameRegex = /^[a-zA-ZÀ-ÿœŒæÆ0-9\-\s\'\’\!\?\.\(\)\[\]:]{3,}$/;
  
  if (!gameNameRegex.test(secureInput(gameName).trim())) {
    gameNameInput.classList.add("is-invalid");
  } else {
    gameNameInput.classList.remove("is-invalid");
  }
}

function checkGameDescription() {
  const gameDescription = gameDescriptionInput.value;
  const gameDescriptionRegex = /^[a-zA-ZÀ-ÿœŒæÆ0-9\-\s\'\’\!\?\.\,\(\)\[\]:;\"\n]{3,}$/;

  if (!gameDescriptionRegex.test(secureInput(gameDescription).trim())) {
    gameDescriptionInput.classList.add("is-invalid");
  } else {
    gameDescriptionInput.classList.remove("is-invalid");
  }
}

function checkSelectedGenres() {
  const selectedGenres = Array.from(genreSelect.selectedOptions);

  if (selectedGenres.length === 0) {
    genreSelect.classList.add("is-invalid"); 
  } else {
    genreSelect.classList.remove("is-invalid"); 
  }
}

function checkSpotlight() {
  const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'];

  if (deleteSpotlight) {
    if (deleteSpotlight.checked) {
      imageSpotlightInput.classList.remove("d-none");
      if (imageSpotlight.files.length === 0) {
        imageSpotlight.classList.add("is-invalid");
      } else {
        const imageType = imageSpotlight.files[0].type;
        if (!allowedTypes.includes(imageType)) {
          imageSpotlight.classList.add("is-invalid");
        } else {
          imageSpotlight.classList.remove("is-invalid");
        }
      }
    } else {
      imageSpotlightInput.classList.add("d-none");
    }
  } else {
    if (imageSpotlight.files.length === 0) {
      imageSpotlight.classList.add("is-invalid");
    } else {
      const imageType = imageSpotlight.files[0].type;
      if (!allowedTypes.includes(imageType)) {
        imageSpotlight.classList.add("is-invalid");
      } else {
        imageSpotlight.classList.remove("is-invalid");
      }
    }
  }
};

function checkPresentation() {
  const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'];

  if (deletePresentation) {
    if (deletePresentation.checked) {
      imagePresentationInput.classList.remove("d-none");
      if (imagePresentation.files.length === 0) {
        imagePresentation.classList.add("is-invalid");
      } else {
        const imageType = imagePresentation.files[0].type;
        if (!allowedTypes.includes(imageType)) {
          imagePresentation.classList.add("is-invalid");
        } else {
          imagePresentation.classList.remove("is-invalid");
        }
      }
    } else {
      imagePresentationInput.classList.add("d-none");
    }
  } else {
    if (imagePresentation.files.length === 0) {
      imagePresentation.classList.add("is-invalid");
    } else {
      const imageType = imagePresentation.files[0].type;
      if (!allowedTypes.includes(imageType)) {
        imagePresentation.classList.add("is-invalid");
      } else {
        imagePresentation.classList.remove("is-invalid");
      }
    }
  }
}

function checkCarouselImages() {
  const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'];

  if (deletesCarousel) {
    // Compter le nombre d'images existantes
    let existingImages = deletesCarousel.length;
    // Compter le nombre d'images à uploader
    let imagesToUpload = imagesCarousel.files.length; 

    // Compter les images sélectionnées pour suppression
    let imagesToDelete = 0; 
    deletesCarousel.forEach(checkbox => {
      if (checkbox.checked) {
        imagesToDelete++;
      }
    });

    // Calculer le nombre total d'images restantes
    const totalImages = existingImages - imagesToDelete + imagesToUpload;

    if (totalImages < 2) {
      imagesCarousel.classList.add("is-invalid");
    } else {
      if (imagesToUpload > 0) {
        Array.from(imagesCarousel.files).forEach(function(file) {
          if (!allowedTypes.includes(file.type)) {
            imagesCarousel.classList.add("is-invalid");
          } else {
            imagesCarousel.classList.remove("is-invalid");
          }
        });
      } else {
        imagesCarousel.classList.remove("is-invalid");
      }
    }
  } else {
    if (imagesCarousel.files.length < 2) {
      imagesCarousel.classList.add("is-invalid");
    } else {
      Array.from(imagesCarousel.files).forEach(function(file) {
        if (!allowedTypes.includes(file.type)) {
          imagesCarousel.classList.add("is-invalid");
        } else {
          imagesCarousel.classList.remove("is-invalid");
        }
      });
    }
  }
} 

// Vérification des champs à chaque saisie
gameNameInput.addEventListener('input', checkGameName);
gameDescriptionInput.addEventListener('input', checkGameDescription);
genreSelect.addEventListener('change', checkSelectedGenres);
if (deleteSpotlight) { 
  deleteSpotlight.addEventListener('change', checkSpotlight);
}
imageSpotlight.addEventListener('change', checkSpotlight);
if (deletePresentation) {
  deletePresentation.addEventListener('change', checkPresentation);
}
imagePresentation.addEventListener('change', checkPresentation);
if (deletesCarousel) {
  deletesCarousel.forEach(function(checkbox) {
    checkbox.addEventListener('change', checkCarouselImages);
  });
}
imagesCarousel.addEventListener('change', checkCarouselImages);

// Vérification si le prix est à 0 désactiver tous les champs associés et réinitialiser leurs valeurs à la saisie
numberInputs.forEach(function(priceInput) {
  if (priceInput.name.includes('price')) {
    priceInput.addEventListener('input', function() {
      const storePlatformId = priceInput.name.split('-')[0] + '-' + priceInput.name.split('-')[1];

      // Sélectionner les autres champs associés à cette plateforme
      const newCheckbox = document.querySelector(`input[name="${storePlatformId}-new"]`);
      const reducedCheckbox = document.querySelector(`input[name="${storePlatformId}-reduced"]`);
      const discountInput = document.querySelector(`input[name="${storePlatformId}-discount"]`);
      const stockInput = document.querySelector(`input[name="${storePlatformId}-stock"]`);

      // Si le prix est 0
      if (validateNumberInput(priceInput.value) === 0) {
        // Désactiver les cases à cocher et autres champs, et réinitialise leurs valeurs
        newCheckbox.checked = false;
        newCheckbox.disabled = true;

        reducedCheckbox.checked = false;
        reducedCheckbox.disabled = true;

        discountInput.value = 0;
        discountInput.disabled = true;

        stockInput.value = 0;
        stockInput.disabled = true;
      } else {
        // Si le prix n'est pas 0, réactiver les champs
        newCheckbox.disabled = false;
        reducedCheckbox.disabled = false;
        discountInput.disabled = false;
        stockInput.disabled = false;
      }
    });
  }
});

// Vérification si la réduction est supérieure à 0, cocher la case "reduced", sinon décocher la case "reduced"
numberInputs.forEach(function(discountInput) {
  // Ajouter un écouteur d'événement sur chaque champ de réduction
  if (discountInput.name.includes('discount')) {
    discountInput.addEventListener('input', function() {
      const storePlatformId = discountInput.name.split('-')[0] + '-' + discountInput.name.split('-')[1];

      // Sélectionner la case à cocher "reduced" associée
      const reducedCheckbox = document.querySelector(`input[name="${storePlatformId}-reduced"]`);

      // Si la réduction est supérieure à 0, cocher la case "reduced", sinon décocher la case "reduced"
      if (validateNumberInput(discountInput.value) > 0) {
        reducedCheckbox.checked = true;
      } else {
        reducedCheckbox.checked = false;
      }
    });

    // Ajouter un écouteur d'événements sur la case à cocher "reduced"
    const storePlatformId = discountInput.name.split('-')[0] + '-' + discountInput.name.split('-')[1];
    const reducedCheckbox = document.querySelector(`input[name="${storePlatformId}-reduced"]`);

    reducedCheckbox.addEventListener('change', function() {
      // Si la case "reduced" est décochée, mettre le discount à 0
      if (!reducedCheckbox.checked) {
        discountInput.value = 0;
        discountInput.classList.remove("is-invalid");
      } else {
        // Si la case "reduced" est cochée, mettre le discount à 1
        discountInput.classList.add("is-invalid");
      }
    });
  }
});

// Au clic sur le bouton de validation, vérification des champs et ajout de la classe was-validated pour la validation de Bootstrap et empêcher l'envoi du formulaire si les champs ne sont pas valides
const gameForm = document.getElementById('game-form');

gameForm.addEventListener('submit', e => {
  if (!e.target.checkValidity()) {
    e.preventDefault();
    e.stopPropagation();
  }
  e.target.classList.add('was-validated');
});