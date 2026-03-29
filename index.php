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
    $option = $_GET['option'];
    $filtre = "";
    
    if ($option == '1') {
        $filtre = "$recherche%";
    } 
    else if ($option == '2') {
        $filtre = "%$recherche%";
    } 
    else if ($option == '3') {
        $filtre = "%$recherche";
    }
    
    $villes = [];
    $query = "SELECT nom, insee, ST_X(ST_GeomFromText(ST_AsText(ST_Centroid(geometry)),4326)) AS lon, ST_Y(ST_GeomFromText(ST_AsText(ST_Centroid(geometry)),4326)) AS lat FROM communes WHERE nom LIKE '$filtre' ORDER BY nom LIMIT 10";
    $recherche = mysqli_query($link, $query);
    foreach ($recherche as $ville) {
        $villes[] = $ville;
    }
    Flight::json($villes);
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