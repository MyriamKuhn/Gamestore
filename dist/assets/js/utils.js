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


/*****************************/

// CHERCHER IMAGE SPOTLIGHT //

/*****************************/
export function getSpotlightImg(datas) {
  let spotlight = datas.find(data => data.includes('spotlight'));
  return spotlight;
}


/*************************************************/

//        SCROLL TO TOP ET NAVBAR OPACITY        //

/*************************************************/
const scrollTopButton = document.querySelector('#scrollTopButton')
const navbar = document.querySelector('#navbar-opacity')

window.onscroll = () => {
    if(window.scrollY>50){
        scrollTopButton.classList.add("show");
        navbar.classList.remove('bg-opacity-75');
    }
    else{
        scrollTopButton.classList.remove("show");
        navbar.classList.add('bg-opacity-75')
    };
};