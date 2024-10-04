/***********/

// IMPORTS //

/**********/
import { validateJSONStructure } from './utils.js';


/*****************************************************************/

// FETCH DES DONNEES ET PREPARATIONS POUR LE GRAPHIQUE CHART.JS //

/****************************************************************/
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

document.addEventListener('DOMContentLoaded', function () {
  fetch('index.php?controller=datas',
    {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': csrfToken
      },
      body: JSON.stringify({ action: 'getSaleDatas' })
    })
    .then(response => response.json())
    .then(data => {
      if (validateJSONStructure(data)) {
        if (data.success === false) {
          console.error("Erreur : " + data.error);
          return;
        }

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

        const chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
      } else {
      console.error('Format inattendu des données');
      }
    })
    .catch(error => console.error('Erreur : ' + error));
});