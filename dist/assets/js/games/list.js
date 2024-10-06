/***********/

// IMPORTS //

/**********/
import { validateJSONStructure, secureInput, getImgByName, showCart } from '../utils.js';


/**********************/

// VARIABLES GLOBALES //

/**********************/
const searchInput = document.getElementById('search-game');
const genresChecks = document.querySelectorAll('.genre-filter');
const platformsChecks = document.querySelectorAll('.platform-filter');
const resetButton = document.getElementById('reset-filters');
const paginationSelect = document.getElementById('games-per-page');
const minPriceInput = document.getElementById('min-price');
const labelminPriceInput = document.getElementById('label-min-price');
const maxPriceInput = document.getElementById('max-price');
const labelmaxPriceInput = document.getElementById('label-max-price');
let dataResults = [];
let cardsDivId = '';
let loadingId = '';
let storeTabId = 0;


/****************/

// AU DEMARRAGE //

/****************/
document.addEventListener('DOMContentLoaded', function () {
  cardsDivId = 'cards-nantes';
  loadingId = 'loading-nantes';
  storeTabId = 1;
  fetchDatas('getListDatasNantes', cardsDivId, 1, true, loadingId, storeTabId);

  const tabs = document.querySelectorAll('a[data-bs-toggle="tab"]');
  tabs.forEach(tab => {
    tab.addEventListener('shown.bs.tab', event => {
      const target = event.target.getAttribute('href').slice(1);
      switch (target) {
        case 'nantes':
          cardsDivId = 'cards-nantes';
          loadingId = 'loading-nantes';
          storeTabId = 1;
          document.getElementById(loadingId).classList.remove('visually-hidden');
          document.getElementById(cardsDivId).innerHTML = '';
          if (document.getElementById('pagination-container')) {
            document.getElementById('pagination-container').innerHTML = '';
          }
          resetFilters(true);
          fetchDatas('getListDatasNantes', cardsDivId, 1, true, loadingId, storeTabId);
          break;
        case 'lille':
          cardsDivId = 'cards-lille';
          loadingId = 'loading-lille';
          storeTabId = 2;
          document.getElementById(loadingId).classList.remove('visually-hidden');
          document.getElementById(cardsDivId).innerHTML = '';
          if (document.getElementById('pagination-container')) {
            document.getElementById('pagination-container').innerHTML = '';
          }
          resetFilters(true);
          fetchDatas('getListDatasLille', cardsDivId, 1, true, loadingId, storeTabId);
          break;
        case 'bordeaux':
          cardsDivId = 'cards-bordeaux';
          loadingId = 'loading-bordeaux';
          storeTabId = 3;
          document.getElementById(loadingId).classList.remove('visually-hidden');
          document.getElementById(cardsDivId).innerHTML = '';
          if (document.getElementById('pagination-container')) {
            document.getElementById('pagination-container').innerHTML = '';
          }
          resetFilters(true);
          fetchDatas('getListDatasBordeaux', cardsDivId, 1, true, loadingId, storeTabId);
          break;
        case 'paris':
          cardsDivId = 'cards-paris';
          loadingId = 'loading-paris';
          storeTabId = 4;
          document.getElementById(loadingId).classList.remove('visually-hidden');
          document.getElementById(cardsDivId).innerHTML = '';
          if (document.getElementById('pagination-container')) {
            document.getElementById('pagination-container').innerHTML = '';
          }
          resetFilters(true);
          fetchDatas('getListDatasParis', cardsDivId, 1, true, loadingId, storeTabId);
          break;
        case 'toulouse':
          cardsDivId = 'cards-toulouse';
          loadingId = 'loading-toulouse';
          storeTabId = 5;
          document.getElementById(loadingId).classList.remove('visually-hidden');
          document.getElementById(cardsDivId).innerHTML = '';
          if (document.getElementById('pagination-container')) {
            document.getElementById('pagination-container').innerHTML = '';
          }
          resetFilters(true);
          fetchDatas('getListDatasToulouse', cardsDivId, 1, true, loadingId, storeTabId);
          break;
      }
    });
  });

  searchInput.addEventListener('search', () => {
    searchResults(dataResults, cardsDivId, 1, true, loadingId, storeTabId);
  });
  searchInput.addEventListener('input', () => {
    searchResults(dataResults, cardsDivId, 1, true, loadingId, storeTabId);
  });
  paginationSelect.addEventListener('change', () => {
    searchResults(dataResults, cardsDivId, 1, true, loadingId, storeTabId);
  });
  resetButton.addEventListener('click', () => {
    resetFilters(false);
  });
  
  genresChecks.forEach(genre => {
    genre.addEventListener('change', () => {
      searchResults(dataResults, cardsDivId, 1, true, loadingId, storeTabId);
    });
  });
  
  platformsChecks.forEach(platform => {
    platform.addEventListener('change', () => {
      searchResults(dataResults, cardsDivId, 1, true, loadingId, storeTabId);
    });
  });
  
  minPriceInput.addEventListener('input', function(event) {
    if (event.target.value > maxPriceInput.value) {
      event.target.value = maxPriceInput.value;
    } else if (event.target.value < minPriceInput.getAttribute('min')) {
      event.target.value = minPriceInput.getAttribute('min');
    }
    labelminPriceInput.textContent = event.target.value + " €";
    searchResults(dataResults, cardsDivId, 1, true, loadingId, storeTabId);
  });
  maxPriceInput.addEventListener('input', function(event) {
    if (event.target.value < minPriceInput.value) {
      event.target.value = minPriceInput.value;
    } else if (event.target.value > maxPriceInput.getAttribute('max')) {
      event.target.value = maxPriceInput.getAttribute('max');
    }
    labelmaxPriceInput.textContent = event.target.value + " €";
    searchResults(dataResults, cardsDivId, 1, true, loadingId, storeTabId);
  });
});


