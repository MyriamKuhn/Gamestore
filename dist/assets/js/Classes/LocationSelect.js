import { secureInput, showCart } from '../utils.js';

export class LocationSelect {

  constructor(gameDatas, PlatformSelectValue) {
    this.gameDatas = gameDatas;
    this.PlatformSelectValue = PlatformSelectValue;

    // Récupération des localisations des jeux en fonction de la plateforme sélectionnée
    const uniqueLocations = [...new Set(this.gameDatas['datas']['game_prices'].filter(data => data['platform'] === this.PlatformSelectValue).map(data => data['location']))];

    // Création du menu déroulant des localisations des jeux
    const locationMenu = document.getElementById('menu-location');
    locationMenu.innerHTML = '';
    uniqueLocations.forEach(location => {
      const menuList = document.createElement('li');
      locationMenu.appendChild(menuList);
      const menuLink = document.createElement('a');
      menuLink.classList.add('dropdown-item');
      menuLink.setAttribute('data-value', location);
      menuList.appendChild(menuLink);
      // Récupération des données de prix en fonction de la localisation et de la plateforme sélectionnées
      const prices = this.gameDatas['datas']['game_prices'].find(data => data['location'] === location && data['platform'] === this.PlatformSelectValue);
      const icon = document.createElement('i');
      // Affichage de l'icône en fonction du stock et de la réduction
      if (parseInt(prices['stock']) === 0) {
        icon.classList.add('bi', 'bi-x-lg', 'cross');
      } else if (parseInt(prices['is_reduced']) === 1) {
        icon.classList.add('bi', 'bi-percent', 'percent');
      } else if (parseInt(prices['stock']) <= 5) {
        icon.classList.add('bi', 'bi-exclamation-lg', 'warn');
      } else {
        icon.classList.add('bi', 'bi-check2', 'check');
      }
      menuLink.appendChild(icon);
      const textSpan = document.createElement('span');
      textSpan.textContent = secureInput(location);
      menuLink.appendChild(textSpan);
      menuLink.addEventListener('click', e => {
        e.preventDefault();
        const link = e.currentTarget;
        const iconClass = link.querySelector('i').className;
        this.updateButton(link.textContent.trim(), link.getAttribute('data-value'), iconClass);
        this.updateDatasinHTML();
      });
    });

    // Récupération du premier élément de la liste des localisations des jeux et affichage dans le bouton
    this.locationButton = document.getElementById('locations');
    const firstItem = document.querySelector('#menu-location .dropdown-item');
    const firstIcon = firstItem.querySelector('i').className;
    const firstText = firstItem.textContent.trim();
    const firstValue = firstItem.getAttribute('data-value');
    this.updateButton(firstText, firstValue, firstIcon);

    // Affichage des données de prix sur le HTML en fonction de la localisation et de la plateforme sélectionnées
    this.updateDatasinHTML();

    // Retrait du chargement
    document.getElementById('loading').classList.add('visually-hidden');
    document.getElementById('details').classList.remove('visually-hidden');
  }

  updateButton(text, value, iconClass) {
    this.locationButton.innerHTML = '';

    const icon = document.createElement('i');
    icon.className = iconClass;
    this.locationButton.appendChild(icon);

    const textSpan = document.createElement('span');
    textSpan.classList.add('ms-2');
    textSpan.textContent = secureInput(text);
    this.locationButton.appendChild(textSpan);

    this.locationButton.setAttribute('data-selected-value', value);
  }

