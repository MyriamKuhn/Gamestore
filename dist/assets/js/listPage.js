// Récupération des données de la base de données
let gameDatas = null;

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

getDatas();

// Event d'écoute sur les onglets
const tabs = document.querySelectorAll('.stores');
tabs.forEach(tab => {
  tab.addEventListener('click', () => {
    // reset des filtres
    searchInput.value = '';
    genresChecks.forEach(genre => {
      genre.checked = false;
    });
    platformsChecks.forEach(platform => {
      platform.checked = false;
    });
    minPriceInput.value = '';
    maxPriceInput.value = '';
    prepareHtmlCard(gameDatas['datas']);
  });
});

// Fonction pour rechercher par prix
function searchGameByPrice(datas, minPrice, maxPrice, city) {
  const searchResult = datas.filter(game => game['platform_price'] >= minPrice && game['platform_price'] <= maxPrice);
  if (searchResult.length === 0) {
    const notFound = document.createElement('h4');
    notFound.classList.add('text-center', 'text-uppercase', 'fs-5');
    notFound.textContent = 'Aucun jeu ne correspond à votre recherche';
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
    createHtmlCard(searchResult, city);
  }
}

// Recherche de jeux d'après prix
const minPriceInput = document.getElementById('min-price');
const maxPriceInput = document.getElementById('max-price');

minPriceInput.addEventListener('input', function(event) {
  if (event.target.value > maxPriceInput.value) {
    event.target.value = maxPriceInput.value;
  } else if (event.target.value < minPriceInput.getAttribute('data-min')) {
    event.target.value = minPriceInput.getAttribute('data-min');
  }
  searchGame()
});
maxPriceInput.addEventListener('input', function(event) {
  if (event.target.value < minPriceInput.value) {
    event.target.value = maxPriceInput.getAttribute('data-max');
  } else if (event.target.value > maxPriceInput.getAttribute('data-max')) {
    event.target.value = maxPriceInput.getAttribute('data-max');
  }
  searchGame()
});

function searchGamePriceInput() {
  const activeTab = document.querySelector('.stores.active');
  const activeTabId = activeTab.getAttribute('id');
  const minPrice = minPriceInput.value;
  const maxPrice = maxPriceInput.value;
  switch (activeTabId) {
    case 'nantes-tab':
      searchGameByPrice(gameDatas['datas']['datasNantes'], minPrice, maxPrice, 'nantes');
      break;
    case 'lille-tab':
      searchGameByPrice(gameDatas['datas']['datasLille'], minPrice, maxPrice, 'lille');
      break;
    case 'bordeaux-tab':
      searchGameByPrice(gameDatas['datas']['datasBordeaux'], minPrice, maxPrice, 'bordeaux');
      break;
    case 'paris-tab':
      searchGameByPrice(gameDatas['datas']['datasParis'], minPrice, maxPrice, 'paris');
      break;
    case 'toulouse-tab':
      searchGameByPrice(gameDatas['datas']['datasToulouse'], minPrice, maxPrice, 'toulouse');
      break;
  } 
}


// Fonction pour rechercher les jeux par nom
function searchGameByName(datas, searchValue, city) {
  const searchResult = datas.filter(game => game['game_name'].toLowerCase().includes(searchValue));
  if (searchValue === '') {
    createHtmlCard(datas, city);
  } else if (searchResult.length === 0) {
    const notFound = document.createElement('h4');
    notFound.classList.add('text-center', 'text-uppercase', 'fs-5');
    notFound.textContent = 'Aucun jeu ne correspond à votre recherche';
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
    createHtmlCard(searchResult, city);
  }
}

//Recherche de jeux d'après noms
const searchInput = document.getElementById('search-game');

searchInput.addEventListener('input', searchGame);
searchInput.addEventListener('search', searchGame);

function searchGameNameInput() {
  const activeTab = document.querySelector('.stores.active');
  const activeTabId = activeTab.getAttribute('id');
  const searchValue = searchInput.value.toLowerCase();
  switch (activeTabId) {
    case 'nantes-tab':
      searchGameByName(gameDatas['datas']['datasNantes'], searchValue, 'nantes');
      break;
    case 'lille-tab':
      searchGameByName(gameDatas['datas']['datasLille'], searchValue, 'lille');
      break;
    case 'bordeaux-tab':
      searchGameByName(gameDatas['datas']['datasBordeaux'], searchValue, 'bordeaux');
      break;
    case 'paris-tab':
      searchGameByName(gameDatas['datas']['datasParis'], searchValue, 'paris');
      break;
    case 'toulouse-tab':
      searchGameByName(gameDatas['datas']['datasToulouse'], searchValue, 'toulouse');
      break;
  }
}

