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
  const table = $('#ordersTable').DataTable({
    "responsive": true,
    "paging": true,
    "pageLength": 10,
    "lengthMenu": [5, 10, 25, 50],
    "ordering": true,
    "order": [[4, 'desc']],
    "searching": true,
    "language": {
      "paginate": {
        "first":      "Première",
        "last":       "Dernière",
        "next":       "Suivante",
        "previous":   "Précédente"
      },
      "lengthMenu": "Afficher _MENU_ entrées par page",
      "zeroRecords": "Aucune commande trouvée",
      "info": "",
      "infoEmpty": "",
      "infoFiltered": ""
    },
    "columnDefs": [
      { "orderable": false, "targets": 5 }  // Désactiver le tri sur la dernière colonne (Actions)
    ]
  });

  // Filtrer par numéro de commande
  $('#orderIdFilter').on('keyup', function() {
    table.column(0).search(this.value).draw();  
  });

  // Filtrer par nom du client
  $('#clientFilter').on('keyup', function() {
    table.column(2).search(this.value).draw();  
  });

  // Filtrer par statut
  $('#statusFilter').on('change', function() {
    table.column(4).search(this.value).draw();  
  });

  // Filtrer par date unique
  $('#dateFilter').on('change', function() {
    const selectedDate = $(this).val(); // Récupérer la date choisie par l'utilisateur (format YYYY-MM-DD)
    table.draw(); 
  });

  // Ajouter un filtre personnalisé pour la colonne de date
  $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
    const selectedDate = $('#dateFilter').val();
    const orderDate = data[1]; 
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