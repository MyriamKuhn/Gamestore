/***********/

// IMPORTS //

/**********/
import { secureInput } from './utils.js';


//**************/

// URL PARAMS //

/**************/
const url = new URL(window.location.href);
const urlParams = new URLSearchParams(url.search);
const gameId = secureInput(urlParams.get('id'));


/********************/

// START PAGE LIST  //

/********************/
getDatas();


/**********************/

// FETCH DES DONNEES //

/*********************/
export let gameDatas = null;

function getDatas() {
  try {
    const requestBody = JSON.stringify({
      action: 'getGameDatas',
      gameId: gameId
    });

    fetch('index.php?controller=datas',
      {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: requestBody
      })
      .then(response => response.json())
      .then(datas => {
        gameDatas = datas;
        console.log(gameDatas);
        createSelectPlatforms();
      })
      .catch(error => console.error('Erreur : ' + error));
      
    } catch (error) {
    console.error('Erreur : ' + error);
  }
}

/*********************************************/

// CONSTRUCTION DES SELECT POUR PLATEFORMES //

/********************************************/
function createSelectPlatforms() {
  const uniquePlatforms = [...new Set(gameDatas['datas']['game_prices'].map(data => data['platform']))];
  const platformsContainer = document.getElementById('select-platforms');
  platformsContainer.innerHTML = '';
  const selectPlatforms = document.createElement('select');
  selectPlatforms.name = 'platforms';
  selectPlatforms.id = 'platforms';
  selectPlatforms.className = 'form-select my-2';
  platformsContainer.appendChild(selectPlatforms);

  uniquePlatforms.forEach(platform => {
    const option = document.createElement('option');
    option.value = platform;
    option.textContent = platform;
    selectPlatforms.appendChild(option);
  });
  changeDatasByPlatform();
}


/*************************************/

// EVENT LISTENER SUR LE SELECTEUR  //

/*************************************/
document.getElementById('select-platforms').addEventListener('change', changeDatasByPlatform);


/***************************************************/

// AFFICHAGE DU PRIX EN FONCTION DE LA PLATEFORME //

