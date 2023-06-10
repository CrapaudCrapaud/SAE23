<?php

  // sensors.php
  // Shows the most recent data of each sensor of the selected building and a link to manage the building data

  // start new or resume existing session
  session_start();

  // retrieve useful code (connection to database and various PHP functions)
	require('inc/db.php');
	require('inc/functions.php');

  // fetch all the buildings from the database into an array so as to give this array
  // to the html_nav() function used to build the dynamic navigation bar
	$buildings_query = mysqli_query($db, 'SELECT id_bat, nom_bat FROM Batiment');
	$buildings = mysqli_fetch_all($buildings_query);

  // all the building IDs are stored in the $building_ids array for validation purpose
  $building_ids = array();

  // this array contains all the first elements (first column id_bat) of each array of $buildings
  for ($i = 0; $i < count($buildings); $i ++) {
    $building_ids[] = $buildings[$i][0];
  }

  // Generate the HTML header part. First argument is the title, second one is the description meta data.
  html_header(
    'SAE 23 - Affichage des données',
    'Affichage des données de capteurs d\'un bâtiment'
  );

  // Generate the navigation bar
  html_nav($buildings);

  // $_SESSION['b'] is where the selected building is stored
  // If the GET parameter 'b' exists and is within the array of building IDs retrieve above, $_SESSION['b'] is set to $_GET['b']
  // Otherwise, if it's invalid or not specified, it is set to 1 by default
  if (isset($_GET['b']) and !empty($_GET['b']) and in_array($_GET['b'], $building_ids))
    $_SESSION['b'] = $_GET['b'];
  else
    $_SESSION['b'] = 1;

?>
<p>
  <!-- Breadcrumbs to help the user navigate trhough the pages, plus a link to log in as an administrator of the buliding -->
  <a href="index.php" title="Retour à l'accueil">Accueil</a> &nbsp;>>&nbsp; 
  <a href="#" title="Données">Données</a>
  <a class="management-link" href="management.php">Gestion du bâtiment</a></p>
<h2 class='title'>Affichage des données</h2>
<main>
  <div class='table-responsive'>
    <table>
      <tr>
        <th>Salle</th><th>Type</th><th>Date</th><th>Horaire</th><th>Valeur</th>
      </tr>
    <?php

      // The given PHP code executes a SQL query to retrieve the most recent measurement for each sensor belonging 
      // to the specific building $_SESSION['b']. The MAX() function is used to select the maximum date and time values
      // for each sensor's measurements. The GROUP BY clause ensures that only one record per sensor is returned,
      // representing the most recent measurement
     
      $data = mysqli_query(
        $db,
        'SELECT Capteur.nom_capt, Mesure.id_capt, MAX(Mesure.date_mes) AS date_mes, MAX(Mesure.horaire_mes) AS horaire_mes
        FROM Mesure
        JOIN Capteur ON Mesure.id_capt = Capteur.id_capt
        WHERE Capteur.id_bat = ' . $_SESSION['b'] . '
        GROUP BY Mesure.id_capt;'
      );

      // Output all the result in the array
      while ($ligne = mysqli_fetch_assoc($data))
      {
        // The format of nom_capt is the following: [BUILDING-LETTER][ROOM]-[SENSOR-TYPE]
        // Where BUILDING-LETTER is the letter that identifies the building, ROOM is a 3-digit room and SENSOR-TYPE is the type of the sensor
        // substr($ligne['nom_capt'], 1, 3) slices the ROOM from this string
        // while substr($ligne['nom_capt'], 5) slices the SENSOR-TYPE
        ?>
        <tr>
          <td><?php echo substr($ligne['nom_capt'], 1, 3) ?></td>
          <td><?php echo substr($ligne['nom_capt'], 5) ?></td>
          <td><?php echo $ligne['date_mes'] ?></td>
          <td><?php echo $ligne['horaire_mes'] ?></td>
          <td><?php echo $ligne['valeur_mes'] ?></td>
        </tr>
        <?php
      }
    ?>
    </table>
  </div>
</main>
<?php

// Generate the footer and close the <body> and <html> tags.
html_footer();

?>