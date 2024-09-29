/***********/

// IMPORTS //

/**********/
import { secureInput } from './utils.js';
import { searchInput, genresChecks, platformsChecks, paginationSelect } from './variables.js';
import { cardsDiv, gameDatas, storesChecks } from './promoPage.js';
import { constructPagination, createHtmlCard } from './promoCardsCreate.js';


/******************************/

// RECHERCHE D'APRES FILTRES //

/*****************************/
export function searchGame(currentPage, isFirstTime) {
  const searchedName = secureInput(searchInput.value.toLowerCase());
  const genres = [];
  genresChecks.forEach(genre => {
    if (genre.checked) {
      genres.push(secureInput(genre.value));
    }
  });
  const platforms = [];
  platformsChecks.forEach(platform => {
    if (platform.checked) {
      platforms.push(secureInput(platform.value));
    }
  });
  const stores = [];
  storesChecks.forEach(store => {
    if (store.checked) {
      stores.push(secureInput(store.value));
    }
  });
  searchGameByAll(gameDatas['datas'], searchedName, genres, platforms, stores, currentPage, isFirstTime);
}

function searchGameByAll(datas, searchedName, genres, platforms, stores, currentPage, isFirstTime) {
  let searchResult = [];
  searchResult = datas.filter(game => game['game_name'].toLowerCase().includes(searchedName));

  if (searchedName === '') {
    searchResult = datas;
  }

  if (genres.length > 0) {
    const result = searchResult.filter(game => game['genre'].some(genre => genres.includes(genre)));
    searchResult = result;
  }

  if (platforms.length > 0) {
    const result = searchResult.filter(game => platforms.includes(game['platform_name']));
    searchResult = result;
  }

  if (stores.length > 0) {
    const result = searchResult.filter(game => stores.includes(game['store_location']));
    searchResult = result;
  }

  if (searchResult.length === 0) {
    const notFound = document.createElement('h4');
    notFound.classList.add('text-center', 'text-uppercase', 'fs-5');
    notFound.textContent = 'Aucun jeu ne correspond à votre recherche';
    cardsDiv.innerHTML = '';
    cardsDiv.appendChild(notFound);
    document.getElementById('pagination-container').innerHTML = '';
  } else {
    pagination(searchResult, currentPage, isFirstTime);
  }
}


/****************************/

// PAGINATION DES RESULTATS //

/****************************/
function pagination(searchResult, currentPage, isFirstTime) {
  const gamesPerPage = parseInt(paginationSelect.value);
  const totalGames = searchResult.length;
  const totalPages = Math.ceil(totalGames / gamesPerPage);
  if (isFirstTime && totalPages > 1) {
    constructPagination(totalPages);
    showPage(currentPage);
  } else if (totalPages === 1) {
    document.getElementById('pagination-container').innerHTML = '';
    showPage(currentPage);
  } else {
    showPage(currentPage);
  }

  function showPage(currentPage) {
    const start = (currentPage - 1) * gamesPerPage;
    const end = start + gamesPerPage;
  
    const paginatedGames = searchResult.slice(start, end);
    createHtmlCard(paginatedGames);
  }

  window.scrollTo({ top: 0, behavior: 'smooth' });
}