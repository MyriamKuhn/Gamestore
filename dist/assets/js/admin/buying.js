/********************************************/

/* INITIALISATION DE SELECT2 ET DATATABLES  */

/********************************************/

$(document).ready(function() {
  $('.user-select').select2({
    theme: 'bootstrap-5',
    templateResult: function(option) {
      if (!option.id) return option.text; // Option par défaut
      // Appliquer du style uniquement à l'option avec data-color="red"
      var color = $(option.element).data('color');
      if (color) {
          return $('<span style="color:' + color + ';">' + option.text + '</span>');
      }

      return option.text;
    }
  });
  const table = $('#gamesTable').DataTable({
    "responsive": true,
    "paging": true,
    "pagingType": "simple_numbers",
    "pageLength": 10,
    "lengthMenu": [5, 10, 25, 50],
    "ordering": true,
    "order": [[0, 'asc']],
    "searching": true,
    "language": {
      "paginate": {
        "next":       ">>",
        "previous":   "<<"
      },
      "lengthMenu": "Afficher _MENU_ entrées par page",
      "zeroRecords": "Aucune commande trouvée",
      "info": "",
      "infoEmpty": "",
      "infoFiltered": ""
    },
    "columnDefs": [
      { "orderable": false, "targets": 6 }  // Désactiver le tri sur la dernière colonne (Actions)
    ],
    initComplete: function () {
      document.getElementById('gamesTable').classList.remove('visually-hidden');
      document.getElementById('loadingGames').classList.add('visually-hidden');
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

  // Filtrer par magasin
  $('#storeFilter').on('change', function() {
    table.column(3).search(this.value).draw();  
  });
});