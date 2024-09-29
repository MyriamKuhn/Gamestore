/***********/

// IMPORTS //

/**********/
import { cardsNantesDiv, cardsLilleDiv, cardsBordeauxDiv, cardsParisDiv, cardsToulouseDiv, gameDatas, resetFilters, minPriceInput, maxPriceInput } from './listPage.js';
import { secureInput } from './utils.js';
import { createHtmlCard, constructPagination } from './listCardsCreate.js';
import { searchInput, genresChecks, platformsChecks, paginationSelect } from './variables.js';


/******************************/

// RECHERCHE D'APRES FILTRES //

/*****************************/
export function searchGame(currentPage, isFirstTime) {
  const activeTab = document.querySelector('.stores.active');
  const activeTabId = activeTab.getAttribute('id');
  const searchedName = secureInput(searchInput.value.toLowerCase());
  const minPrice = parseFloat(minPriceInput.value);
  const maxPrice = parseFloat(maxPriceInput.value);
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

  switch (activeTabId) {
    case 'nantes-tab':
      searchGameByAll(gameDatas['datas']['datasNantes'], searchedName, genres, platforms, minPrice, maxPrice, 'nantes', currentPage, isFirstTime);
      break;
    case 'lille-tab':
      searchGameByAll(gameDatas['datas']['datasLille'], searchedName, genres, platforms, minPrice, maxPrice, 'lille', currentPage, isFirstTime);
      break;
    case 'bordeaux-tab':
      searchGameByAll(gameDatas['datas']['datasBordeaux'], searchedName, genres, platforms, minPrice, maxPrice, 'bordeaux', currentPage, isFirstTime);
      break;
    case 'paris-tab':
      searchGameByAll(gameDatas['datas']['datasParis'], searchedName, genres, platforms, minPrice, maxPrice, 'paris', currentPage, isFirstTime);
      break;
    case 'toulouse-tab':
      searchGameByAll(gameDatas['datas']['datasToulouse'], searchedName, genres, platforms, minPrice, maxPrice, 'toulouse', currentPage, isFirstTime);
      break;
  }
}



function searchGameByAll(datas, searchedName, genres, platforms, minPrice, maxPrice, city, currentPage, isFirstTime) {
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

  if (minPrice !== '' && maxPrice !== '') {
    searchResult = searchResult.filter(game => game['platform_price'] >= minPrice && game['platform_price'] <= maxPrice);
  }
  
  if (searchResult.length === 0) {
    const notFound = document.createElement('h4');
    notFound.classList.add('text-center', 'text-uppercase', 'fs-5');
    notFound.textContent = 'Aucun jeu ne correspond à votre recherche';
    document.getElementById('pagination-container').innerHTML = '';
    switch (city) {
      case 'nantes':
        cardsNantesDiv.innerHTML = '';
        cardsNantesDiv.appendChild(notFound);
        break;
      case 'lille':
        cardsLilleDiv.innerHTML = '';
        cardsLilleDiv.appendChild(notFound);
        break;
      case 'bordeaux':
        cardsBordeauxDiv.innerHTML = '';
        cardsBordeauxDiv.appendChild(notFound);
        break;
      case 'paris':
        cardsParisDiv.innerHTML = '';
        cardsParisDiv.appendChild(notFound);
        break;
      case 'toulouse':
        cardsToulouseDiv.innerHTML = '';
        cardsToulouseDiv.appendChild(notFound);
        break;
    }
  } else {
    pagination(searchResult, city, currentPage, isFirstTime);
  }
}


/****************************/

// PAGINATION DES RESULTATS //

/****************************/
function pagination(searchResult, city, currentPage, isFirstTime) {
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
    createHtmlCard(paginatedGames, city);
  }
  
  window.scrollTo({ top: 0, behavior: 'smooth' });
}
