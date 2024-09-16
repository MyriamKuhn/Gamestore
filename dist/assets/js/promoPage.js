let gameDatas = null;

function getDatas() {
  fetch('index.php?controller=datas',
    {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ action: 'getPromoDatas' })
    })
    .then(response => response.json())
    .then(datas => {
      gameDatas = datas;
      createHtmlCard(gameDatas);
    })
    .catch(error => console.error('Erreur : ' + error));
}

getDatas();

// Récupération de l'emplacement où afficher les cartes de jeux
const cardsDiv = document.getElementById('cards');

// Récupération de l'image Spotlight dans gameDatas
function getSpotlightImg(datas) {
  let spotlight = datas.find(data => data.includes('spotlight'));
  return spotlight;
}

// Fonction pour afficher les cartes de jeux
function createHtmlCard() {
  cardsDiv.innerHTML = '';
  gameDatas['datas'].forEach(game => {
    const gameCard = document.createElement('div');
    gameCard.classList.add('card', 'gamestore-card');
    gameCard.style.width = '18rem';
    cardsDiv.appendChild(gameCard);

    const cardImgBlock = document.createElement('div');
    cardImgBlock.classList.add('card-img-block');
    gameCard.appendChild(cardImgBlock);

    const cardImg = document.createElement('img');
    cardImg.classList.add('card-img-top');
    cardImg.src = './uploads/games/' + getSpotlightImg(game['images']);
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
    cardPriceText.textContent = (game['price'] * (1 - game['discount_rate'])).toFixed(2) + ' €';
    containerPriceText.appendChild(cardPriceText);

    const cardPriceOld = document.createElement('div');
    cardPriceOld.classList.add('text-decoration-line-through');
    cardPriceOld.textContent = game['price'] + ' €';
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

    emptyDiv = document.createElement('div');
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