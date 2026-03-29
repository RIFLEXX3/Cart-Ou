# Description de l'outil Cart'Où

Bienvenue sur Cart'Où, un candidat peu sérieux à la succession de Google Maps !  
Cart'Où est un projet de webmapping simple d'utilisation permettant de retrouver des communes selon des filtres choisis par l'utilisateur.  
Ce projet s'inscrit dans le cadre du cours de webmapping de Master 1 Géomatique 2025-2026 de Géodata Paris, dispensé par Vincent De Oliveira ([@iamvdo](https://github.com/iamvdo)).

---

## 1. Accès au site

Afin d'accéder à Cart'Où, vous aurez besoin d'un logiciel permettant l'hébergement local des fichiers.  
Nous vous recommandons d'utiliser **MAMP**.

Pour ce faire :

1. Téléchargez le logiciel puis rendez-vous dans ses préférences :

<img width="738" height="668" alt="image" src="https://github.com/user-attachments/assets/204b72ef-c358-4cff-be8e-b12c81822bdc" />

2. Rendez-vous dans l'onglet **Server** pour sélectionner un dossier :

<img width="825" height="714" alt="image" src="https://github.com/user-attachments/assets/25f0c8c1-6830-436b-89c2-5f2ee43bed69" />

3. Renseignez le dossier contenant Cart'Où :

<img width="1181" height="893" alt="image" src="https://github.com/user-attachments/assets/94bdc7aa-3aad-4f95-8afc-35fff5737e55" />

4. Ouvrez votre navigateur et saisissez `localhost` dans la barre d'adresse — vous arriverez directement sur Cart'Où !

---

## 2. Interface graphique

Cart'Où se présente de la manière suivante :

<img width="2559" height="1399" alt="image" src="https://github.com/user-attachments/assets/f8af3488-660f-4759-a7a7-1ebdb60598d8" />

- **Au centre** : l'outil de filtrage pour la recherche, avec 3 fonctionnalités prédéfinies :

<img width="246" height="269" alt="image" src="https://github.com/user-attachments/assets/726fc370-141e-4b23-99f3-ac370a05491e" />

- **En bas** : la visualisation cartographique des résultats de votre recherche :

<img width="764" height="408" alt="image" src="https://github.com/user-attachments/assets/7a2875a2-fef1-4a4a-852c-5bd14bf97899" />

---

## 3. Moteur de recherche

Pour effectuer une recherche sur Cart'Où, sélectionnez d'abord le type de filtre souhaité :

- La commune **contient** certaines lettres
- La commune **commence** par certaines lettres
- La commune **finit** par certaines lettres

Puis saisissez les lettres désirées et cliquez sur **OK**.

Techniquement, cela se traduit par une requête SQL transmise via JavaScript, qui interroge la base de données selon le filtre choisi.

<img width="873" height="561" alt="image" src="https://github.com/user-attachments/assets/04514e87-749d-49b4-8f89-d152f5a4f379" />

Les résultats s'affichent ensuite sur la carte sous forme de marqueurs. En cliquant sur un marqueur, vous obtenez des informations supplémentaires : le nom de la commune et sa distance par rapport au point de référence (voir [section 5](#5-fonctionnalité-de-calcul-de-distance-par-rapport-à-un-point-de-référence)).

---

## 4. Affichage cartographique

Cart'Où fonctionne avec l'**API Mapbox**. Il est donc nécessaire de posséder un compte Mapbox pour obtenir un token d'accès et afficher le fond de carte.  
Il reste cependant possible de remplacer ce fond de carte par une autre source (OpenStreetMap, IGN, etc.) en modifiant le paramètre correspondant dans le code.

<img width="1419" height="143" alt="image" src="https://github.com/user-attachments/assets/4c06d130-66d0-4a4b-a80f-e44ae276fc9d" />

---

## 5. Fonctionnalité de calcul de distance par rapport à un point de référence

Cart'Où propose de calculer automatiquement la distance entre les communes recherchées et un point de référence.  
Nous avons choisi comme point de référence **Géodata Paris**, dont les coordonnées sont : `lat : 48.841063, lon : 2.587373`.

### Récupération des coordonnées

Les coordonnées de chaque commune sont récupérées directement depuis la base de données via la requête SQL suivante :
```sql
SELECT nom, insee,
  ST_X(ST_GeomFromText(ST_AsText(ST_Centroid(geometry)), 4326)) AS lon,
  ST_Y(ST_GeomFromText(ST_AsText(ST_Centroid(geometry)), 4326)) AS lat
FROM communes
WHERE nom LIKE '$filtre'
ORDER BY nom
LIMIT 10
```

### Formule de Haversine

Une fois les coordonnées récupérées, la distance est calculée grâce à la **formule de Haversine**, qui permet de déterminer la distance du grand cercle entre deux points d'une sphère à partir de leurs latitudes et longitudes.

$$d = 2R \cdot \arctan2\left(\sqrt{a},\ \sqrt{1-a}\right)$$

où :

$$a = \sin^2\!\left(\frac{\Delta\varphi}{2}\right) + \cos(\varphi_1)\cdot\cos(\varphi_2)\cdot\sin^2\!\left(\frac{\Delta\lambda}{2}\right)$$

| Symbole | Signification |
|---|---|
| $R$ | Rayon de la Terre (6371 km) |
| $\varphi_1,\ \varphi_2$ | Latitudes des points A et B (en radians) |
| $\Delta\varphi$ | $\varphi_2 - \varphi_1$ |
| $\Delta\lambda$ | $\lambda_2 - \lambda_1$ (différence de longitudes) |
| $d$ | Distance résultante en kilomètres |

### Les 3 étapes du calcul

**Étape 1 — Conversion degrés → radians**  
Les fonctions trigonométriques (`sin`, `cos`) travaillent en radians :
```
dLat = (lat2 - lat1) × π / 180
dLon = (lon2 - lon1) × π / 180
```

**Étape 2 — Calcul de `a`**  
`a` est un nombre compris entre 0 et 1 représentant la relation angulaire entre les deux points :
```
a = sin²(dLat/2) + cos(lat1) × cos(lat2) × sin²(dLon/2)
```
- `a = 0` → les deux points sont identiques
- `a = 1` → les deux points sont aux antipodes

**Étape 3 — Distance finale**  
```
d = R × 2 × atan2(√a, √(1-a))
```

### Résultat

La distance calculée s'affiche directement dans le pop-up du marqueur, indiquant le nom de la commune ainsi que sa distance par rapport à Géodata Paris.

<img width="768" height="409" alt="image" src="https://github.com/user-attachments/assets/dbaae905-0750-4f8b-8a05-d0a25be77dc9" />
