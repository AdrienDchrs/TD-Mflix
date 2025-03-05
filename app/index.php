<?php
require 'vendor/autoload.php';

// Récupération des variables d'environnement
$mongoUri = "mongodb://user:userpassword@mongodb_mflix_container:27017/mflix?authSource=admin";
$database = getenv("MONGO_INITDB_DATABASE") ?: "mflix";
$collection = getenv("MONGO_INITDB_COLLECTION") ?: "movies";

try 
{
    echo "Mongo URI: " . getenv("MONGO_URI") . "<br>";
    // Connexion à MongoDB
    $client = new MongoDB\Client($mongoUri);
    $db = $client->$database;
    $movies = $db->$collection;

    // Test : récupérer 5 films
    $result = $movies->find([], ['limit' => 5]);

    echo "<h2>Films dans la collection :</h2>";
    foreach ($result as $movie) {
        echo "<p>" . ($movie["title"] ?? "Titre inconnu") . "</p>";
    }
} catch (Exception $e) {
    echo "<p>Erreur de connexion : " . $e->getMessage() . "</p>";
}
?>

