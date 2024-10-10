/***********/

// IMPORTS //

/**********/
import { validateJSONStructure, secureInput, getImgByName, showCart, htmlEntityDecode } from '../utils.js';


/**********************/

// VARIABLES GLOBALES //

/**********************/
const searchInput = document.getElementById('search-game');
const genresChecks = document.querySelectorAll('.genre-filter');
const platformsChecks = document.querySelectorAll('.platform-filter');
const resetButton = document.getElementById('reset-filters');
const paginationSelect = document.getElementById('games-per-page');
const cardsDiv = document.getElementById('cards');
const storesChecks = document.querySelectorAll('.store-filter');
const loadingDiv = document.getElementById('loading');
let dataResults = [];


/****************/

// AU DEMARRAGE //

/****************/
document.addEventListener('DOMContentLoaded', function () {
  fetchDatas();
  
  searchInput.addEventListener('search', () => {
    searchResults(1, true);
  });
  searchInput.addEventListener('input', () => {
    searchResults(1, true);
  });
  paginationSelect.addEventListener('change', () => {
    searchResults(1, true);
  });
  resetButton.addEventListener('click', () => {
    resetFilters();
  });
  
  genresChecks.forEach(genre => {
    genre.addEventListener('change', () => {
      searchResults(1, true);
    });
  });
  
  platformsChecks.forEach(platform => {
    platform.addEventListener('change', () => {
      searchResults(1, true);
    });
  });

  storesChecks.forEach(store => {
    store.addEventListener('change', () => {
      searchResults(1, true);
    });
  });
});


/**********************/

// RESET DES FILTRES //

/*********************/
function resetFilters() {
  searchInput.value = '';
  genresChecks.forEach(genre => {
    genre.checked = false;
  });
  platformsChecks.forEach(platform => {
    platform.checked = false;
  });
  storesChecks.forEach(store => {
    store.checked = false;
  });
  paginationSelect.selectedIndex = 1;
  searchResults(1, true);
}


/**********************/

// FETCH DES DONNEES //

/*********************/
function fetchDatas() {
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
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
        dataResults = data.datas;
        searchResults(1, true);
      } else {
        console.error('Format inattendu des données');
      }
    })
    .catch(error => console.error('Erreur : ' + error));
}


/**************************/

// PREPARATION DES CARTES //

/**************************/
function searchResults(currentPage, isFirstTime) {
  // Récupération des valeurs
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

  // Résultat des filtres
  let searchResult = [];
  searchResult = dataResults.filter(game => htmlEntityDecode(game['game_name']).toLowerCase().includes(searchedName));

  if (searchedName === '') {
    searchResult = dataResults;
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
    return createHtmlCard(paginatedGames);
  }
  
  window.scrollTo({ top: 0, behavior: 'smooth' });
}


/***************************************/

// CREATION DES CARTES LISTE DES JEUX //

