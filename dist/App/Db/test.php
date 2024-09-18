<?php
require '../../vendor/autoload.php'; // Inclure l'autoload de Composer

use MongoDB\Client;
use MongoDB\Exception\Exception;

// URI de connexion MongoDB Atlas (Remplace <username> et <password> par tes identifiants)
$uri = "mongodb+srv://myriamkuehn:dsF2gWNShO0shGLu@gamestore.lyjmd.mongodb.net/";

try {
    // Créer une instance de MongoDB\Client avec l'URI MongoDB Atlas
    $client = new MongoDB\Client($uri);

    // Sélectionner une base de données
    $database = $client->selectDatabase('gamestore'); // Remplace 'myDatabase' par le nom de ta base de données

    // Sélectionner une collection
    $collection = $database->selectCollection('sales'); // Remplace 'myCollection' par le nom de ta collection

    echo "Connexion réussie à MongoDB Atlas!\n";

    // Insérer un document dans la collection
    $result = $collection->insertOne([
        'nom' => 'Julie Lefebvre',
        'email' => 'julie.lefebvre@example.com',
        'age' => 30
    ]);

    echo "Document inséré avec l'ID : " . $result->getInsertedId() . "\n";

} catch (Exception $e) {
    echo "Erreur de connexion à MongoDB : " . $e->getMessage();
}