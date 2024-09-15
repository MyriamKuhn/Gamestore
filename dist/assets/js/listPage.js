// Récupération des données des jeux dans le div data-container
const dataContainer = document.getElementById('data-container');
const gameNantesDatas = JSON.parse(dataContainer.getAttribute('data-games-nantes'));
const gameLilleDatas = JSON.parse(dataContainer.getAttribute('data-games-lille'));
const gameBordeauxDatas = JSON.parse(dataContainer.getAttribute('data-games-bordeaux'));
const gameParisDatas = JSON.parse(dataContainer.getAttribute('data-games-paris'));
const gameToulouseDatas = JSON.parse(dataContainer.getAttribute('data-games-toulouse'));

// Récupération de l'emplacement où afficher les cartes de jeux
const cardsNantesDiv = document.getElementById('cards-nantes');
const cardsLilleDiv = document.getElementById('cards-lille');
const cardsBordeauxDiv = document.getElementById('cards-bordeaux');
const cardsParisDiv = document.getElementById('cards-paris');
const cardsToulouseDiv = document.getElementById('cards-toulouse');

//Au démarrage de la page, on appelle les fonctions pour afficher les jeux sur chaque tab
if (gameNantesDatas) {
  createHtmlCard(gameNantesDatas);
};
if (gameLilleDatas) {
  createHtmlCard(gameLilleDatas);
};
if (gameBordeauxDatas) {
  createHtmlCard(gameBordeauxDatas);
};
if (gameParisDatas) {
  createHtmlCard(gameParisDatas);
};
if (gameToulouseDatas) {
  createHtmlCard(gameToulouseDatas);
};

// Récupération de l'image Spotlight dans gameDatas
function getSpotlightImg(datas) {
  let spotlight = datas.find(data => data.includes('spotlight'));
  return spotlight;
}

// Fonction pour afficher les cartes de jeux
function createHtmlCard(datas) {
  switch (true) {
    case datas === gameNantesDatas:
      cardsNantesDiv.innerHTML = '';
      break;
    case datas === gameLilleDatas:
      cardsLilleDiv.innerHTML = '';
      break;
    case datas === gameBordeauxDatas:
      cardsBordeauxDiv.innerHTML = '';
      break;
    case datas === gameParisDatas:
      cardsParisDiv.innerHTML = '';
      break;
    case datas === gameToulouseDatas:
      cardsToulouseDiv.innerHTML = '';
      break;
  }
  datas.forEach(game => {
    const gameCard = document.createElement('div');
    gameCard.classList.add('card', 'gamestore-card');
    gameCard.style.width = '18rem';
    switch (true) {
      case datas === gameNantesDatas:
        cardsNantesDiv.appendChild(gameCard);
        break;
      case datas === gameLilleDatas:
        cardsLilleDiv.appendChild(gameCard);
        break;
      case datas === gameBordeauxDatas:
        cardsBordeauxDiv.appendChild(gameCard);
        break;
      case datas === gameParisDatas:
        cardsParisDiv.appendChild(gameCard);
        break;
      case datas === gameToulouseDatas:
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
      cardSubtitle.textContent += genre + ', ';
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