/*************************************************/

// SECURISER LES INPUTS CONTRE LES INJECTIONS XSS //

/*************************************************/
export function secureInput(text) {
  const map = {
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;',
    '&': '&amp;',
  };
  return text.replace(/[<>"'&]/g, (m) => map[m]);
}


/*******************************************/

// CHERCHER IMAGE> EN FONCTION DE SON NOM //

/*****************************************/
export function getImgByName(datas) {
  let spotlight = datas.find(data => data.includes('spotlight'));
  return spotlight;
}


/************************/

// VALIDATION DES JSON //

/***********************/
export const validateJSONStructure = (data) => {
  if (Array.isArray(data)) {
    return data.every(item => validateJSONStructure(item));
  } else if (typeof data === 'object' && data !== null) {
    return Object.values(data).every(value => validateJSONStructure(value));
  } else {
    return ['string', 'number', 'boolean'].includes(typeof data);
  }
};


/*****************************************************/

// MONTRER LE PANIER QUE SI IL CONTIENT DES ELEMENTS //

/*****************************************************/
export function showCart() {
  const cart = document.getElementById('navbar-cart');
  const cartCount = document.getElementById('cart-count');
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  const requestBody = JSON.stringify({
    action: 'getCartContent'
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
    .then(data => {
      if (validateJSONStructure(data)) {
        if (data.success) {
          const cartDatas = data.datas || [];
          if (cartDatas.length > 0) {
            cart.classList.remove('visually-hidden');
            cartCount.textContent = cartDatas.length;
          } else {
            cart.classList.add('visually-hidden');
            cartCount.textContent = '0';
          }
        } else {
          console.error('Erreur : ' + data.message);
          cart.classList.add('visually-hidden');
          cartCount.textContent = '0';
        }
      } else {
        console.error('Format inattendu des données');
        cart.classList.add('visually-hidden');
        cartCount.textContent = '0';
      }
    })
    .catch(error => {
      console.error('Erreur : ' + error);
      cart.classList.add('visually-hidden');
      cartCount.textContent = '0';
  });
}