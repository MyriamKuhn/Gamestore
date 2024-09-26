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
      .then(datas => {
        gameDatas = datas;
        new PlatformSelect(gameDatas);
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
