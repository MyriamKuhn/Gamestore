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