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
      .then(response => response.text())
      .then(text => {
        const jsonObject = JSON.parse(text);
        //gameDatas = datas;
        console.log(jsonObject);
      })
      .catch(error => console.error('Erreur : ' + error));
      
    } catch (error) {
    console.error('Erreur : ' + error);
  }
}

  
  
  