/***************************************/
function createHtmlCard(datas) {
  let isUser = false;
  let storeId = 0;
  let userId = 0;
  
  if (document.getElementById('sessionDataId')) {
    const sessionDivId = document.getElementById('sessionDataId');
    userId = sessionDivId.getAttribute('data-session-user');
    const sessionDivStore = document.getElementById('sessionDataStore');
    storeId = sessionDivStore.getAttribute('data-session-store');
    isUser = true;
  }

  cardsDiv.innerHTML = '';
      
  datas.forEach(game => {
    let priceToPay = 0;
    let isLogged = false;

    switch (game['store_location']) {
      case 'Nantes':
        if (isUser == true && storeId == 1) {
          isLogged = true;
        } 
        break;
      case 'Lille':
        if (isUser == true && storeId == 2) {
          isLogged = true;
        } 
        break;
      case 'Bordeaux':
        if (isUser == true && storeId == 3) {
          isLogged = true;
        } 
        break;
      case 'Paris':
        if (isUser == true && storeId == 4) {
          isLogged = true;
        } 
        break;
      case 'Toulouse':
        if (isUser == true && storeId == 5) {
          isLogged = true;
        } 
        break;
    }

    const gameCard = document.createElement('div');
    gameCard.classList.add('card', 'gamestore-card');
    gameCard.style.width = '18rem';
    cardsDiv.appendChild(gameCard);

    const cardImgBlock = document.createElement('div');
    cardImgBlock.classList.add('card-img-block');
    gameCard.appendChild(cardImgBlock);

    const cardImg = document.createElement('img');
    cardImg.classList.add('card-img-top');
    cardImg.src = '/uploads/games/' + getImgByName(game['images']);
    cardImg.alt = htmlEntityDecode(game['game_name']);
    cardImg.loading = 'lazy';
    cardImgBlock.appendChild(cardImg);

    const badge = document.createElement('span');
    badge.classList.add('badge', 'position-absolute', 'badge', 'rounded-pill', 'text-uppercase', 'py-1', 'px-2');
    badge.textContent = 'Promo';
    cardImgBlock.appendChild(badge);

    if (game['is_new'] === 1) {
      const badge = document.createElement('span');
      badge.classList.add('badge-new', 'position-absolute', 'rounded-pill', 'text-uppercase', 'py-1', 'px-2');
      badge.textContent = 'Nouveauté';
      cardImgBlock.appendChild(badge);
    }

    const cardBody = document.createElement('div');
    cardBody.classList.add('card-body', 'card-body-promos', 'pt-0');
    gameCard.appendChild(cardBody);

    const emtpyDiv = document.createElement('div');
    cardBody.appendChild(emtpyDiv);

    const cardTitle = document.createElement('div');
    cardTitle.classList.add('card-title', 'text-uppercase', 'text-center', 'p-0', 'm-0');
    cardTitle.textContent = htmlEntityDecode(game['game_name']);
    emtpyDiv.appendChild(cardTitle);

    const cardSubtitle = document.createElement('p');
    cardSubtitle.classList.add('card-subtitle', 'text-center', 'm-0');
    game['genre'].forEach(genre => {
      cardSubtitle.textContent += genre + ' ';
    });
    emtpyDiv.appendChild(cardSubtitle);

    const cardPrice = document.createElement('div');
    cardPrice.classList.add('d-flex', 'justify-content-center');
    cardBody.appendChild(cardPrice);

    const cardPercent = document.createElement('div');
    cardPercent.classList.add('card-percent');
    cardPercent.textContent = game['discount_rate'] * 100;
    cardPrice.appendChild(cardPercent);

    const imgPercent = document.createElement('img');
    imgPercent.src = '/assets/images/percent_icon.svg';
    imgPercent.alt = 'Image représentant un pourcentage';
    cardPrice.appendChild(imgPercent);

    const containerPriceText = document.createElement('div');
    containerPriceText.classList.add('d-flex', 'flex-column', 'align-items-center', 'justify-content-center', 'ps-3');
    cardPrice.appendChild(containerPriceText);

    const cardPriceText = document.createElement('div');
    cardPriceText.classList.add('card-price', 'm-0');
    priceToPay = (game['platform_price'] * (1 - game['discount_rate'])).toFixed(2);
    cardPriceText.textContent = priceToPay + ' €';
    containerPriceText.appendChild(cardPriceText);

    const cardPriceOld = document.createElement('div');
    cardPriceOld.classList.add('text-decoration-line-through');
    cardPriceOld.textContent = game['platform_price'] + ' €';
    containerPriceText.appendChild(cardPriceOld);

    const cardLocation = document.createElement('h5');
    cardLocation.classList.add('text-center');
    cardLocation.textContent = 'Uniquement à ' + game['store_location'];
    cardBody.appendChild(cardLocation);

    const gameInfos = document.createElement('div');
    gameInfos.classList.add('d-flex', 'justify-content-between', 'align-items-center');
    cardBody.appendChild(gameInfos);

    const containerDiv = document.createElement('div');
    gameInfos.appendChild(containerDiv);

    const cardPlatform = document.createElement('img');
    cardPlatform.src = '/assets/images/platforms/' + game['platform_name'].replace(/\s+/g, '-').toLowerCase() + '.svg';
    cardPlatform.alt = game['platform_name'];
    cardPlatform.width = '25';
    cardPlatform.classList.add('me-3');
    containerDiv.appendChild(cardPlatform);

    const cardPegi = document.createElement('img');
    cardPegi.src = '/assets/images/pegi/' + game['pegi_name'] + '.jpg';
    cardPegi.alt = game['pegi_name'];
    cardPegi.width = '30';
    containerDiv.appendChild(cardPegi);

    const cardCart = document.createElement('div');
    gameInfos.appendChild(cardCart);

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

    const cardFooter = document.createElement('div');
    cardFooter.classList.add('row', 'row-cols-1', 'justify-content-center');
    gameCard.appendChild(cardFooter);

    const cardLink = document.createElement('a');
    cardLink.href = 'index.php?controller=games&action=show&id=' + game['game_id'];
    cardLink.classList.add('news-card-footer', 'text-uppercase', 'py-3', 'text-center', 'text-decoration-none');
    cardLink.textContent = 'Plus d\'infos';
    cardFooter.appendChild(cardLink);
  });
  loadingDiv.classList.add('visually-hidden');
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
      searchResults(i+1, false);
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