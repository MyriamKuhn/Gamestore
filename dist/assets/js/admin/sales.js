/***********/

// IMPORTS //

/**********/
import { validateJSONStructure } from '../utils.js';


/****************/

// AU DEMARRAGE //

/****************/
document.addEventListener('DOMContentLoaded', function () {
  fetchDatasFirst('getSalesNantesDatas', 'no-datas-nantes', 'chartNantes', 'loading-nantes', 'getSalesGenreNantesDatas', 'chartGenreNantes', 'graphic-title-nantes');

  const tabs = document.querySelectorAll('a[data-bs-toggle="tab"]');
  tabs.forEach(tab => {
    tab.addEventListener('shown.bs.tab', event => {
      const target = event.target.getAttribute('href').slice(1);
      switch (target) {
        case 'nantes':
          document.getElementById('loading-nantes').classList.remove('visually-hidden');
          document.getElementById('no-datas-nantes').classList.add('visually-hidden');
          document.getElementById('chartNantes').innerHTML = '';
          document.getElementById('chartGenreNantes').innerHTML = '';
          fetchDatasFirst('getSalesNantesDatas', 'no-datas-nantes', 'chartNantes', 'loading-nantes', 'getSalesGenreNantesDatas', 'chartGenreNantes', 'graphic-title-nantes');
          break;
        case 'lille':
          document.getElementById('loading-lille').classList.remove('visually-hidden');
          document.getElementById('no-datas-lille').classList.add('visually-hidden');
          document.getElementById('chartLille').innerHTML = '';
          document.getElementById('chartGenreLille').innerHTML = '';
          fetchDatasFirst('getSalesLilleDatas', 'no-datas-lille', 'chartLille', 'loading-lille', 'getSalesGenreLilleDatas', 'chartGenreLille', 'graphic-title-lille');
          break;
        case 'bordeaux':
          document.getElementById('loading-bordeaux').classList.remove('visually-hidden');
          document.getElementById('no-datas-bordeaux').classList.add('visually-hidden');
          document.getElementById('chartBordeaux').innerHTML = '';
          document.getElementById('chartGenreBordeaux').innerHTML = '';
          fetchDatasFirst('getSalesBordeauxDatas', 'no-datas-bordeaux', 'chartBordeaux', 'loading-bordeaux', 'getSalesGenreBordeauxDatas', 'chartGenreBordeaux', 'graphic-title-bordeaux');
          break;
        case 'paris':
          document.getElementById('loading-paris').classList.remove('visually-hidden');
          document.getElementById('no-datas-paris').classList.add('visually-hidden');
          document.getElementById('chartParis').innerHTML = '';
          document.getElementById('chartGenreParis').innerHTML = '';
          fetchDatasFirst('getSalesParisDatas', 'no-datas-paris', 'chartParis', 'loading-paris', 'getSalesGenreParisDatas', 'chartGenreParis', 'graphic-title-paris');
          break;
        case 'toulouse':
          document.getElementById('loading-toulouse').classList.remove('visually-hidden');
          document.getElementById('no-datas-toulouse').classList.add('visually-hidden');
          document.getElementById('chartToulouse').innerHTML = '';
          document.getElementById('chartGenreToulouse').innerHTML = '';
          fetchDatasFirst('getSalesToulouseDatas', 'no-datas-toulouse', 'chartToulouse', 'loading-toulouse', 'getSalesGenreToulouseDatas', 'chartGenreToulouse', 'graphic-title-toulouse');
          break;
        case 'all':
          document.getElementById('loading-all').classList.remove('visually-hidden');
          document.getElementById('no-datas-all').classList.add('visually-hidden');
          document.getElementById('chartAll').innerHTML = '';
          document.getElementById('chartGenreAll').innerHTML = '';
          fetchDatasFirst('getSalesAllDatas', 'no-datas-all', 'chartAll', 'loading-all', 'getSalesGenreAllDatas', 'chartGenreAll', 'graphic-title-all');
          break;
      }
    });
  });

});


/******************************************/

// FETCH DES DONNEES POUR LES GRAPHIQUES //