// Recherche de jeux d'après genres
const genresChecks = document.querySelectorAll('.genre-filter');
genresChecks.forEach(genre => {
  genre.addEventListener('change', searchGame);
});

function searchGameByGenre() {
  const activeTab = document.querySelector('.stores.active');
  const activeTabId = activeTab.getAttribute('id');
  const genres = [];
  genresChecks.forEach(genre => {
    if (genre.checked) {
      genres.push(genre.value);
    }
  });

  switch (activeTabId) {
    case 'nantes-tab':
      searchGameByGenreName(gameDatas['datas']['datasNantes'], genres, 'nantes');
      break;
    case 'lille-tab':
      searchGameByGenreName(gameDatas['datas']['datasLille'], genres, 'lille');
      break;
    case 'bordeaux-tab':
      searchGameByGenreName(gameDatas['datas']['datasBordeaux'], genres, 'bordeaux');
      break;
    case 'paris-tab':
      searchGameByGenreName(gameDatas['datas']['datasParis'], genres, 'paris');
      break;
    case 'toulouse-tab':
      searchGameByGenreName(gameDatas['datas']['datasToulouse'], genres, 'toulouse');
      break;
  }
}

function searchGameByGenreName(datas, genres, city) {
  let searchResult = [];

  genres.forEach(genre => {
    console.log(genre);
    const result = datas.filter(game => game['genre'].includes(genre));
    searchResult = searchResult.concat(result);
  });
  
  console.log(searchResult);
  if (genres.length === 0) {
    createHtmlCard(datas, city);
  } else if (searchResult.length === 0) {
    const notFound = document.createElement('h4');
    notFound.classList.add('text-center', 'text-uppercase', 'fs-5');
    notFound.textContent = 'Aucun jeu ne correspond à votre recherche';
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
    createHtmlCard(searchResult, city);
  }
}

// Recherche de jeux d'après plateformes
const platformsChecks = document.querySelectorAll('.platform-filter');
platformsChecks.forEach(platform => {
  platform.addEventListener('change', searchGame);
});

function searchGameByPlatform() {
  const activeTab = document.querySelector('.stores.active');
  const activeTabId = activeTab.getAttribute('id');
  const platforms = [];
  platformsChecks.forEach(platform => {
    if (platform.checked) {
      platforms.push(platform.value);
    }
  });

  switch (activeTabId) {
    case 'nantes-tab':
      searchGameByPlatformName(gameDatas['datas']['datasNantes'], platforms, 'nantes');
      break;
    case 'lille-tab':
      searchGameByPlatformName(gameDatas['datas']['datasLille'], platforms, 'lille');
      break;
    case 'bordeaux-tab':
      searchGameByPlatformName(gameDatas['datas']['datasBordeaux'], platforms, 'bordeaux');
      break;
    case 'paris-tab':
      searchGameByPlatformName(gameDatas['datas']['datasParis'], platforms, 'paris');
      break;
    case 'toulouse-tab':
      searchGameByPlatformName(gameDatas['datas']['datasToulouse'], platforms, 'toulouse');
      break;
  } 
}

function searchGameByPlatformName(datas, platforms, city) {
  let searchResult = [];

  platforms.forEach(platform => {
    const result = datas.filter(game => game['platform_name'] === platform);
    searchResult = searchResult.concat(result);
  });

  if (platforms.length === 0) {
    createHtmlCard(datas, city);
  } else if (searchResult.length === 0) {
    const notFound = document.createElement('h4');
    notFound.classList.add('text-center', 'text-uppercase', 'fs-5');
    notFound.textContent = 'Aucun jeu ne correspond à votre recherche';
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
    createHtmlCard(searchResult, city);
  }
}

