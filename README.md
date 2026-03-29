# Description de l'outil Cart-Où

Bienvenue sur Cart-Où, un candidat peu serieux à la succession de Google Maps !
Cart-Où est un projet Webmapping simple d'utilisation permettant de retrouver les communes selon des filtres choisis par l'utilisateur.
Ce projet rentre dans le cadre du cours de webmapping de Master 1 Géomatique 2025-2026 de Géodata Paris, dispensé par Vincent De Oliveira (@iamvdo)

---
## 1. Entrée sur le site

Afin d'accèder à Cart-Où, il vous sera demander d'utiliser un logiciel permettant l'hébergement en local des fichiers.
Nous vous recommandons d'utiliser **MAMP**.

Pour ce faire :
- Téléchargé le logiciel puis rendez-vous dans les préférences de ce dernier :
<img width="738" height="668" alt="image" src="https://github.com/user-attachments/assets/204b72ef-c358-4cff-be8e-b12c81822bdc" />

- Rendez-vous dans l'onglet 'Server' pour entrer un dossier :
<img width="825" height="714" alt="image" src="https://github.com/user-attachments/assets/25f0c8c1-6830-436b-89c2-5f2ee43bed69" />

- Renseignez le dossier contenant Cart-Où :
<img width="1181" height="893" alt="image" src="https://github.com/user-attachments/assets/94bdc7aa-3aad-4f95-8afc-35fff5737e55" />

- Ensuite rendez-vous sur votre navigateur internet puis entrée 'localhost' dans la barre de recherche, vous tomberez alors sur la page de Cart-Où !

---
## 2. Interface graphique

Cart-Où ce présente de la manière suivante :
<img width="2559" height="1399" alt="image" src="https://github.com/user-attachments/assets/f8af3488-660f-4759-a7a7-1ebdb60598d8" />

- Au centre, vous retrouvez l'outil de fitrage pour la recherche avec 3 fonctionnalitées déjà paramétrées :
<img width="246" height="269" alt="image" src="https://github.com/user-attachments/assets/726fc370-141e-4b23-99f3-ac370a05491e" />

- En bas, vous retrouvez la visualisation sur la carte de la recherche que vous effecturez grâce à l'outil de recherche :
<img width="764" height="408" alt="image" src="https://github.com/user-attachments/assets/7a2875a2-fef1-4a4a-852c-5bd14bf97899" />

---
## 3. Moteur de recherche

Pour effectuer une recherche sur Cart-Où, il vous suffit simplement de rentrer le type de filtrage des communes, c'est-à-dire si :
- La commune **contient"" certaines lettres
- la commune **commence"" par certaines lettres
- la commune **fini** par certaines lettres

Puis il suffit de rentrer les lettres désirées.

Techniquement, cela se traduit par l'appel d'une requête SQL reliée à une page Javascript permettant de rechercher dans la base de données les communes selon le filtrage choisi.
<img width="873" height="561" alt="image" src="https://github.com/user-attachments/assets/04514e87-749d-49b4-8f89-d152f5a4f379" />

La recherche va ensuite s'afficher sur la carte avec un curseur. Il est possible d'intéragir avec ce dernier pour pouvoir avec des informations supplémentaires tel que le nom de la commune ou encore la distance par rapport à un point de référence [cf 5.](#5.Fonctionnalité-de-calcul-de-distance-par-rapport-à-un-point-de-référence)

---
## 4. Affichage cartographique

Il est à noté que Cart-Où fonctionne avec une **API Mapbox**. Ainsi, il est nécessaire de posséder un compte Mapbox pour pouvoir accéder à un Token Access afin d'avoir le fond de carte Mapbox.
Pour autant, il est toujours possible de modifier ce paramètre afin de pouvoir mettre un autre fond de carte (OpenStreetMap, ...).

<img width="1419" height="143" alt="image" src="https://github.com/user-attachments/assets/4c06d130-66d0-4a4b-a80f-e44ae276fc9d" />

---
## 5. Fonctionnalité de calcul de distance par rapport à un point de référence

Enfin, Cart-où propose à ses utilisateurs de calculer automatiquement la distance entre les communes recherchées et un point de référence.
Nous avons choisi de prendre comment point de référence Géodata Paris, ayant comme coordonnées __lat : 48.841063, long : 2.587373__
Afin de calculer la distance entre le point de référence et les différentes communes, nous avons du récuperer les coordonnées de ces points lors de la requête à la base de données.
Une requête à la base de données :
'''
$query = "SELECT nom, insee, ST_X(ST_GeomFromText(ST_AsText(ST_Centroid(geometry)),4326)) AS lon, ST_Y(ST_GeomFromText(ST_AsText(ST_Centroid(geometry)),4326)) AS lat FROM communes WHERE nom LIKE '$filtre' ORDER BY nom LIMIT 10";
'''

Une fois les coordonnées récupérées, nous nous sommes aidés de la **Formule de haversine** qui permet de déterminer la distance du grand cercle entre deux points d'une sphère, à partir de leurs longitudes et latitudes (Wikipédia).

## Formule de Haversine

$$d = 2R \cdot \arctan2\left(\sqrt{a},\ \sqrt{1-a}\right)$$

où :

$$a = \sin^2\!\left(\frac{\Delta\varphi}{2}\right) + \cos(\varphi_1)\cdot\cos(\varphi_2)\cdot\sin^2\!\left(\frac{\Delta\lambda}{2}\right)$$

avec :

| Symbole | Signification |
|---|---|
| $R$ | Rayon de la Terre (6371 km) |
| $\varphi_1,\ \varphi_2$ | Latitudes des points A et B (en radians) |
| $\Delta\varphi$ | $\varphi_2 - \varphi_1$ |
| $\Delta\lambda$ | $\lambda_2 - \lambda_1$ (différence de longitudes) |
| $d$ | Distance en km |

<img width="1236" height="313" alt="image" src="https://github.com/user-attachments/assets/705ad825-09c4-4ee3-acfc-8ee9594ced92" />

## Calcul de distance — Formule de Haversine

La fonction `haversine(lat1, lon1, lat2, lon2)` calcule la distance en **kilomètres** entre deux points GPS.

### Pourquoi Haversine ?

Contrairement à la géométrie euclidienne (Pythagore), la formule de Haversine tient compte de la **courbure de la Terre**. Elle est précise pour toutes les distances, même intercontinentales.

### Les 3 étapes

*** 1. Conversion degrés → radians**
Les fonctions trigonométriques (`sin`, `cos`) travaillent en radians, pas en degrés.
```
dLat = (lat2 - lat1) × π / 180
dLon = (lon2 - lon1) × π / 180
```

*** 2. Calcul de `a` (carré de la moitié de la corde)**
`a` est un nombre entre 0 et 1 qui représente la relation angulaire entre les deux points.
```
a = sin²(dLat/2) + cos(lat1) × cos(lat2) × sin²(dLon/2)
```
- `a = 0` → les deux points sont identiques
- `a = 1` → les deux points sont aux antipodes

** *3. Conversion en distance réelle**
```
d = R × 2 × atan2(√a, √(1-a))
```
Avec `R = 6371 km` (rayon moyen de la Terre).

Ainsi, on obtient à la fin un pop-up permettant d'indiquer la commune que l'on regarde et aussi la distance entre cette dernière et l'école Géodata Paris.

<img width="768" height="409" alt="image" src="https://github.com/user-attachments/assets/dbaae905-0750-4f8b-8a05-d0a25be77dc9" />

