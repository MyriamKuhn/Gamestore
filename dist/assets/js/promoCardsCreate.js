/***********/

// IMPORTS //

/**********/
import { getImgByName } from "./utils.js";
import { cardsDiv } from './promoPage.js';
import { searchGame } from './promoFilters.js';


/***************************************/

// CREATION DES CARTES PROMO DES JEUX //

/***************************************/
export function createHtmlCard(datas) {
  cardsDiv.innerHTML = '';
  datas.forEach(game => {
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

    const badge = document.createElement('span');
    badge.classList.add('badge', 'position-absolute', 'badge', 'rounded-pill', 'text-uppercase', 'py-1', 'px-2');
    badge.textContent = 'Promo';
    cardImgBlock.appendChild(badge);

    const cardBody = document.createElement('div');
    cardBody.classList.add('card-body', 'card-body-promos', 'pt-0');
    gameCard.appendChild(cardBody);

    const cardTitle = document.createElement('div');
    cardTitle.classList.add('card-title', 'text-uppercase', 'text-center', 'pb-2');
    cardTitle.textContent = game['game_name'];
    cardBody.appendChild(cardTitle);

    const cardPrice = document.createElement('div');
    cardPrice.classList.add('d-flex', 'justify-content-center');
    cardBody.appendChild(cardPrice);

    const cardPercent = document.createElement('div');
    cardPercent.classList.add('card-percent');
    cardPercent.textContent = game['discount_rate'] * 100;
    cardPrice.appendChild(cardPercent);

    const imgPercent = document.createElement('img');
    imgPercent.src = './assets/images/percent_icon.svg';
    imgPercent.alt = 'Image représentant un pourcentage';
    cardPrice.appendChild(imgPercent);

    const containerPriceText = document.createElement('div');
    containerPriceText.classList.add('d-flex', 'flex-column', 'align-items-center', 'justify-content-center', 'ps-3');
    cardPrice.appendChild(containerPriceText);

    const cardPriceText = document.createElement('div');
    cardPriceText.classList.add('card-price', 'm-0');
    cardPriceText.textContent = (game['platform_price'] * (1 - game['discount_rate'])).toFixed(2) + ' €';
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
    cardPlatform.src = './assets/images/platforms/' + game['platform_name'].replace(/\s+/g, '-').toLowerCase() + '.svg';
    cardPlatform.alt = game['platform_name'];
    cardPlatform.width = '25';
    containerDiv.appendChild(cardPlatform);

    const emptyDiv = document.createElement('div');
    gameInfos.appendChild(emptyDiv);

    const cardPegi = document.createElement('img');
    cardPegi.src = './assets/images/pegi/' + game['pegi_name'] + '.jpg';
    cardPegi.alt = game['pegi_name'];
    cardPegi.width = '30';
    emptyDiv.appendChild(cardPegi);

    const cardFooter = document.createElement('div');
    cardFooter.classList.add('row', 'row-cols-1', 'justify-content-center');
    gameCard.appendChild(cardFooter);

    const cardLink = document.createElement('a');
    cardLink.href = 'index.php?controller=games&action=show&id=' + game['game_id'];
    cardLink.classList.add('news-card-footer', 'text-uppercase', 'py-3', 'text-center', 'text-decoration-none');
    cardLink.textContent = 'Acheter';
    cardFooter.appendChild(cardLink);
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