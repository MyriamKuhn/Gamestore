/**************************************/

/* ACTIVATION DU TOOLTIP DE BOOTSTRAP */

/**************************************/
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));


/*********************************/

/* INITIALISATION DE DATATABLES */

/********************************/
$(document).ready(function() {
  // Initialiser DataTables
  const table = $('#gameTable').DataTable({
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
      { "orderable": false, "targets": 7 }  // Désactiver le tri sur la dernière colonne (Actions)
    ],
    initComplete: function () {
      document.getElementById('gameTable').classList.remove('visually-hidden');
      document.getElementById('loading').classList.add('visually-hidden');
    }
  });

  // Filtrer par nom
  $('#nameFilter').on('keyup', function() {
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