/**************************************************/
  function changeDatasByPlatform () {
  // Récupération de tous les container
  const iconContainerN = document.getElementById('icon-nantes');
  const iconContainerL = document.getElementById('icon-lille');
  const iconContainerB = document.getElementById('icon-bordeaux');
  const iconContainerP = document.getElementById('icon-paris');
  const iconContainerT = document.getElementById('icon-toulouse');
  const priceContainerN = document.getElementById('price-nantes');
  const priceContainerL = document.getElementById('price-lille');
  const priceContainerB = document.getElementById('price-bordeaux');
  const priceContainerP = document.getElementById('price-paris');
  const priceContainerT = document.getElementById('price-toulouse');
  const discountContainerN = document.getElementById('discount-nantes');
  const discountContainerL = document.getElementById('discount-lille');
  const discountContainerB = document.getElementById('discount-bordeaux');
  const discountContainerP = document.getElementById('discount-paris');
  const discountContainerT = document.getElementById('discount-toulouse');
  const oldpriceContainerN = document.getElementById('oldprice-nantes');
  const oldpriceContainerL = document.getElementById('oldprice-lille');
  const oldpriceContainerB = document.getElementById('oldprice-bordeaux');
  const oldpriceContainerP = document.getElementById('oldprice-paris');
  const oldpriceContainerT = document.getElementById('oldprice-toulouse');
  const stockContainerN = document.getElementById('stock-nantes');
  const stockContainerL = document.getElementById('stock-lille');
  const stockContainerB = document.getElementById('stock-bordeaux');
  const stockContainerP = document.getElementById('stock-paris');
  const stockContainerT = document.getElementById('stock-toulouse');
  const pricesContainerN = document.getElementById('prices-container-nantes');
  const pricesContainerL = document.getElementById('prices-container-lille');
  const pricesContainerB = document.getElementById('prices-container-bordeaux');
  const pricesContainerP = document.getElementById('prices-container-paris');
  const pricesContainerT = document.getElementById('prices-container-toulouse');

  // Récupération de la plateforme sélectionnée
  const selectedPlatform = document.getElementById('platforms').value;

  // Récupération du tableau des données pour chaque ville
  const priceN = gameDatas['datas']['game_prices'].find(data => data['platform'] === selectedPlatform && data['location'] === 'Nantes');
  const priceL = gameDatas['datas']['game_prices'].find(data => data['platform'] === selectedPlatform && data['location'] === 'Lille');
  const priceB = gameDatas['datas']['game_prices'].find(data => data['platform'] === selectedPlatform && data['location'] === 'Bordeaux');
  const priceP = gameDatas['datas']['game_prices'].find(data => data['platform'] === selectedPlatform && data['location'] === 'Paris');
  const priceT = gameDatas['datas']['game_prices'].find(data => data['platform'] === selectedPlatform && data['location'] === 'Toulouse');

  //Nantes
  if (parseInt(priceN['stock']) === 0) {
    iconContainerN.classList = 'bi bi-x-lg cross';
    priceContainerN.textContent = ' ';
    pricesContainerN.style.display = 'none';
    stockContainerN.textContent = 'Rupture de stock';
  } else if (parseInt(priceN['is_reduced']) === 1) {
    iconContainerN.classList = 'bi bi-percent percent';
    priceContainerN.textContent = (priceN['price'] * (1 - priceN['discount_rate'])).toFixed(2) + ' €';
    pricesContainerN.style.display = 'block';
    discountContainerN.textContent = priceN['discount_rate'] * 100 + '%';
    oldpriceContainerN.textContent = priceN['price'] + ' €';
    stockContainerN.textContent = priceN['stock'] + ' en stock';
  } else if (parseInt(priceN['stock']) <= 5) {
    iconContainerN.classList = 'bi bi-exclamation-lg warn';
    priceContainerN.textContent = priceN['price'] + ' €';
    pricesContainerN.style.display = 'none';
    stockContainerN.textContent = 'reste ' + priceN['stock'];
  } else {
    iconContainerN.classList = 'bi bi-check2 check';
    priceContainerN.textContent = priceN['price'] + ' €';
    pricesContainerN.style.display = 'none';
    stockContainerN.textContent = priceN['stock'] + ' en stock';
  }

    //Lille
    if (parseInt(priceL['stock']) === 0) {
      iconContainerL.classList = 'bi bi-x-lg cross';
      priceContainerL.textContent = ' ';
      pricesContainerL.style.display = 'none';
      stockContainerL.textContent = 'Rupture de stock';
    } else if (parseInt(priceL['is_reduced']) === 1) {
      iconContainerL.classList = 'bi bi-percent percent';
      priceContainerL.textContent = (priceL['price'] * (1 - priceL['discount_rate'])).toFixed(2) + ' €';
      pricesContainerL.style.display = 'block';
      discountContainerL.textContent = priceL['discount_rate'] * 100 + '%';
      oldpriceContainerL.textContent = priceL['price'] + ' €';
      stockContainerL.textContent = priceL['stock'] + ' en stock';
    } else if (parseInt(priceL['stock']) <= 5) {
      iconContainerL.classList = 'bi bi-exclamation-lg warn';
      priceContainerL.textContent = priceL['price'] + ' €';
      pricesContainerL.style.display = 'none';
      stockContainerL.textContent = 'reste ' + priceL['stock'];
    } else {
      iconContainerL.classList = 'bi bi-check2 check';
      priceContainerL.textContent = priceL['price'] + ' €';
      pricesContainerL.style.display = 'none';
      stockContainerL.textContent = priceL['stock'] + ' en stock';
    }

    //Bordeaux
    if (parseInt(priceB['stock']) === 0) {
      iconContainerB.classList = 'bi bi-x-lg cross';
      priceContainerB.textContent = ' ';
      pricesContainerB.style.display = 'none';
      stockContainerB.textContent = 'Rupture de stock';
    } else if (parseInt(priceB['is_reduced']) === 1) {
      iconContainerB.classList = 'bi bi-percent percent';
      priceContainerB.textContent = (priceB['price'] * (1 - priceB['discount_rate'])).toFixed(2) + ' €';
      pricesContainerB.style.display = 'block';
      discountContainerB.textContent = priceB['discount_rate'] * 100 + '%';
      oldpriceContainerB.textContent = priceB['price'] + ' €';
      stockContainerB.textContent = priceB['stock'] + ' en stock';
    } else if (parseInt(priceB['stock']) <= 5) {
      iconContainerB.classList = 'bi bi-exclamation-lg warn';
      priceContainerB.textContent = priceB['price'] + ' €';
      pricesContainerB.style.display = 'none';
      stockContainerB.textContent = 'reste ' + priceB['stock'];
    } else {
      iconContainerB.classList = 'bi bi-check2 check';
      priceContainerB.textContent = priceB['price'] + ' €';
      pricesContainerB.style.display = 'none';
      stockContainerB.textContent = priceB['stock'] + ' en stock';
    }

    //Paris
    if (parseInt(priceP['stock']) === 0) {
      iconContainerP.classList = 'bi bi-x-lg cross';
      priceContainerP.textContent = ' ';
      pricesContainerP.style.display = 'none';
      stockContainerP.textContent = 'Rupture de stock';
    } else if (parseInt(priceP['is_reduced']) === 1) {
      iconContainerP.classList = 'bi bi-percent percent';
      priceContainerP.textContent = (priceP['price'] * (1 - priceP['discount_rate'])).toFixed(2) + ' €';
      pricesContainerP.style.display = 'block';
      discountContainerP.textContent = priceP['discount_rate'] * 100 + '%';
      oldpriceContainerP.textContent = priceP['price'] + ' €';
      stockContainerP.textContent = priceP['stock'] + ' en stock';
    } else if (parseInt(priceP['stock']) <= 5) {
      iconContainerP.classList = 'bi bi-exclamation-lg warn';
      priceContainerP.textContent = priceP['price'] + ' €';
      pricesContainerP.style.display = 'none';
      stockContainerP.textContent = 'reste ' + priceP['stock'];
    } else {
      iconContainerP.classList = 'bi bi-check2 check';
      priceContainerP.textContent = priceP['price'] + ' €';
      pricesContainerP.style.display = 'none';
      stockContainerP.textContent = priceP['stock'] + ' en stock';
    }

    //Toulouse
    if (parseInt(priceT['stock']) === 0) {
      iconContainerT.classList = 'bi bi-x-lg cross';
      priceContainerT.textContent = ' ';
      pricesContainerT.style.display = 'none';
      stockContainerT.textContent = 'Rupture de stock';
    } else if (parseInt(priceT['is_reduced']) === 1) {
      iconContainerT.classList = 'bi bi-percent percent';
      priceContainerT.textContent = (priceT['price'] * (1 - priceT['discount_rate'])).toFixed(2) + ' €';
      pricesContainerT.style.display = 'block';
      discountContainerT.textContent = priceT['discount_rate'] * 100 + '%';
      oldpriceContainerT.textContent = priceT['price'] + ' €';
      stockContainerT.textContent = priceT['stock'] + ' en stock';
    } else if (parseInt(priceT['stock']) <= 5) {
      iconContainerT.classList = 'bi bi-exclamation-lg warn';
      priceContainerT.textContent = priceT['price'] + ' €';
      pricesContainerT.style.display = 'none';
      stockContainerT.textContent = 'reste ' + priceT['stock'];
    } else {
      iconContainerT.classList = 'bi bi-check2 check';
      priceContainerT.textContent = priceT['price'] + ' €';
      pricesContainerT.style.display = 'none';
      stockContainerT.textContent = priceT['stock'] + ' en stock';
    }
}

