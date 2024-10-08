/*********************************/

/* INITIALISATION DE DATATABLES */

/********************************/
$(document).ready(function() {
  // Initialiser DataTables
  const table = $('#salesTable').DataTable({
    dom: '<"row"<"col-md-6"l><"col-md-6 text-end"B>>rt<"row"<"col-md-6"i><"col-md-6 d-flex justify-content-end"p>>',  // i = info, B = buttons, f = filter, l = length changing input, p = pagination, t = table
      buttons: [
        {
          extend: 'copy',
          text: 'Copier',
          className: 'btn btn-gamestore-outline text-uppercase'
        },
        {
          extend: 'csv',
          text: 'CSV',
          className: 'btn btn-gamestore-outline text-uppercase'
        },
        {
          extend: 'excel',
          text: 'Excel',
          className: 'btn btn-gamestore-outline text-uppercase'
        },      
      ],
    "responsive": true,
    "paging": true,
    "pagingType": "simple_numbers",
    "pageLength": 10,
    "lengthMenu": [5, 10, 25, 50],
    "ordering": true,
    "order": [[0, 'desc']],
    "searching": true,
    "language": {
      "paginate": {
        "next":       ">>",
        "previous":   "<<"
      },
      "lengthMenu": "Afficher _MENU_ entrées par page",
      "zeroRecords": "Aucune vente trouvée",
      "info": "",
      "infoEmpty": "",
      "infoFiltered": ""
    },
    "footerCallback": function(row, data, start, end, display) {
      const api = this.api();

      // Nettoyer les colonnes de quantité et de prix pour qu'elles ne contiennent que des chiffres
      const intVal = function(i) {
        return typeof i === 'string' ?
        i.replace(/[\€\$,]/g, '') * 1 :
          typeof i === 'number' ?
          i : 0;
      };

      // Total quantités sur tous les filtres
      const totalQuantity = api
        .column(3, { page: 'current' }) // Colonne de la quantité
        .data()
        .reduce(function(a, b) {
          return intVal(a) + intVal(b);
        }, 0);

      // Total prix sur tous les filtres
      const totalPrice = api
        .column(4, { page: 'current' }) // Colonne du prix
        .data()
        .reduce(function(a, b) {
          return intVal(a) + intVal(b);
        }, 0);

      // Mettre à jour le pied de page avec les totaux calculés
      $(api.column(3).footer()).html(totalQuantity);
      $(api.column(4).footer()).html(totalPrice.toFixed(2) + ' €');
    },
    initComplete: function () {
      document.getElementById('salesTable').classList.remove('visually-hidden');
      document.getElementById('loading').classList.add('visually-hidden');
    }
  });

  // Filtrer par nom du jeu
  $('#gameFilter').on('keyup', function() {
    table.column(1).search(this.value).draw();  
  });

    // Filtrer par plateforme
  $('#platformFilter').on('change', function() {
    table.column(2).search(this.value).draw();  
  });

  // Filtrer par date unique
  $('#dateFilter').on('change', function() {
    const selectedDate = $(this).val(); // Récupérer la date choisie par l'utilisateur (format YYYY-MM-DD)
    table.draw(); 
  });

  // Ajouter un filtre personnalisé pour la colonne de date
  $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
    const selectedDate = $('#dateFilter').val();
    const orderDate = data[0]; 
    // Convertir la date de la table (format d/m/Y) et la date sélectionnée (YYYY-MM-DD)
    if (selectedDate) {
      const parts = orderDate.split('/');
      const tableDate = new Date(parts[2], parts[1] - 1, parts[0]); // Convertir la date en objet Date (d/m/Y)
      const filterDate = new Date(selectedDate); // Convertir la date sélectionnée (YYYY-MM-DD) en objet Date
      // Comparer les deux dates
      if (tableDate.toDateString() === filterDate.toDateString()) {
        // Si les dates correspondent, afficher la ligne
        return true; 
      }
      // Si les dates ne correspondent pas, masquer la ligne
      return false; 
    }
    // Si aucune date sélectionnée, toutes les lignes sont affichées
    return true; 
  });
});