/**********************/

// RESET DES FILTRES //

/*********************/
function resetFilters(isChangingTab) {
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
  if (!isChangingTab) {
  searchResults(dataResults, cardsDivId, 1, true, loadingId, storeTabId);
  }
}


/**********************/

// FETCH DES DONNEES //

/*********************/
function fetchDatas(action, cardsDivId, currentPage, isFirstTime, loadingId, storeTabId) {
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  fetch('index.php?controller=datas',
    {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': csrfToken
      },
      body: JSON.stringify({ action: action })
    })
    .then(response => response.json())
    .then(data => {
      if (validateJSONStructure(data)) {
        dataResults = data.datas;
        searchResults(dataResults, cardsDivId, currentPage, isFirstTime, loadingId, storeTabId);
      } else {
        console.error('Format inattendu des données');
      }
    })
    .catch(error => console.error('Erreur : ' + error));
}


/**************************/

// PREPARATION DES CARTES //

/**************************/
function searchResults(datas, cardsDivId, currentPage, isFirstTime, loadingId, storeTabId) {
  // Récupération des valeurs
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

  // Résultat des filtres
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

    const cardsDiv = document.getElementById(cardsDivId);
    cardsDiv.innerHTML = '';
    cardsDiv.appendChild(notFound);
  } else {
    pagination(searchResult, currentPage, isFirstTime, document.getElementById(cardsDivId), loadingId, storeTabId);
  }
}


/****************************/

// PAGINATION DES RESULTATS //

/****************************/
function pagination(searchResult, currentPage, isFirstTime, cardsDiv, loadingId, storeTabId) {
  const gamesPerPage = parseInt(paginationSelect.value);
  const totalGames = searchResult.length;
  const totalPages = Math.ceil(totalGames / gamesPerPage);
  if (isFirstTime && totalPages > 1) {
    if (showPage(currentPage)) {
      constructPagination(totalPages);
    };
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
    return createHtmlCard(paginatedGames, cardsDiv, loadingId, storeTabId);
  }
  
  window.scrollTo({ top: 0, behavior: 'smooth' });
}


