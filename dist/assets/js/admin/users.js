/***********/

/* IMPORT */

/**********/
import { secureInput } from '../utils.js';


/*************************/

/* VERIFICATIONS DU MAIL */

/*************************/
// Récupération des éléments
const emailInput = document.querySelector('input[name=email]');

// Fonction de vérification du champs
function checkEmail() {
  const email = emailInput.value;
  const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  if (!emailRegex.test(secureInput(email).trim())) {
    emailInput.classList.add("is-invalid");
  } else {
    emailInput.classList.remove("is-invalid");
  }
}

// Ecoute des événements
emailInput.addEventListener('input', checkEmail);

// Au clic sur le bouton de validation, vérification des champs et ajout de la classe was-validated pour la validation de Bootstrap et empêcher l'envoi du formulaire si les champs ne sont pas valides
const employeForm = document.getElementById('user-form');

employeForm.addEventListener('submit', e => {
  if (!e.target.checkValidity()) {
    e.preventDefault();
    e.stopPropagation();
  }
  e.target.classList.add('was-validated');
});


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
  const table = $('#usersTable').DataTable({
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
    ],
    initComplete: function () {
      document.getElementById('usersTable').classList.remove('visually-hidden');
      document.getElementById('loading').classList.add('visually-hidden');
    }
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