/*****************************************/
function fetchDatasFirst(action, idNoDatas, idChart, idLoading, actionSecond, idChartSecond, classTitle) {
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  fetch('index.php?controller=datas',
    {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': csrfToken
      },
      body: JSON.stringify({ action: action })
    })
    .then(response => response.json())
    .then(data => {
      if (!validateJSONStructure(data)) {
        console.error('Format inattendu des données');
        return;
      }

      if (data.success === false) {
        if (data.datas === "Aucune vente n'a été trouvée") {
          document.getElementById(idLoading).classList.add('visually-hidden');
          const noDatas = document.getElementById(idNoDatas);
          noDatas.classList.remove('visually-hidden');
          return;
        }
        console.error("Erreur : " + data.error);
        return;
      }

      // Lancement du fetch pour le graphique par genre 
      fetchDatasSecond(actionSecond, idChartSecond, idLoading, classTitle);

      // Préparation des données pour le graphique
      const seriesData = {};
      const allDates = new Set();
      data.datas.forEach((sale) => {
        const { name, date, totalQuantity } = sale;

        allDates.add(date);

        if (!seriesData[name]) {
          seriesData[name] = {};
        }

        if (!seriesData[name][date]) {
          seriesData[name][date] = 0;
        }

        seriesData[name][date] += totalQuantity;
      });

      const series = Object.keys(seriesData).map((gameName) => {
        return {
          name: gameName,
          data: Array.from(allDates).map((date) => {
            // Convertir la date UTC en date locale
            const localDate = new Date(date).toLocaleDateString("fr-FR", {
              timeZone: "Europe/Paris",
            });
            return {
              x: localDate, 
              y: seriesData[gameName][date] || 0,
            };
          }),
        };
      });

      displayGraphFirst(series, idChart);
    })
    .catch(error => console.error('Erreur : ' + error));
}

function fetchDatasSecond(action, idChart, idLoading, classTitle) {
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  fetch('index.php?controller=datas',
    {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': csrfToken
      },
      body: JSON.stringify({ action: action })
    })
    .then(response => response.json())
    .then(data => {
      if (validateJSONStructure(data)) {
        if (data.success === false) {
          console.error("Erreur : " + data.error);
          return;
        }

        // Fonction pour convertir la date au format dd-mm-YYYY
        function formatDate(dateStr) {
          const date = new Date(dateStr);
          const day = String(date.getDate()).padStart(2, '0');
          const month = String(date.getMonth() + 1).padStart(2, '0'); 
          const year = String(date.getFullYear()); 
          return `${day}-${month}-${year}`;
        }

        // Préparer les séries par genre
        const genres = [...new Set(data.datas.map(item => item.genre))]; 
        const dates = [...new Set(data.datas.map(item => formatDate(item.date)))]; 

        // Préparation des données pour le graphique
        const series = genres.map(genre => {
          return {
            name: genre,
            data: dates.map(date => {
              const sale = data.datas.find(item => item.genre === genre && formatDate(item.date) === date);
              return sale ? sale.totalQuantity : 0;
            })
          };
        });

        displayGraphSecond(series, dates, idChart, idLoading, classTitle);
        
      } else {
      console.error('Format inattendu des données');
      }
    })
    .catch(error => console.error('Erreur : ' + error));
}


/*****************************/

// AFFICHAGE DES GRAPHIQUES //

/****************************/
function displayGraphFirst(series, idChart) {
  // Initialisation du graphique
  const options = {
    series: series,
    chart: {
      height: 500,
      type: "line",
      zoom: {
        enabled: true,
      },
      dropShadow: {
        enabled: true,
        top: 3,
        left: 2,
        blur: 4,
        opacity: 0.4,
      },
    },
    stroke: {
      curve: 'smooth',
      width: 2
    },
    xaxis: {
      type: "category", 
      title: {
        text: "Dates",
      },
      labels: {
        format: 'dd/MM/yy'
      },
    },
    yaxis: {
      title: {
        text: "Quantité",
      },
    },
    legend: {
      position: "bottom",
    },
  };

  const chart = new ApexCharts(document.querySelector("#" + idChart + ""), options);
  chart.render();
}

function displayGraphSecond(series, dates, idChart, idLoading, classTitle) {
  // Configuration du graphique ApexCharts
  const options = {
    chart: {
      type: 'bar',
      stacked: true,
      height: 500,
      zoom: {
        enabled: true,
      },
      dropShadow: {
        enabled: true,
        top: 3,
        left: 2,
        blur: 4,
        opacity: 0.4,
      },
    },
    series: series,
    xaxis: {
      categories: dates,
    },
    yaxis: {
      title: {
        text: 'Quantité totale'
      }
    },
    legend: {
      position: 'bottom'
    }
  };

  // Initialisation du graphique
  const chart = new ApexCharts(document.querySelector("#" + idChart + ""), options);
  chart.render();

  document.getElementById(idLoading).classList.add('visually-hidden');
  const titles = document.querySelectorAll('.' + classTitle + '');
  titles.forEach(title => title.classList.remove('visually-hidden'));
}
