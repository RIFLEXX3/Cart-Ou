var map = L.map('map').setView([45.757, 4.833], 10);

const mapboxAccessToken = 'pk.eyJ1IjoicmdvbnpvIiwiYSI6ImNtbTdodmo1czBuM2cycnNnbjU3a2V6cWYifQ.qKXThwmlE9rP8wIRTuG1Tg';

L.tileLayer(`https://api.mapbox.com/styles/v1/mapbox/streets-v12/tiles/256/{z}/{x}/{y}?access_token=${mapboxAccessToken}`, {
    maxZoom: 19,
    attribution: '© <a href="https://www.mapbox.com/about/maps/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);


var marker = L.geoJSON(null, {
    onEachFeature: function(feature, layer) {
        let nom = feature.properties.label || feature.properties.name || 'Lieu inconnu';
        layer.bindPopup(nom);
    }
}).addTo(map);

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
        option: '1', //option par défaut 1 -> Commence par...
    };
  },
  
  methods: {
    haversine(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon/2) * Math.sin(dLon/2);
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    },

    recherche() {
        let url = '/villes?recherche=' + this.search + '&option=' + this.option;
        fetch(url)
        .then(res => {
            return res.json();
        })
        .then(result => {
            this.villes = result;
            console.log("Villes :", result);
            marker.clearLayers();        
            result.forEach(ville => {
                fetch('https://data.geopf.fr/geocodage/search?q=' + ville.nom + '&limit=1')
                .then(response => response.json())
                .then(geojson => {
                    if (geojson.features && geojson.features.length > 0) {
                        console.log(geojson.features[0]);
                        let feature = geojson.features[0];
                        
                        let dist = this.haversine(48.841063, 2.587373, ville.lat, ville.lon);

                        feature.properties.label = ville.nom + " est à " + dist.toFixed(2) + " km de l'ENSG";

                        marker.addData(feature);

                        let bounds = marker.getBounds();
                        if (bounds.isValid()) {
                            map.fitBounds(bounds);
                        }
                    }
                })
            });
        });
    },

    rechercheFonc(ville, option) {
        this.search = ville;
        this.option = option;
        this.recherche();
    },

}}).mount('#aside');