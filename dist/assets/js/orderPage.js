/*********************************/

/* INITIALISATION DE DATATABLES */

/********************************/
$(document).ready(function() {
  // Initialiser DataTables
  const table = $('#orderTable').DataTable({
    "responsive": true,
    "paging": false,
    "ordering": true,
    "order": [[0, 'asc']],
    "searching": true,
    "language": {
      "zeroRecords": "Aucune commande trouvée",
      "info": "",
      "infoEmpty": "",
      "infoFiltered": ""
    },
  });
});