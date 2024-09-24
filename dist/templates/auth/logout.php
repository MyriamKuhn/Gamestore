<?php

//Prévient les attaques de fixation de session
session_regenerate_id(true);
// Détruit la session
session_destroy(); 
//Supprime les données du tableau $_SESSION
unset($_SESSION); 

header("Location: index.php");