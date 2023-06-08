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
<header>
		<h2>Gestion de projet et informations complémentaires</h2>
</header>


<main>

</main>
<?php

// Generate the footer and close the <body> and <html> tags.
html_footer();

?>