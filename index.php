<?php

declare(strict_types=1);

require_once 'flight/Flight.php';

$link = mysqli_connect('localhost', 'root', 'root', 'geobase'); 
if (!$link) {
    die('Erreur de connexion MySQL : ' . mysqli_connect_error());
}

mysqli_set_charset($link, "utf8");

Flight::set('geobase', $link);
Flight::get('geobase');


Flight::route('/', function() {
    Flight::render('home');
});

Flight::route('/villes', function() {
    $link = Flight::get('geobase');
    $recherche = $_GET['recherche'];
    $villes = [];
    $query = "SELECT nom, insee FROM communes WHERE nom LIKE '$recherche%' ORDER BY nom LIMIT 10";
    $recherche = mysqli_query($link, $query);
    foreach ($recherche as $ville) {
        $villes[] = $ville;
    }
    Flight::json($villes);
});

Flight::route('/villes2', function() {
    $link = Flight::get('geobase');
    $insee = $_GET['insee'];
    $villes = [];
    $query = "SELECT ST_AsGeoJson(geometry) AS geom FROM communes WHERE insee = '$insee'";
    $result = mysqli_query($link, $query);
    $ville = mysqli_fetch_assoc($result); // fonction qui renvoi le premier element du tableau associatif
    $villes['geom'] = json_decode($ville['geom']); // on decode le json pour le transformer en objet utilisable en js car c'est une chaine de caratères
    Flight::json($villes['geom']); // on retourne uniquement la géométrie de la ville, pas besoin du nom pour l'affichage sur la carte
});

Flight::route('/test-db', function () {
    $host = 'db';
    $port = 5432;
    $dbname = 'mydb';
    $user = 'postgres';
    $pass = 'postgres';

    // Connexion BDD
    $link = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$pass");

    $sql = "SELECT * FROM points";
    $query = pg_query($link, $sql);
    $results = pg_fetch_all($query);
    Flight::json($results);
});

Flight::start();
?>