<?php

	session_start();

	require('inc/db.php');
	require('inc/functions.php');

	$buildings_query = mysqli_query($db, 'SELECT id_bat, nom_bat FROM Batiment');
	$buildings = mysqli_fetch_all($buildings_query);

	html_header(
    'SAE 23 - Accueil',
    'Page d\'accueil de la SAE 23'
  );

	html_nav($buildings);

?>

<header>
		<h1>SAE 23</h1>
		<small>RAMOUNET Elie, JULOU Loïc, FROMENT Téo</small>
		<p class="description">Description de la SAE 23 ici</p>
</header>
<main>
	<?php

		foreach($buildings as $building)
		{
		?>
		<div class="box">
			<a href="./sensors.php?b=<?php echo $building[0] ?>"><h2>Bâtiment <?php echo $building[1] ?></h2></a>
			<ul>
				<?php 
				
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
</main>
<?php

html_footer();

?>