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
  const table = $('#employeTable').DataTable({
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
    "order": [[5, 'asc']],
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
    ]
  });

  // Filtrer par nom
  $('#nameFilter').on('keyup', function() {
    table.column(1).search(this.value).draw();  
  });

  // Filtrer par magasin
  $('#storeFilter').on('change', function() {
    table.column(4).search(this.value).draw();  
  });

  // Filtrer par statut
  $('#statusFilter').on('change', function() {
    table.column(5).search(this.value).draw();  
  });
});