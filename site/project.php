<?php

	// project.php
	// Contains the project management and additional information about it

	// start new or resume existing session
	session_start();

  // retrieve useful code (connection to database and various PHP functions)
	require('inc/db.php');
	require('inc/functions.php');

	// fetch all the buildings from the database into an array so as to give this array
  // to the html_nav() function used to build the dynamic navigation bar
	$buildings_query = mysqli_query($db, 'SELECT id_bat, nom_bat FROM Batiment');

	$buildings = mysqli_fetch_all($buildings_query);

  // Generate the HTML header part. First argument is the title, second one is the description meta data.
	html_header(
    'SAE 23 - Gestion de projet',
    'Page destinée à la gestion du projet et au ressenti final quant à son résultat'
  );

  // Generate the navigation bar
	html_nav($buildings);

?>
<p>
  <a href="index.php" title="Retour à l'accueil">Accueil</a> &nbsp;>>&nbsp; 
  <a href="#" title="Gestion de projet">Gestion de projet</a>
</p>
<h2>Gestion de projet et informations complémentaires</h2>
<main>
	<h3>Diagramme de Gantt final</h3>
	<p>Le diagramme de Gantt permet de répartir les tâches dans le temps et dans l'équipe. Voici le diagramme de Gantt finalement réalisé :</p>
	<img class='img-center' alt='Diagramme de Gantt - première partie' src='img/gantt1.png'>
	<br><br>
	<img class='img-center' alt='Diagramme de Gantt - deuxième partie' src='img/gantt2.png'>
	<div class='margin'></div>
	<h3>Tableau Trello</h3>
	<p>Nous avons utilisé l'outil Trello pour suivre l'avancement de la progression des différentes tâches réparties entre les membres du groupe. Voici un aperçu du tableau Trello avant la fin imminente du projet :</p>
	<img class='img-center' alt='Tableau Trello - première partie' src='img/trello1.png'>
	<br><br>
	<img class='img-center' alt='Tableau Trello - deuxième partie' src='img/trello2.png'>
	<div class='margin'></div>
	<h3>Témoignages individuels</h3>
	<h4>RAMOUNET Elie</h4>
	<p>Je me suis concentré sur la partie base de données / récupération des capteurs / site web du projet, et je n'ai pas touché à la partie NodeRed, 
		InfluxDB et Grafana. J'ai aussi été chef du projet, c'est pourquoi je suis à l'origine de la plupart des commits sur le dépôt Github. J'ai pensé
		et créé la base de données de la SAE sur MySQL en collaboration avec les autres membres, j'ai codé le script de récupération des données et
		façonné le site dynamique avec l'aide de Téo FROMENT. J'ai aussi recueilli les témoignages individuels et rédigé en partie le degré de satisfaction
		final de l'équipe.
	</p>
	<h5>Problèmes rencontrés, solutions proposées :</h5>
	<p>Je n'ai pas rencontré de problèmes majeurs durant la création de la base de données, ni celle du script en Python de récupération des données des capteurs
		et d'envoi vers la base de données. Néanmoins, la partie PHP du site a parfois été délicate et certaines erreurs nous ont fait perdre un certain temps,
		notamment les erreurs liées non pas à un problème visible dans le code mais un problème de résultats faux ou incohérents de la part du code. Après de nombreuses
		sessions de débogage, nous sommes parvenus à faire fonctionner toutes les pages. La mise en forme du site n'a pas été dur avec la maquette préalable que l'on
		avait établie.
	</p>
	<h5>Conclusion :</h5>
	<p>En somme, le script Python récupère toutes les 10 minutes les données des capteurs, les envoie dans la base de données, qui est exploitée par PHP pour 
		les afficher dans une page accessible aux utilisateurs. L'administrateur a la possibilité de supprimer et d'ajouter des capteurs et des bâtiments, les 
		gestionnaires du bâtiment peuvent voir l'intégralité des données des capteurs du bâtiment en question, tandis que les visiteurs ne peuvent accéder qu'à 
		la dernière donnée de chacun des capteurs. Le projet a été mené à bien, et j'ai accompli toutes mes tâches. Le site est fonctionnel, le script également, 
		et la base de données a été optimisée. Je suis très satisfait du rendu final de mon travail et de la collaboration avec mes camarades dans l'avancement du
		projet.
	</p>
	<h4>FROMENT Téo</h4>
	<p>De mon côté, j'ai réalisé la partie sur l'affichage et la mise en forme des données, mais j'ai également
		participé à l'avancement de la page d'accueil et de la page de gestion de projet en collaboration avec
		Elie RAMOUNET. Je n'ai pas non plus touché à toute la partie NodeRed, InfluxDB et Grafana.</p>
	<p>Je suis également à l'origine des aspects techniques du site web, tels que la réalisation du diagramme
		de Gantt prévisionnel et final. J'ai également façonné le Trello de sorte que mon équipe puisse
		ajouter et valider leurs étapes au fur et à mesure des avancées.</p>
	<h5>Problèmes rencontrés, solutions proposées :</h5>
	<p>La partie sur le site web dynamique a été pour moi une légère source de difficultés, car c'était mon
	premier site réalisé à partir de zéro. Cependant, grâce à mes associés, des travaux pratiques en R209
	(Initiation au développement Web), mais aussi à l'aide de documentations sur des forums tels que
	Stack Overflow, j'ai pu surmonter ces difficultés.</p>
	<p>Après de nombreux essais et beaucoup de temps passé sur le script récupérant les données toutes
		les 10 minutes, nous avons finalement réussi à obtenir un site dynamique fonctionnel.</p>
	<h5>Conclusion :</h5>
	<p>En outre, ce projet nous a permis de récupérer correctement les données de différents capteurs dans
		différentes salles tout en les stockant dans une base de données pour ensuite les traiter sur un site
		web dynamique. Je suis globalement satisfait de ma partie, même si elle m'a demandé beaucoup de
		temps étant donné que mes bases étaient fragiles. Cependant, j'ai fortement apprécié l'entraide au
		sein de mon groupe, ainsi que la consolidation de mes connaissances. C'est pourquoi je suis très
		satisfait de mon implication et du rendu final de mon travail, ainsi que de celui de mes camarades,
		pour mener à bien notre projet.</p>
	<h4>JULOU Loïc</h4>
	<p>Pour ma part, je me suis concentré sur la partie des dockers de cette SAE - j'ai donc dû configurer la VM et créer les trois dockers. Les dockers 
		étaient nécessaires pour implémenter NodeRed, influxDB et Grafana. Le docker NodeRed a permis de récupérer les données des capteurs par le biais 
		de MQTT, de créer un dashboard et cela graphiquement avec un système de nodes. Influxdb est la base de données dans laquelle Grafana va pouvoir 
		récupérer les données des capteurs afin de faire des graphiques à partir de celles-ci.</p>
	<h5>Problèmes rencontrés, solutions proposées :</h5>
	<p>Je n’ai pas vraiment eu de problèmes gênants, si ce n’est la syntaxe pour récupérer les données dans le payload mais en me documentant un peu la 
		solution est vite venue. NodeRed et les deux autres dockers étaient assez intuitifs, et la prise en main de NodeRed a été facile après les TPs de 
		familiarisation avec cet environnement.</p>
	<h5>Conclusion :</h5>
	<p>Pour conclure, nous devions récupérer les données de minimum 4 capteurs dans 2 bâtiments différents de l’IUT avec NodeRed via MQTT, les 
		traiter et produire deux dashboard finaux. Le premier dashboard est directement fait dans NodeRed via un module et le deuxième doit passer par 
		une base de données créée grâce à influxDB et remplie grâce à des options dans NodeRed, puis utilisée par Grafana pour générer des graphiques. 
		Tous ces points étant réalisés, mon degré de satisfaction pour cette partie de la SAE est maximale.</p>
	<div class='margin'></div>
	<h3>Degré de satisfaction</h3>
	<p>Nous sommes très satisfaits d'avoir réalisé l'ensemble du projet à temps et en respectant le cahier des charges. Nous avons rencontré quelques
		problèmes mais nous en sommes venus à bout après beaucoup de recherches et de tests en local. Le partage de connaissances de chaque membre du groupe
		a permis à chacun de comprendre la globalité du projet, plutôt que de retenir seulement sa partie individuelle.</p>
	<p>Le rendu final est fonctionnel (du moins lors des tests), esthétique et l'utilisation de Github pour centraliser l'arborescence du projet et
		 faciliter sa mise à jour nous a aidé. Nous nous sommes aussi appuyés sur Google Drive pour faire une sauvegarde du projet.</p>
	<p>Ce projet nous a permis d'appliquer nos connaissances informatiques en respectant un cahier des charges réaliste. La récupération des données 
		de capteurs, la création de la base de données et la mise en place d'un site dynamique nous ont permis d'avoir un apprentissage pratique pour 
		mieux assimiler les technologies impliquées et monter en compétence dans la partie informatique de la formation. La partie réalisée avec Node-RED,
		 InfluxDB et Grafana nous a fait découvrir une manière complètement différente de réaliser ce projet, beaucoup plus rapide, graphique et moderne.</p>
</main>
<?php

// Generate the footer and close the <body> and <html> tags.
html_footer();

?>