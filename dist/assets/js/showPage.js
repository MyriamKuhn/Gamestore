/***********/

// IMPORTS //

/**********/
import { secureInput } from './utils.js';
import { PlatformSelect } from './Classes/PlatformSelect.js';
import { CarouselGamestore } from './Classes/CarouselGamestore.js';


//**************/

// URL PARAMS //

/**************/
const url = new URL(window.location.href);
const urlParams = new URLSearchParams(url.search);
const gameId = secureInput(urlParams.get('id'));


/********************/

// START PAGE LIST  //

/********************/
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
getDatas();

// Fonction récursive pour valider si une structure JSON contient uniquement des objets et des tableaux
const validateJSONStructure = (data) => {
  if (Array.isArray(data)) {
    return data.every(item => validateJSONStructure(item));
  } else if (typeof data === 'object' && data !== null) {
    return Object.values(data).every(value => validateJSONStructure(value));
  } else {
    // Valider que ce ne soit pas un type inattendu (par exemple une fonction)
    return ['string', 'number', 'boolean'].includes(typeof data);
  }
};




/**********************/

// FETCH DES DONNEES //

/*********************/
export let gameDatas = null;

function getDatas() {
  try {
    const requestBody = JSON.stringify({
      action: 'getGameDatas',
      gameId: gameId
    });

    fetch('index.php?controller=datas',
      {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': csrfToken
        },
        body: requestBody
      })
      .then(response => response.json())
      .then(data => {
        if (validateJSONStructure(data)) {
          gameDatas = data;
          new PlatformSelect(gameDatas);
        } else {
          console.error('Format inattendu des données');
        }
      })
      .catch(error => console.error('Erreur : ' + error));
    } catch (error) {
    console.error('Erreur : ' + error);
  }
}

/*********************************************/

// CONSTRUCTION DES SELECT POUR PLATEFORMES //

/********************************************/
function buildPlatformSelect() {
  new PlatformSelect(gameDatas);
}

//********************************************//

// CHARGEMENT DU CAROUSEL //

//******************************************//
document.addEventListener('DOMContentLoaded', function() {
  new CarouselGamestore(document.querySelector('#carousel-gamestore'), {
  slidesToScroll: 1,
  slidesVisible: 1,
  loop: false,
  pagination: true,
  navigation: false,
  infinite: true,
  navigation: false
  });
});
