/********************************************/

/* INITIALISATION DE SELECT2 ET DATATABLES  */

/********************************************/

$(document).ready(function() {
  $('.user-select').select2({
    theme: 'bootstrap-5'
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
      "zeroRecords": "Aucun jeu trouvé",
      "info": "",
      "infoEmpty": "",
      "infoFiltered": ""
    },
    "columnDefs": [
      { "orderable": false, "targets": 5 }  // Désactiver le tri sur la dernière colonne (Actions)
    ],
    initComplete: function () {
      document.getElementById('gamesTable').classList.remove('visually-hidden');
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
});
