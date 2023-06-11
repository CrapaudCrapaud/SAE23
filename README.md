# SAE23

Projet de première année de BUT Réseaux & Télécommunications.

De nombreux capteurs sont répartis dans les bâtiments de l'IUT. Ils sont accessibles à l'URL mqtt.iut-blagnac.fr, port 1883, via MQTT. Chaque groupe devait récupérer, stocker et afficher les relevés de plusieurs capteurs au sein de plusieurs bâtiments. Pour ce faire, nous devions suivre deux techniques :

## Première méthode
La première méthode était plus longue et impliquait beaucoup plus d'investissement et de code. Il fallait:
- Penser et créer la base de données qui contient les bâtiments, les capteurs et les mesures ainsi que les identifiants de l'administrateur du site
- Créer un script qui récupère les données des capteurs et les envoie dans la base de données
- Créer un site web dynamique qui affiche ces données, offre une interface de gestion des données de chaque bâtiment par le gérant du bâtiment et une interface d'administration où l'administrateur a la possibilité de supprimer ou d'ajouter un bâtiment ou un capteur.

L'exécution du script de récupération des données devait être automatisée à l'aide du crontab

Tous les scripts devaient être commentés en anglais, et le site devait également contenir une page sur la gestion du projet (diagramme de Gantt, outil collaboratif employé, synthèses personnelles...).

### Base de données

Voici le schéma de la base de données sous MySQL :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/fcc585cd-91db-4c8b-9c6b-fec37ab66682)

- La table Batiment contient les informations relatives au bâtiment, ainsi que les identifiants de connexion de son gérant.
- La table Capteur contient les capteurs et est liée à la table Batiment via la clef étrangère id_bat qui fait référence à la clef primaire id_bat de la table Batiment.
- La table Mesure contient toutes les mesures des capteurs et est liée à la table Capteur grâce à la clef étrangère id_capt qui fait référence à la clef primaire id_capt de la table Capteur.
- Enfin, la table Administration est indépendante et contient les identifiants de connexion de l'administrateur du site

### Récupération des données

Nous avons choisi d'effectuer la récupération des données avec Python. Pour ce faire, nous avons séparé le programme en deux fichiers :
- config.py contient toutes les informations de configuration (configuration de MySQL et de MQTT)
- retrieve_data.py contient le code nécessaire pour récupérer les données des capteurs via MQTT et les insérer dans la table Mesure de la base de données

Il faut insérer ce script dans votre crontab pour qu'il puisse s'exécuter automatiquement toutes les 10 minutes, pour avoir un suivi cohérent des données des capteurs.

La commande à inscrire est "/bin/python3 /chemin/vers/le/fichier/retrieve_data.py" (notez que le chemin de l'exécutable python3 peut changer selon les environnements) :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/20219a1a-23f7-45b3-8d7e-026256464d93)

Exemple d'exécution du script :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/45a4e8d3-e8ee-4214-854a-e4fa228d2599)

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/ac357b91-275d-4216-885b-ffcc5a1bf391)

### Affichage des données sur le site web

Voici l'arborescence du site web :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/8adffa09-9423-40e0-a799-bb440f1a9c00)

- Le dossier **css** contient l'unique feuille de style, style.css, qui assure le RWD et un joli site vert
- Le dossier **img** contient les diverses images utilisées dans le site (logo, captures d'écran du diagramme de Gantt et du tableau Trello)
- Le dossier **inc** contient les scripts PHP qui sont inclus dans d'autres scripts PHP :
- - **db.php** se connecte à la base de données
- - **functions.php** offre des fonctions pour générer l'en-tête / la barre de navigation / le pied de page HTML, ainsi que pour vérifier le statut de l'utilisateur (visiteur, gestionnaire ou admin) et une fonction pour rediriger l'utilisateur vers la page désirée
- **admin.php** affiche le formulaire de connexion vers la partie d'administration, puis les 4 formulaires d'ajout et de suppression

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/2783a15e-ca46-4472-bffa-eabc02045465)

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/a5497d93-5c25-487e-b7b1-7f43f0efdb34)

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/d305b662-b44d-4502-b08b-ca3f6ffc28c2)

- **index.php** contient la page d'accueil, qui liste les bâtiments de la table Batiment et leurs capteurs respectifs et décrit brièvement le projet ainsi que les compétences impliquées :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/a7341fbf-0dbf-4aad-adcd-82506132cec9)

- **legal.php** contient les informations légales classiques
- **logout.php** est utilisé pour déconnecter le gérant / l'administrateur et rediriger vers la page d'accueil
- **management.php** affiche le formulaire de connexion et l'interface de gestion du bâtiment : sélection du capteur et de la plage temporelle à respecter pour afficher les valeurs.

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/8196140f-a62a-4190-af30-02dfccb2430b)
![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/9f7db2f3-4ba5-48ff-87a7-bb3a9db55482)

- **project.php** contient la partie gestion de projet : diagramme de Gantt, tableau collaboratif Trello, synthèses personnelles (problèmes rencontrées, degré de satisfaction, etc.) et synthèse globale.
- **sensors.php** permet d'afficher aux visiteurs la donnée la plus récente de chaque capteur du bâtiment choisi :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/abdaa62e-4d57-4dc2-8345-59e7f435523f)

Toutes ces pages articulent le site et en font une interface de gestion esthétique, dynamique et fonctionnelle.

