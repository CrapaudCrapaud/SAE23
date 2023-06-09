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
	<p>Voici le diagramme de Gantt finalement réalisé :</p>
	<div class='margin'></div>
	<h3>Tableau Trello</h3>
	<p>Nous avons utilisé l'outil Trello pour suivre l'avancement de la progression des différentes tâches réparties entre les groupes.</p>
	<div class='margin'></div>
	<h3>Témoignages individuels</h3>
	<h4>RAMOUNET Elie</h4>
	<p></p>
	<h4>FROMENT Téo</h4>
	<p></p>
	<h4>JULOU Loïc</h4>
	<p></p>
	<div class='margin'></div>
	<h3>Degré de satisfaction</h3>
	<p></p>
</main>
<?php

// Generate the footer and close the <body> and <html> tags.
html_footer();

?>