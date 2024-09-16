/*******************************/

// IMPORT DES FONCTIONS UTILES //

/******************************/
import { getSpotlightImg } from "./utils.js";
import { cardsNantesDiv, cardsLilleDiv, cardsBordeauxDiv, cardsParisDiv, cardsToulouseDiv } from './listPage.js';


/***************************************/

// CREATION DES CARTES LISTE DES JEUX //

/***************************************/
export function createHtmlCard(datas, city) {
  switch (city) {
    case 'nantes':
      cardsNantesDiv.innerHTML = '';
      break;
    case 'lille':
      cardsLilleDiv.innerHTML = '';
      break;
    case 'bordeaux':
      cardsBordeauxDiv.innerHTML = '';
      break;
    case 'paris':
      cardsParisDiv.innerHTML = '';
      break;
    case 'toulouse':
      cardsToulouseDiv.innerHTML = '';
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
    cardImg.src = './uploads/games/' + getSpotlightImg(game['images']);
    cardImg.alt = game['game_name'];
    cardImg.loading = 'lazy';
    cardImgBlock.appendChild(cardImg);

    if (game['is_reduced'] === 1) {
      const badge = document.createElement('span');
      badge.classList.add('badge', 'position-absolute', 'badge', 'rounded-pill', 'text-uppercase', 'py-1', 'px-2');
      badge.textContent = 'Promo';
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
    cardCartImg.classList.add('bi', 'bi-cart2', 'fs-2', 'navbar-cart-img', 'navbar-cart');
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