## Deuxième méthode
La deuxième partie du projet était beaucoup plus facile et rapide. Il fallait utiliser l'outil Node-Red afin de récupérer les données des capteurs et de les insérer dans une base de données InfluxDB, puis de les afficher de manière minimaliste via NodeRed mais aussi via un dashboard Grafana qui exploitait la base de données InfluxDB.

Pour cela, nous devions déployer 3 dockers (un par service) et créer le flow Node-Red, la base de données InfluxDB et mettre en place le dashboard de Grafana et le lier à InfluxDB.

### Node-Red

Après avoir installé le docker de Node-Red avec la commande **docker run -d -p 1880:1880 --restart=always --name noderedSAE23 -v volNodeRed:/data nodered/node-red**, on accède à Node-Red au port 1880 via un navigateur :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/8c334e61-541a-40cf-b727-a78b5b5b3f6e)

Il faut ensuite configurer la connexion au serveur MQTT :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/4ad239d3-44c1-4825-ad03-baf5cae88811)

Les noeuds créés par la suite donnent ce rendu final :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/dbdd804d-95ff-442a-8fb8-1ce528e5b787)

Le switch est utilisé pour rendre le schéma plus simple et faciliter les modifications. Voici sa configuration :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/0b7e7210-fd49-48a5-a9c4-626662c9c9c7)

Les modules "set" reliés au switch permettent de récupérer les valeurs souhaitées. Voici par exemple le paramétrage d'un de ces modules pour récupérer la température dans une salle :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/3ca25ed5-ef80-4bb1-9004-81aa3da508d5)

Enfin, afin d'avoir une interprétation graphique minimaliste des données relevées, il faut configurer les différentes jauges. La configuration doit être répétée pour toutes les valeurs vouloues :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/9a18ad1b-035b-4b1a-820b-9a1828bb7c09)

Les jauges sont désormais accessibles à l'URL http://@ip_de_la_vm:1880/ui

### InfluxDB

Après création du docker (**docker run -d -p 8086:8086 --restart=always --name influxdbSAE23 -v volinfluxdb:/var/lib/influxdb influxdb:1.8**), de la base de données (**create database SAE23**) et d'un utilisateur root (**create user root with password 'passroot' with all privileges**), il faut configurer Node-Red afin que les données soient envoyées dans la base de données fraîchement créée dans InfluxDB.

Le noeud InfluxDB out est configuré avec le nom de la base de données, sont hôte et le port d'écoute ainsi que les identifiants de connexion. Une fois configuré, il faut sélectionner les mesures à envoyer à la base de données SAE 23 :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/95d6cc6f-12fd-45aa-9845-4edd43043b3f)

On peut vérifier dans InfluxDB la récupération des données depuis Node-Red :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/b51882f4-7c62-444f-a8cb-e8352cb0d081)

### Grafana

Enfin, nous devons déployer un dashboard Grafana pour transformer toutes ces valeurs en jolis graphiques. Après avoir crée le docker (**docker  run -d -p 3000:3000 –restart=always –name grafanaSAE23 -v volGrafana:/var/lib/grafana grafana/grafana**), on se connecte à Grafana au port 3000 :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/50f55c4b-3d22-48cc-8ce0-db77ee0b4518)

Il faut dans un premier temps que Grafana accède au docker InfluxDB :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/ec3b26cd-1caa-4f7b-94dd-1c0c5da9134e)

Puis il est nécessaire d'indiquer les informations de la base de données :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/e5446c98-3ebe-4cc5-83b9-4f9548ed85ef)

On crée par la suite notre premier dashboard :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/fc85f233-4c5b-4d62-9b58-508a81faeb37)

On formalise alors la requête qui sera réalisée périodiquement sur la base de données des capteurs.
La requête à réaliser est “**FROM default temperature WHERE building = RT AND room = E208**”.

Ce processus doit être répété pour les autres valeurs dans la base de données SAE23 d'influxDB.

# Hébergement

Nous avons décidé d'héberger le projet sur eohost. La page est disponible à l'URL suivante : http://ramounet.atwebpages.com/SAE23/
Malheureusement, la base de données MySQL hébergée n'est pas accessible depuis l'extérieur :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/87c56ada-92c0-4612-bdf2-45d64874b86a)

Cependant, on peut tester le site en important le schéma de la base et en insérant des données, et vérifier si l'affichage se fait correctement :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/4e88cd58-dcb4-4805-9b05-6122c37a8133)

Il est nécessaire de modifier au préalable la version de PHP, sinon les fonctions mysqli_fetch_assoc() et autres ne sont pas reconnues :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/655df9b3-4f44-496e-9eeb-89534a395577)

Recréation de la base de données :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/c2daeb05-15e8-49ee-8fe8-a370a8f2442a)

Importation du schéma de la base sae23 :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/770ca0b6-6b45-4735-8278-c74c0f19f05b)

Importation du dossier contenant tous les fichiers relatifs au site :

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/29652d79-4b99-4ec5-a90f-c449d89418ae)

La dernière étape réside dans la modification des informations de connexion à la nouvelle base de données MySQL.

Finalement, le site est fonctionnel, mais la base de données devra être mise à jour manuellement par l'intermédiaire de PHPMyAdmin.

![image](https://github.com/CrapaudCrapaud/SAE23/assets/133014379/7aaf48e7-5ddb-4c2b-8f1b-88a1033f719c)