  updateDatasinHTML() {
    const priceDatas = this.gameDatas['datas']['game_prices'].find(data => data['location'] === this.getLocationSelectValue() && data['platform'] === this.PlatformSelectValue);
    const price = document.getElementById('price');
    price.innerHTML = '';
    const discount = document.getElementById('discount');
    discount.innerHTML = '';
    const oldprice = document.getElementById('oldprice');
    oldprice.innerHTML = '';
    const badgesDiv = document.getElementById('badges-show');
    badgesDiv.innerHTML = '';

    if (parseInt(priceDatas['is_new']) === 1) {
      const badgeNew = document.createElement('span');
      badgeNew.classList.add('badge', 'rounded-pill', 'text-uppercase', 'py-1', 'px-2', 'me-1');
      badgeNew.textContent = "Nouveauté";
      badgesDiv.appendChild(badgeNew);
    }

    let priceToPay = 0;

    if (parseInt(priceDatas['is_reduced']) === 1) {
      priceToPay = secureInput((priceDatas['price'] * (1 - priceDatas['discount_rate'])).toFixed(2));
      price.textContent = priceToPay + ' €';
      discount.textContent = secureInput(priceDatas['discount_rate'] * 100 + '%');
      oldprice.textContent = secureInput(priceDatas['price']);
      const badgeReduc = document.createElement('span');
      badgeReduc.classList.add('badge', 'rounded-pill', 'text-uppercase', 'py-1', 'px-2');
      badgeReduc.textContent = "Promo";
      badgesDiv.appendChild(badgeReduc);
    } else {
      priceToPay = secureInput(priceDatas['price']);
      price.textContent = priceToPay + ' €';
    }

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

    switch (true) {
      case this.getLocationSelectValue() === 'Nantes':
        if (isUser == true && storeId == 1) {
          isLogged = true;
        } 
        break;
      case this.getLocationSelectValue() === 'Lille':
        if (isUser == true && storeId == 2) {
          isLogged = true;
        } 
        break;
      case this.getLocationSelectValue() === 'Bordeaux':
        if (isUser == true && storeId == 3) {
          isLogged = true;
        } 
        break;
      case this.getLocationSelectValue() === 'Paris':
        if (isUser == true && storeId == 4) {
          isLogged = true;
        } 
        break;
      case this.getLocationSelectValue() === 'Toulouse':
        if (isUser == true && storeId == 5) {
          isLogged = true;
        }
        break;
      default:
        isLogged = false;
        break;
    }

    const buyButton = document.getElementById('buy-button');
    const stock = document.getElementById('stock');
    stock.innerHTML = '';
    if (parseInt(priceDatas['stock']) === 0) {
      stock.textContent = secureInput('Rupture de stock');
      buyButton.disabled = true;
    } else if (parseInt(priceDatas['stock']) <= 5) {
      stock.textContent = secureInput('Plus que ' + priceDatas['stock'] + (parseInt(priceDatas['stock']) === 1 ? ' exemplaire disponible' : ' exemplaires disponibles'));
      if (isLogged == true) {
        buyButton.disabled = false;
      } else {
        buyButton.disabled = true;
      }
    } else {
      stock.textContent = secureInput(priceDatas['stock'] + ' exemplaires disponibles');
      if (isLogged == true) {
        buyButton.disabled = false;
      } else {
        buyButton.disabled = true;
      }
    }

    if (isLogged == true) {
      buyButton.addEventListener('click', () => {
        try {
          const gameId = this.gameDatas['datas']['game_id'];
          const platform = this.PlatformSelectValue;
          const price = priceToPay;
          let discountRate = 0;
          let oldPrice = 0;
          if (secureInput(priceDatas['price']) !== priceToPay) {
            discountRate = secureInput(priceDatas['discount_rate']);
            oldPrice = secureInput(priceDatas['price']);
          }
          const location = storeId;
          const user = userId;
          const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

          const requestBody = JSON.stringify({
            action: 'addCart',
            gameId: gameId,
            platform: platform,
            price: price,
            discountRate: discountRate,
            oldPrice: oldPrice,
            location: location,
            userId: user
          });
      
          fetch('/index.php?controller=datas',
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
                window.location.href = '/index.php?controller=auth&action=logout';
              } else {
                alert ('Le jeu n\'a pas pu être ajouté au panier');
              }
            })
            .catch(error => console.error('Erreur : ' + error));
        } catch (error) {
          console.error('Erreur : ' + error);
        }
      });
    }
  }

  getLocationSelectValue() {
    return this.locationButton.getAttribute('data-selected-value');
  }

}