/***************************************/

// CREATION DES CARTES LISTE DES JEUX //

/***************************************/
function createHtmlCard(datas, cardsDiv, loadingId, storeTabId) {
  let isUser = false;
  let storeId = 0;
  let userId = 0;
  let isLogged = false;
  
  if (document.getElementById('sessionDataId')) {
    const sessionDivId = document.getElementById('sessionDataId');
    userId = sessionDivId.getAttribute('data-session-user');
    const sessionDivStore = document.getElementById('sessionDataStore');
    storeId = sessionDivStore.getAttribute('data-session-store');
    isUser = true;
  }

  cardsDiv.innerHTML = '';

  if (isUser == true && storeId == storeTabId) {
    isLogged = true;
  } 
      
  datas.forEach(game => {
    let priceToPay = 0;
    const gameCard = document.createElement('div');
    gameCard.classList.add('card', 'gamestore-card');
    gameCard.style.width = '18rem';

    cardsDiv.appendChild(gameCard);

    const cardImgBlock = document.createElement('div');
    cardImgBlock.classList.add('card-img-block');
    gameCard.appendChild(cardImgBlock);

    const cardImg = document.createElement('img');
    cardImg.classList.add('card-img-top');
    cardImg.src = './uploads/games/' + getImgByName(game['images']);
    cardImg.alt = game['game_name'];
    cardImg.loading = 'lazy';
    cardImgBlock.appendChild(cardImg);

    if (game['is_reduced'] === 1) {
      const badge = document.createElement('span');
      badge.classList.add('badge', 'position-absolute', 'badge', 'rounded-pill', 'text-uppercase', 'py-1', 'px-2');
      badge.textContent = 'Promo';
      cardImgBlock.appendChild(badge);
    }

    if (game['is_new'] === 1) {
      const badge = document.createElement('span');
      badge.classList.add('badge-new', 'position-absolute', 'rounded-pill', 'text-uppercase', 'py-1', 'px-2');
      badge.textContent = 'Nouveauté';
      cardImgBlock.appendChild(badge);
    }

    const cardBody = document.createElement('div');
    cardBody.classList.add('card-body', 'card-body-games', 'pt-0');
    gameCard.appendChild(cardBody);

    const emtpyDiv = document.createElement('div');
    cardBody.appendChild(emtpyDiv);

    const cardTitle = document.createElement('p');
    cardTitle.classList.add('card-title', 'text-uppercase', 'text-center', 'p-0', 'm-0');
    cardTitle.textContent = game['game_name'];
    emtpyDiv.appendChild(cardTitle);

    const cardSubtitle = document.createElement('p');
    cardSubtitle.classList.add('card-subtitle', 'text-center', 'm-0');
    game['genre'].forEach(genre => {
      cardSubtitle.textContent += genre + ' ';
    });
    emtpyDiv.appendChild(cardSubtitle);

    const cardContainer = document.createElement('div');
    cardContainer.classList.add('d-flex', 'justify-content-center');
    cardBody.appendChild(cardContainer);

    const cardList = document.createElement('div');
    cardList.classList.add('d-flex', 'flex-column', 'align-items-center', 'justify-content-center');
    cardContainer.appendChild(cardList);

    if (game['is_reduced'] === 1) {
      const cardPriceList = document.createElement('div');
      cardPriceList.classList.add('card-price-list', 'm-0');
      priceToPay = (game['platform_price'] * (1 - game['discount_rate'])).toFixed(2);
      cardPriceList.textContent = priceToPay + ' €';
      cardList.appendChild(cardPriceList);
    
      const cardPercent = document.createElement('div');
      cardPercent.textContent = '-' + game['discount_rate'] * 100 + '% ';
      cardList.appendChild(cardPercent);

      const cardPriceOld = document.createElement('span');
      cardPriceOld.classList.add('text-decoration-line-through');
      cardPriceOld.textContent = game['platform_price'] + ' €';
      cardPercent.appendChild(cardPriceOld);
    } else {
      const cardPriceList = document.createElement('div');
      cardPriceList.classList.add('card-price-list', 'm-0');
      priceToPay = game['platform_price'];
      cardPriceList.textContent = priceToPay + ' €';
      cardList.appendChild(cardPriceList);
    }

    const cardInfos = document.createElement('div');
    cardInfos.classList.add('d-flex', 'justify-content-between', 'align-items-center');
    cardBody.appendChild(cardInfos);

    const cardPlatform = document.createElement('div');
    cardInfos.appendChild(cardPlatform);

    const cardPlatformImg = document.createElement('img');
    cardPlatformImg.src = './assets/images/platforms/' + game['platform_name'].replace(/\s+/g, '-').toLowerCase() + '.svg';
    cardPlatformImg.alt = game['platform_name'];
    cardPlatformImg.width = '25';
    cardPlatformImg.classList.add('me-3');
    cardPlatform.appendChild(cardPlatformImg);

    const cardPegi = document.createElement('img');
    cardPegi.src = './assets/images/pegi/' + game['pegi_name'] + '.jpg';
    cardPegi.alt = game['pegi_name'];
    cardPegi.width = '30';
    cardPlatform.appendChild(cardPegi);

    const cardCart = document.createElement('div');
    cardInfos.appendChild(cardCart);

    const cardCartImg = document.createElement('i');
    if (isLogged) {
      cardCartImg.classList.add('bi', 'bi-cart2', 'fs-2', 'navbar-cart-img', 'navbar-cart');
      // Ajouter un event listener pour ajouter le jeu au panier
      cardCartImg.addEventListener('click', () => {
        try {
          const gameIdToSend = game['game_id'];
          const platformToSend = game['platform_name'];
          const priceToSend = priceToPay;
          let discountRateToSend = 0;
          let oldPriceToSend = 0;
          if (secureInput(game['platform_price']) !== priceToPay) {
            discountRateToSend = secureInput(game['discount_rate']);
            oldPriceToSend = secureInput(game['platform_price']);
          }
          const locationToSend = storeId;
          const userToSend = userId;
          const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

          const requestBody = JSON.stringify({
            action: 'addCart',
            gameId: gameIdToSend,
            platform: platformToSend,
            price: priceToSend,
            discountRate: discountRateToSend,
            oldPrice: oldPriceToSend,
            location: locationToSend,
            userId: userToSend
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
              if (datas.success) {
                showCart();
              } else if (datas.datas == "Votre compte est bloqu\u00e9, veuillez contacter un administrateur") {
                window.location.href = 'index.php?controller=auth&action=logout';
              } else {
                alert ('Le jeu n\'a pas pu être ajouté au panier');
              }
            })
            .catch(error => console.error('Erreur : ' + error));
        } catch (error) {
          console.error('Erreur : ' + error);
        }
      });
    } else {
    cardCartImg.classList.add('bi', 'bi-cart2', 'fs-2', 'navbar-cart-img', 'navbar-cart', 'disabled');
    }
    cardCart.appendChild(cardCartImg);

    const cardEnd = document.createElement('div');
    cardEnd.classList.add('row', 'row-cols-1', 'justify-content-center');
    gameCard.appendChild(cardEnd);

    const cardFooter = document.createElement('a');
    cardFooter.classList.add('news-card-footer', 'text-uppercase', 'py-3', 'text-center', 'text-decoration-none');
    cardFooter.href = 'index.php?controller=games&action=show&id=' + game['game_id'];
    cardFooter.textContent = 'Plus d\'infos';
    cardEnd.appendChild(cardFooter);
  });
  document.getElementById(loadingId).classList.add('visually-hidden');
  return true;
}


/**********************************/

// MISE EN PLACE DE LA PAGINATION //

/**********************************/
function constructPagination(totalPages) {
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
      searchResults(dataResults, cardsDivId, i+1, false, loadingId, storeTabId);
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