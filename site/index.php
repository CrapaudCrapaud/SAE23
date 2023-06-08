<?php

	// index.php
	// Contains the presentation speech and the available buildings

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
    'SAE 23 - Accueil',
    'Page d\'accueil de la SAE 23'
  );

  // Generate the navigation bar
	html_nav($buildings);

?>

<header>
		<h1>SAE 23</h1>
		<small>RAMOUNET Elie, JULOU Loïc, FROMENT Téo</small>
</header>

<!-- Description of the project -->
<div class="description">
	<p>Ce site permet de consulter et de gérer les données des capteurs, les capteurs et les bâtiments impliqués la SAE 23.</p>
	<p>Les données des capteurs sont récupérées par un script Python via MQTT, envoyées dans une base de données MySQL et affichées sur ce site en PHP.</p>
	<p>Le projet associé à cette SAE est disponible sur Github, <a href='https://github.com/CrapaudCrapaud/SAE23'>ici</a>.</p>
	<p>Vous trouverez ci-dessous la liste des bâtiments et leurs capteurs respectifs :</p>		
</div>

<main>
	<?php

		// Loop through each array of buildings and echo a link towards it.
		// The URL has the form 'sensors.php?b=[BUILDING_ID]' where BUILDING_ID is $building[0].
		// The named of the building displayed within the link is the second element of the array
		foreach($buildings as $building)
		{
		?>
		<div class="box">
			<a href="./sensors.php?b=<?php echo $building[0] ?>"><h2>Bâtiment <?php echo $building[1] ?></h2></a>
			<ul>
				<?php 
				
				// For each building, the program retrieves the associated sensors and displays them in an unordered list
				$sensors_query = mysqli_query($db, 'SELECT nom_capt FROM Capteur WHERE id_bat = ' . $building[0]);

				while ($sensor = mysqli_fetch_assoc($sensors_query))
				{
					echo '<li>' . str_replace('-', ' : ', $sensor['nom_capt']) . '</li>';
				}

				?>
			</ul>
		</div>
		<?php
		}
	?>
	<div class="description">

	<!-- Skills implicated in this project -->

		<h3>Ce projet s'inscrit dans la compétences RT3 : Créer des outils et applications informatiques pour les R&T</h3>

		<ul>
			<li>En étant à l'écoute des besoins du client : réponse à un cahier des charges</li>
			<li>En documentant le travail réalisé : codes commentés + site web</li>
			<li>En utilisant les outils numériques à bon escient : environnement GNU/Linux, serveur web</li>
			<li>En choisissant les outils de développement adaptés : gestion de version via Git</li>
			<li>En intégrant les problématiques de sécurité : accès restreint au site, https forcé</li>
		</ul>

		<h3>Apprentissages critiques couverts</h3>

		<ul>
			<li>Utiliser un système informatique et ses outils</li>
			<li>Lire, exécuter, corriger et modifier un programme</li>
			<li>Traduire un algorithme dans un langage et pour un environnement donné</li>
			<li>Connaître l’architecture et les technologies d’un site web</li>
			<li>Choisir les mécanismes de gestion de données adaptés au développement de l’outil</li>
			<li>S’intégrer dans un environnement propice au développement et au travail collaboratif</li>
		</ul>

		<h3>Ressources mobilisées</h3>
		<h4>Ressources transverses</h4>

		<ul>
			<li>R210 : documentation des codes en anglais</li>
			<li>R211 : présentation orale de la maquette, rédaction technique</li>
			<li>R115 : organisation du travail</li>
		</ul>

		<h4>Ressources techniques</h4>

		<ul>
			<li>R107 & R208 : algorithmie, programmation</li>
			<li>R108 : environnement GNU/Linux, scripts Bash, commandes systèmes</li>
			<li>R109 & R209 : mise en forme d’une page web dynamique, publication sur un serveur</li>
			<li>R207 : mise en place d’une base de données</li>
			<li>R202 : mise en place de conteneurs Docker</li>
		</ul>
	</div>
</main>
<?php

// Generate the footer and close the <body> and <html> tags.
html_footer();

?>