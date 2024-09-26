/***********/

// IMPORTS //

/**********/
import { getImgByName } from "./utils.js";
import { cardsNantesDiv, cardsLilleDiv, cardsBordeauxDiv, cardsParisDiv, cardsToulouseDiv } from './listPage.js';
import { searchGame } from './listFilters.js';


/***************************************/

// CREATION DES CARTES LISTE DES JEUX //

/***************************************/
export function createHtmlCard(datas, city) {
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
    console.log(userId);
    console.log(storeId);
  }

  switch (city) {
    case 'nantes':
      cardsNantesDiv.innerHTML = '';
      if (isUser == true && storeId == 1) {
        isLogged = true;
      } 
      break;
    case 'lille':
      cardsLilleDiv.innerHTML = '';
      if (isUser == true && storeId == 2) {
        isLogged = true;
      } 
      break;
    case 'bordeaux':
      cardsBordeauxDiv.innerHTML = '';
      if (isUser == true && storeId == 3) {
        isLogged = true;
      } 
      break;
    case 'paris':
      cardsParisDiv.innerHTML = '';
      if (isUser == true && storeId == 4) {
        isLogged = true;
      } 
      break;
    case 'toulouse':
      cardsToulouseDiv.innerHTML = '';
      if (isUser == true && storeId == 5) {
        isLogged = true;
      } 
      break;
  }
  datas.forEach(game => {
    const gameCard = document.createElement('div');
    gameCard.classList.add('card', 'gamestore-card');
    gameCard.style.width = '18rem';

    switch (city) {
      case 'nantes':
        cardsNantesDiv.appendChild(gameCard);
        break;
      case 'lille':
        cardsLilleDiv.appendChild(gameCard);
        break;
      case 'bordeaux':
        cardsBordeauxDiv.appendChild(gameCard);
        break;
      case 'paris':
        cardsParisDiv.appendChild(gameCard);
        break;
      case 'toulouse':
        cardsToulouseDiv.appendChild(gameCard);
        break;
    }

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

    const cardPriceList = document.createElement('div');
    cardPriceList.classList.add('card-price-list', 'm-0');
    cardPriceList.textContent = game['platform_price'] + ' €';
    cardList.appendChild(cardPriceList);

    if (game['is_reduced'] === 1) {
      const cardPercent = document.createElement('div');
      cardPercent.textContent = '-' + game['discount_rate'] * 100 + '% ';
      cardList.appendChild(cardPercent);

      const cardPriceOld = document.createElement('span');
      cardPriceOld.classList.add('text-decoration-line-through');
      cardPriceOld.textContent = (game['platform_price'] * (1 - game['discount_rate'])).toFixed(2) + ' €';
      cardPercent.appendChild(cardPriceOld);
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
      //cardCartImg.addEventListener('click', function() {
        //addToCart(game['game_id']);
      //});
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