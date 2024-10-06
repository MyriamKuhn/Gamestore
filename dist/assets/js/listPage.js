/***********/

// IMPORTS //

/**********/
import { searchGame } from './listFilters.js';
import { searchInput, paginationSelect, resetButton, genresChecks, platformsChecks } from './variables.js';
import { validateJSONStructure } from './utils.js';


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
  fetch('index.php?controller=datas',
    {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': csrfToken
      },
      body: JSON.stringify({ action: 'getListDatas' })
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
export const minPriceInput = document.getElementById('min-price');
export const labelminPriceInput = document.getElementById('label-min-price');
export const maxPriceInput = document.getElementById('max-price');
export const labelmaxPriceInput = document.getElementById('label-max-price');

export const cardsNantesDiv = document.getElementById('cards-nantes');
export const cardsLilleDiv = document.getElementById('cards-lille');
export const cardsBordeauxDiv = document.getElementById('cards-bordeaux');
export const cardsParisDiv = document.getElementById('cards-paris');
export const cardsToulouseDiv = document.getElementById('cards-toulouse');


/**********************/

// LISTENER SUR TABS //

/*********************/
const tabs = document.querySelectorAll('.stores');
tabs.forEach(tab => {
  tab.addEventListener('click', event => {
    event.preventDefault();
    resetFilters();
  });
});


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

minPriceInput.addEventListener('input', function(event) {
  if (event.target.value > maxPriceInput.value) {
    event.target.value = maxPriceInput.value;
  } else if (event.target.value < minPriceInput.getAttribute('min')) {
    event.target.value = minPriceInput.getAttribute('min');
  }
  labelminPriceInput.textContent = event.target.value + " €";
  searchGame(1, true)
});
maxPriceInput.addEventListener('input', function(event) {
  if (event.target.value < minPriceInput.value) {
    event.target.value = minPriceInput.value;
  } else if (event.target.value > maxPriceInput.getAttribute('max')) {
    event.target.value = maxPriceInput.getAttribute('max');
  }
  labelmaxPriceInput.textContent = event.target.value + " €";
  searchGame(1, true)
});


/********************************/

// AFFICHAGE DES CARTES DE JEUX //

/********************************/
export function prepareHtmlCard() {
  const activeTab = document.querySelector('.stores.active');
  const activeTabId = activeTab.getAttribute('id');

  switch (activeTabId) {
    case 'nantes-tab':
      searchGame(1, true);
      document.getElementById('loading-nantes').classList.add('visually-hidden');
      break;
    case 'lille-tab':
      searchGame(1, true);
      document.getElementById('loading-lille').classList.add('visually-hidden');
      break;
    case 'bordeaux-tab':
      searchGame(1, true);
      document.getElementById('loading-bordeaux').classList.add('visually-hidden');
      break;
    case 'paris-tab':
      searchGame(1, true);
      document.getElementById('loading-paris').classList.add('visually-hidden');
      break;
    case 'toulouse-tab':
      searchGame(1, true);
      document.getElementById('loading-toulouse').classList.add('visually-hidden');
      break;
  }
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
  minPriceInput.value = '0.00';
  labelminPriceInput.textContent = minPriceInput.value + " €";
  maxPriceInput.value = '1000.00';
  labelmaxPriceInput.textContent = maxPriceInput.value + " €";
  paginationSelect.selectedIndex = 1;
  prepareHtmlCard(gameDatas['datas']);
}
