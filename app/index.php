<?php
require 'vendor/autoload.php';

// Récupération des variables d'environnement
$mongoUri = getenv("MONGO_URI");
$database = getenv("MONGO_INITDB_DATABASE") ?: "mflix";
$collection = getenv("MONGO_INITDB_COLLECTION") ?: "movies";

try 
{
    // Connexion à MongoDB
    $client = new MongoDB\Client($mongoUri);
    $db = $client->$database;
    $movies = $db->$collection;

    echo "1. Obtenez le nombre total de films <br>"; 
    $count = $movies->countDocuments(['type' => 'movie']);
    echo $count . "<br>";

    echo "2. Obtenez le nombre total de séries <br>"; 
    $count = $movies->countDocuments(['type' => 'series']);
    echo "Nombre de séries : " . $count . "<br>";
    
    echo "3. Obtenez les 2 différents types de contenu présents dans la collection movies <br>"; 
    $distinctTypes = $movies->distinct('type');
    echo implode(", ", $distinctTypes) . "<br>";


    echo "4. Obtenez la liste des genres de contenus disponibles dans la collection movies. <br>"; 
    $distinctTypes = $movies->distinct('genres');
    echo implode(", ", $distinctTypes) . "<br>";


    echo "5. Récupérez les films depuis 2015 classés par ordre décroissant <br>"; 
    $filter = ['year' => ['$gte' => 2015], 'type' => 'movie'];
    $options = [ 'sort' => ['year' => -1]];
    $movies = $movies->find($filter, $options);
    foreach ($movies as $movie) {
        echo "Title: " . $movie['title'] . "<br>";
        echo "Year: " . $movie['year'] . "<br>";
        echo "Genres: " . implode(", ", $movie['genres']) . "<br><br>";
    }

    echo "6. Obtenez le nombre de films sortis depuis 2015 ayant remporté au moins 5 récompenses <br>"; 
    
    $filter = ['year' => ['$gte' => 2015],'type' => 'movie','awards.wins' => ['$gte' => 5]];
    $count = $movies->countDocuments($filter);
    echo $count . "<br>";

    echo "7. Parmi ces films, indiquez le nombre de films disponibles en français <br>"; 
    $filter = ['year' => ['$gte' => 2015],'type' => 'movie','awards.wins' => ['$gte' => 5],'languages' => 'French'];
    $count = $movies->countDocuments($filter);
    echo $count . "<br>";

    echo "8. Sélectionnez les films dont le genre est Thriller et Drama et indiquez leur nombre <br>"; 
    $filter = ['genres' => ['$all' => ['Thriller', 'Drama']]]; 
    $count = $movies->countDocuments($filter);
    echo $count . "<br>";

    echo "9. Sélectionnez le titre et les genres des films dont le genre est Crime ou Thriller <br>"; 
    $filter = ['genres' => ['$in' => ['Crime', 'Thriller']]];
    $options = ['projection' => ['title' => 1,'genres' => 1,'_id' => 0]];
    $movies = $movies->find($filter, $options) . "<br>";
    
    foreach ($movies as $movie) 
    {
        echo "Title: " . $movie['title'] . "\n";
        echo "Genres: " . implode(", ", $movie['genres']) . "\n\n";
    }

    echo "10. Sélectionnez le titre et les langues des films disponibles en français et en italien <br>"; 
    $filter = ['languages' => ['$all' => ['French', 'Italian']]];
    $options = ['projection' => ['title' => 1,'languages' => 1,'_id' => 0]];
    $movies = $movies->find($filter, $options);
    
    foreach ($movies as $movie) {
        echo "Title: " . $movie['title'] . "\n";
        echo "Languages: " . implode(", ", $movie['languages']) . "\n\n";
    }

    echo "11. Sélectionnez le titre et le genre des films dont la note IMDB est supérieure à 9 <br>"; 
    $query = ['imdb.rating' => ['$gt' => 9]];
    $projection = ['title' => 1, 'genres' => 1, '_id' => 0];

    $result = $movies->find($query, ['projection' => $projection]);

    foreach ($result as $movie) {
        echo "Title: " . $movie['title'] . "<br>";
        echo "Genres: " . implode(', ', $movie['genres']) . "<br><br>";
    }

    echo "12. Affichez le nombre de contenus dont le nombre d'acteurs au casting est égal à 4 <br>"; 
    $count = $movies->countDocuments(['cast' => ['$size' => 4]]);
    echo $count . "<br>";
    
    echo "13. Affichez : Le nombre de contenus (count), Le nombre total de récompense(totalAwards), Le nombre moyen de nominations (averageNominations), Le nombre moyen de récompenses (averageAwards) pour l'ensemble des contenus de la collection movies. <br>"; 
    $pipeline = [
        [
            '$group' => [
                '_id' => null,
                'count' => ['$sum' => 1],
                'totalAwards' => ['$sum' => '$awards.wins'],
                'averageNominations' => ['$avg' => '$awards.nominations'],
                'averageAwards' => ['$avg' => '$awards.wins']
            ]
        ]
    ];

    $result = $movies->aggregate($pipeline);

    foreach ($result as $data) {
        echo "Total movies: " . $data['count'] . "<br>";
        echo "Total awards won: " . $data['totalAwards'] . "<br>";
        echo "Average nominations: " . $data['averageNominations'] . "<br>";
        echo "Average awards won: " . $data['averageAwards'] . "<br>";
    }
    
    echo "14. Affichez le nombre d'acteurs au casting (castTotal) pour chaque contenu : <br>"; 
    $pipeline = [
        [
            '$project' => [
                'title' => 1,
                'castTotal' => ['$size' => '$cast']
            ]
        ]
    ];

    $result = $movies->aggregate($pipeline);

    foreach ($result as $movie) {
        echo "Title: " . $movie['title'] . "<br>";
        echo "Number of actors in cast: " . $movie['castTotal'] . "<br><br>";
    }
    
    echo "15. Calculez le nombre de fois que le terme 'Hollywood' apparaît dans le résumé des contenus (fullplot): <br>"; 
    $pipeline = [
        [
            '$project' => [
                'fullplot' => 1,
                'hollywoodCount' => [
                    '$size' => [
                        '$filter' => [
                            'input' => [
                                '$cond' => [
                                    'if' => ['$isArray' => ['$split' => ['$fullplot', ' ']]],
                                    'then' => ['$split' => ['$fullplot', ' ']],
                                    'else' => []
                                ]
                            ],
                            'as' => 'word',
                            'cond' => ['$eq' => ['$$word', 'Hollywood']]
                        ]
                    ]
                ]
            ]
        ],
        [
            '$group' => [
                '_id' => null,
                'totalHollywood' => ['$sum' => '$hollywoodCount']
            ]
        ]
    ];

    // Exécution de l'agrégation
    $result = $movies->aggregate($pipeline);

    // Affichage des résultats
    foreach ($result as $data) {
        echo "Total occurrences of 'Hollywood': " . $data['totalHollywood'] . "<br>";
    }
    
    echo "16. Trouvez les films sortis entre 2000 et 2010 qui ont une note IMDB supérieure à 8 et plus de 10 récompenses. <br>"; 
    $query = [
        "year" => ['$gte' => 2000, '$lte' => 2010],  // L'année est entre 2000 et 2010
        "imdb.rating" => ['$gt' => 8],  // La note IMDb est supérieure à 8
        "awards.wins" => ['$gt' => 10]  // Le nombre de prix remportés est supérieur à 10
    ];

    // Exécuter la requête find
    $result = $movies->find($query);

    // Afficher les résultats
    foreach ($result as $movie) {
        echo "Title: " . $movie['title'] . "<br>";
        echo "Year: " . $movie['year'] . "<br>";
        echo "IMDb Rating: " . $movie['imdb']['rating'] . "<br>";
        echo "Awards Wins: " . $movie['awards']['wins'] . "<br><br>";
    }

    echo "17. Proposez une nouvelle question complexe et fournissez la solution pour y répondre ainsi que la réponse. <br>";
    $query = [
        "year" => ['$gte' => 2000, '$lte' => 2015],  // L'année est entre 2000 et 2015
        "directors" => "Steven Spielberg",  // Le réalisateur est Steven Spielberg
        "awards.nominations" => ['$gt' => 20],  // Le nombre de nominations est supérieur à 20
        "imdb.rating" => ['$gt' => 7.5]  // La note IMDb est supérieure à 7.5
    ];

    // Exécuter la requête find
    $result = $movies->find($query);

    // Afficher les résultats
    foreach ($result as $movie) {
        echo "Title: " . $movie['title'] . "<br>";
        echo "Year: " . $movie['year'] . "<br>";
        echo "IMDb Rating: " . $movie['imdb']['rating'] . "<br>";
        echo "Awards Nominations: " . $movie['awards']['nominations'] . "<br><br>";
    }


} catch (Exception $e) {
    echo "<p>Erreur de connexion : " . $e->getMessage() . "</p>";
}

?>

