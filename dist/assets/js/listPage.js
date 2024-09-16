/*******************************/

// IMPORT DES FONCTIONS UTILES //

/******************************/
import { searchGame, resetFilters } from './listFilters.js';


/********************/

// START PAGE LIST  //

/********************/
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
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ action: 'getListDatas' })
    })
    .then(response => response.json())
    .then(datas => {
      gameDatas = datas;
      prepareHtmlCard(gameDatas['datas']);
    })
    .catch(error => console.error('Erreur : ' + error));
}


/**************/

// VARIABLES //

/**************/
export const searchInput = document.getElementById('search-game');
export const minPriceInput = document.getElementById('min-price');
export const labelminPriceInput = document.getElementById('label-min-price');
export const maxPriceInput = document.getElementById('max-price');
export const labelmaxPriceInput = document.getElementById('label-max-price');
export const genresChecks = document.querySelectorAll('.genre-filter');
export const platformsChecks = document.querySelectorAll('.platform-filter');
export const resetButton = document.getElementById('reset-filters');
export const paginationSelect = document.getElementById('games-per-page');

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


/********************************/

// AFFICHAGE DES CARTES DE JEUX //

/********************************/
export function prepareHtmlCard(datas) {
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


/**********************************/

// MISE EN PLACE DE LA PAGINATION //

/**********************************/
export function constructPagination(totalPages) {
  const paginationContainer = document.getElementById('pagination-container');
  paginationContainer.innerHTML = '';

  const paginationPacman = document.createElement('div');
  paginationPacman.classList.add('pagination-pacman');
  paginationContainer.appendChild(paginationPacman);

  for (let i=0; i<totalPages; i++) {
    const input = document.createElement('input');
    input.classList.add('input-pacman');
    input.id = `dot-${i+1}`;
    input.type = 'radio';
    input.name = 'dots';
      if (i === 0) {
        input.checked = 'checked';
      }
    input.addEventListener('change', function() {
      searchGame(i+1, false);
    });
    paginationPacman.appendChild(input);

    const label = document.createElement('label');
    label.classList.add('label-pacman');
    label.htmlFor = `dot-${i+1}`;
    paginationPacman.appendChild(label);
  }
  const pacman = document.createElement('div');
  pacman.classList.add('pacman');
  paginationPacman.appendChild(pacman);
}