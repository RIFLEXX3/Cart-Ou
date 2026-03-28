var map = L.map('map').setView([45.757, 4.833], 10);

const mapboxAccessToken = 'VOTRE_CLE_MAPBOX_ICI';

L.tileLayer(`https://api.mapbox.com/styles/v1/mapbox/streets-v12/tiles/256/{z}/{x}/{y}?access_token=${mapboxAccessToken}`, {
    maxZoom: 19,
    attribution: '© <a href="https://www.mapbox.com/about/maps/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);


var marker = L.geoJSON().addTo(map);
var popup = L.popup();

function onMapClick(e) {
    popup
        .setLatLng(e.latlng)
        .setContent("Vous avez cliqué ici : <br>" + e.latlng.lat.toFixed(4) + ", " + e.latlng.lng.toFixed(4))
        .openOn(map);
}
map.on('click', onMapClick);

navigator.geolocation.getCurrentPosition(function (position) {
    var lat = position.coords.latitude;
    var lon = position.coords.longitude;
    
    map.setView([lat, lon], 13);
    
    L.marker([lat, lon]).addTo(map)
        .bindPopup("Vous êtes ici")
        .openPopup();
});

Vue.createApp({
  data() {
    return {
        search: '',
        villes: [],
    };
  },
  
  computed: {
    urlrecherche() {
        return 'https://data.geopf.fr/geocodage/search?q=' + this.search;
    },
  },

  methods: {
    geocode() {
        this.villes = []; // Vider la liste
        fetch(this.urlrecherche)
        .then(response => response.json())
        .then(result => {
            marker.clearLayers(); // Nettoyer l'ancienne recherche
            
            // On vérifie qu'il y a bien des résultats avant de dessiner
            if (result.features && result.features.length > 0) {
                marker.addData(result); 
                
                let bounds = marker.getBounds();
                if (bounds.isValid()) {
                    map.fitBounds(bounds);
                }
            }
        });
    },
    
    autocomplete() {
        let url = '/villes?recherche=' + this.search;
        fetch(url)
        .then(res => res.json())
        .then(result => {
            this.villes = result;
        });
    },
    
    recupGeometry(ville){ 
        this.villes = []; 
        this.search = ville.nom; 
        let url = '/villes2?insee=' + ville.insee;
        
        fetch(url)
        .then(res => res.json())
        .then(result => {
            marker.clearLayers();
            marker.addData(result); 
            
            let bounds = marker.getBounds();
            if (bounds.isValid()) {
                map.fitBounds(bounds);
            }
        });
    }
  }
}).mount('#aside');