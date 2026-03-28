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
        <nav>
            <ul class="nav justify-content-center mb-3">
                <li class="nav-item"><a class="nav-link" href="#">Fonction 1</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Fonction 2</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Fonction 3</a></li>
            </ul>
        </nav>
    </header>

    <main>
        
        <aside id="aside">
            <h3>Recherche</h3>
            <div class="form-floating mb-3">
                <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                    <option value="1" selected>Commence par ...</option>
                    <option value="2">.. Contient ..</option>
                    <option value="3">... Finit par</option>
                </select>
                <label for="floatingSelect">Option de recherche :</label>
            </div>

            <form @submit.prevent="geocode">
        
                <div style="position: relative;" class="mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="floatingInput" placeholder="Rechercher un lieu..." v-model="search" @input="autocomplete" autocomplete="off">
                        <label for="floatingInput">Lieu</label>
                    </div>
                    <ul id="villes" v-if="villes.length" class="list-group" style="position: absolute; top: 100%; left: 0; right: 0; z-index: 1000; max-height: 200px; overflow-y: auto; box-shadow: 0px 4px 8px rgba(0,0,0,0.2);">
                        <li v-for="ville in villes" @click="recupGeometry(ville)" class="list-group-item list-group-item-action" style="cursor: pointer;">
                            {{ ville.nom }} - {{ ville.insee }}
                        </li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-primary">OK</button>
            </form>
        </aside>

    <div id="map"></div>

    </main>

    <footer>
        <p>Projet Webmapping - Raphaël GONZO-MASSOL - 2026 </p>
    </footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="assets/home.js"></script>

</body>
</html>