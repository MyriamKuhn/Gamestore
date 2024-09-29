/***********/

// IMPORTS //

/**********/
import { searchGame } from './promoFilters.js';
import { searchInput, paginationSelect, resetButton, genresChecks, platformsChecks } from './variables.js';
import { validateJSONStructure } from './utils.js';



/********************/

// START PAGE PROMO  //

/********************/
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
getDatas();


/**********************/

// FETCH DES DONNEES //

/*********************/
export let gameDatas = null;

function getDatas() {
  fetch('index.php?controller=datas',
    {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': csrfToken
      },
      body: JSON.stringify({ action: 'getPromoDatas' })
    })
    .then(response => response.json())
    .then(data => {
      if (validateJSONStructure(data)) {
        gameDatas = data;
        prepareHtmlCard();
      } else {
        console.error('Format inattendu des données');
      }
    })
    .catch(error => console.error('Erreur : ' + error));
}


/**************/

// VARIABLES //

/**************/
export const cardsDiv = document.getElementById('cards');
export const storesChecks = document.querySelectorAll('.store-filter');


/********************************/

// AFFICHAGE DES CARTES DE JEUX //

/********************************/
export function prepareHtmlCard() {
  searchGame(1, true);
  document.getElementById('loading').classList.add('visually-hidden');
}


/**********************/

// RESET DES FILTRES //

/*********************/
export function resetFilters() {
  searchInput.value = '';
  genresChecks.forEach(genre => {
    genre.checked = false;
  });
  platformsChecks.forEach(platform => {
    platform.checked = false;
  });
  storeChecks.forEach(store => {
    store.checked = false;
  });
  paginationSelect.selectedIndex = 1;
  prepareHtmlCard(gameDatas['datas']);
}


/************************/

// LISTENER SUR FILTRES //

/***********************/
searchInput.addEventListener('search', () => {
  searchGame(1, true)
});
searchInput.addEventListener('input', () => {
  searchGame(1, true)
});
paginationSelect.addEventListener('change', () => {
  searchGame(1, true)
});
resetButton.addEventListener('click', resetFilters);

genresChecks.forEach(genre => {
  genre.addEventListener('change', () => {
    searchGame(1, true)
  });
});

platformsChecks.forEach(platform => {
  platform.addEventListener('change', () => {
    searchGame(1, true)
  });
});

storesChecks.forEach(store => {
  store.addEventListener('change', () => {
    searchGame(1, true)
  });
});