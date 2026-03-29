<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/home.css">
    <title>Cart'Où - Accueil</title>
</head>
<body>

    <header>
        <h1>Cart'Où</h1>
        <p>Bienvenue sur Cart'Où, l'appli qui essaye d'imiter Google Maps mais sans jamais l'égaler !</p>
    </header>

    <aside id="aside">
        <h3>Recherche</h3>
        <ul class="nav justify-content-center mb-3">
                <li class="nav-item">
                    <button type="button" @click.prevent="rechercheFonc('Annec','1')" class="btn btn-light">Commence</button>
                </li>
                <li class="nav-item" @click="">
                    <button type="button" @click.prevent="rechercheFonc('ntfo','2')" class="btn btn-light">Contient</button>
                </li>
                <li class="nav-item" @click="">
                    <button type="button" @click.prevent="rechercheFonc('aen','3')" class="btn btn-light">Fini</button>
                </li>
            </ul>
        <div class="form-floating mb-3">
            <select class="form-select" id="floatingSelect" aria-label="Floating label select example" v-model="option">
                <option value="1" selected>Commence par ...</option>
                <option value="2">.. Contient ..</option>
                <option value="3">... Finit par</option>
            </select>
            <label for="floatingSelect">Option de recherche :</label>
        </div>

        <form @submit.prevent="recherche">
            <div style="position: relative;" class="mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInput" placeholder="Rechercher un lieu..." v-model="search">
                    <label for="floatingInput">Lieu</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">OK</button>
        </form>
    </aside>

    <main>
        <div id="map"></div>
    </main>

    <footer>
        <p>Projet Webmapping - Raphaël GONZO-MASSOL - 2026 </p>
    </footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="assets/home.js"></script>
    <!-- <script src="assets/test.js"></script> -->

</body>
</html>