// Combinaison de recherche de jeux
function searchGame() {
  const activeTab = document.querySelector('.stores.active');
  const activeTabId = activeTab.getAttribute('id');
  const searchValue = searchInput.value.toLowerCase();
  const minPrice = minPriceInput.value;
  const maxPrice = maxPriceInput.value;
  const genres = [];
  genresChecks.forEach(genre => {
    if (genre.checked) {
      genres.push(genre.value);
    }
  });
  const platforms = [];
  platformsChecks.forEach(platform => {
    if (platform.checked) {
      platforms.push(platform.value);
    }
  });

  switch (activeTabId) {
    case 'nantes-tab':
      searchGameByAll(gameDatas['datas']['datasNantes'], searchValue, genres, platforms, minPrice, maxPrice, 'nantes');
      break;
    case 'lille-tab':
      searchGameByAll(gameDatas['datas']['datasLille'], searchValue, genres, platforms, minPrice, maxPrice, 'lille');
      break;
    case 'bordeaux-tab':
      searchGameByAll(gameDatas['datas']['datasBordeaux'], searchValue, genres, platforms, minPrice, maxPrice, 'bordeaux');
      break;
    case 'paris-tab':
      searchGameByAll(gameDatas['datas']['datasParis'], searchValue, genres, platforms, minPrice, maxPrice, 'paris');
      break;
    case 'toulouse-tab':
      searchGameByAll(gameDatas['datas']['datasToulouse'], searchValue, genres, platforms, minPrice, maxPrice, 'toulouse');
      break;
  }
}

function searchGameByAll(datas, searchValue, genres, platforms, minPrice, maxPrice, city) {
  let searchResult = datas.filter(game => game['game_name'].toLowerCase().includes(searchValue));

  if (searchValue === '') {
    searchResult = datas;
  }

  if (genres.length > 0) {
    let result = [];
    genres.forEach(genre => {
      const res = searchResult.filter(game => game['genre'].includes(genre));
      result = result.concat(res);
    });
    searchResult = result;
  }

  if (platforms.length > 0) {
    let result = [];
    platforms.forEach(platform => {
      const res = searchResult.filter(game => game['platform_name'] === platform);
      result = result.concat(res);
    });
    searchResult = result;
  }

  if (minPrice !== '' && maxPrice !== '') {
    searchResult = searchResult.filter(game => game['platform_price'] >= minPrice && game['platform_price'] <= maxPrice);
  }

  if (searchResult.length === 0) {
    const notFound = document.createElement('h4');
    notFound.classList.add('text-center', 'text-uppercase', 'fs-5');
    notFound.textContent = 'Aucun jeu ne correspond à votre recherche';
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
    createHtmlCard(searchResult, city);
  }
}




// Récupération de l'emplacement où afficher les cartes de jeux
const cardsNantesDiv = document.getElementById('cards-nantes');
const cardsLilleDiv = document.getElementById('cards-lille');
const cardsBordeauxDiv = document.getElementById('cards-bordeaux');
const cardsParisDiv = document.getElementById('cards-paris');
const cardsToulouseDiv = document.getElementById('cards-toulouse');

// Récupération de l'image Spotlight dans gameDatas
function getSpotlightImg(datas) {
  let spotlight = datas.find(data => data.includes('spotlight'));
  return spotlight;
}

// Fonction pour afficher les cartes de jeux
function prepareHtmlCard(datas) {
  const activeTab = document.querySelector('.stores.active');
  const activeTabId = activeTab.getAttribute('id');

  switch (activeTabId) {
    case 'nantes-tab':
      createHtmlCard(datas['datasNantes'], 'nantes');
      document.getElementById('loading-nantes').classList.add('d-none');
      break;
    case 'lille-tab':
      createHtmlCard(datas['datasLille'], 'lille');
      document.getElementById('loading-lille').classList.add('visually-hidden');
      break;
    case 'bordeaux-tab':
      createHtmlCard(datas['datasBordeaux'], 'bordeaux');
      document.getElementById('loading-bordeaux').classList.add('visually-hidden');
      break;
    case 'paris-tab':
      createHtmlCard(datas['datasParis'], 'paris');
      document.getElementById('loading-paris').classList.add('visually-hidden');
      break;
    case 'toulouse-tab':
      createHtmlCard(datas['datasToulouse'], 'toulouse');
      document.getElementById('loading-toulouse').classList.add('visually-hidden');
      break;
  }
}

// Fonction pour créer les cartes de jeux
function createHtmlCard(datas, city) {
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