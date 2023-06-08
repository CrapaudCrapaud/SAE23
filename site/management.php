<?php

  // management.php
  // Contains a form for the administrator of the building to log in
  // Once the local administrator is logged in, he can display the data of the building sensors
  // with a timeframe filter

	// start new or resume existing session
  session_start();

  // retrieve useful code (connection to database and various PHP functions)
  require('inc/db.php');
  require('inc/functions.php');

  // If the building is not set for the session, it means the user did not visit the sensors.php page and
  // directly accessed the management.php page thus we don't know in which building he is supposed to log in
  if (!isset($_SESSION['b']) or empty($_SESSION['b']))
    redirect('index.php');


  // fetch all the buildings from the database into an array so as to give this array
  // to the html_nav() function used to build the dynamic navigation bar
	$buildings_query = mysqli_query($db, 'SELECT id_bat, nom_bat FROM Batiment');
	$buildings = mysqli_fetch_all($buildings_query);

  // Generate the HTML header part. First argument is the title, second one is the description meta data.
  html_header(
    'SAE 23 - Gestion du bâtiment',
    'Affichage et gestion des données de capteurs d\'un bâtiment'
  );


	// Handling the login form before the nav is generated because if the admin logs in, the logout button must appear in the navigation bar
  if (isset($_POST['username']) && isset($_POST['password']))
  {
		// Retrieve the correct login details from the Batiment table associated with the building ID of the session
    $credentials_query = mysqli_query($db, 'SELECT login_gest, password_gest FROM Batiment WHERE id_bat = ' . $_SESSION['b']);
    $credentials = mysqli_fetch_assoc($credentials_query);

    // If the provided credentials match the stored credentials, the 'connected' session variable is created
    // and the 'status' of the user's session is set according to the building.
    // If he logged in with building n°3 credentials, the status will be 'management-3' and so on
    if ($_POST['username'] === $credentials['login_gest'] && hash('sha256', $_POST['password']) === $credentials['password_gest'])
    {
      $_SESSION['status'] = 'management-' . $_SESSION['b'];
      $_SESSION['connected'] = 1;
    } else {
			// Otherwise, a login-error variable is created to output an error later in the script
      $_SESSION['login-error'] = 1;
    }
  }

  // Generate the navigation bar once the session has been potentially set
  html_nav($buildings);
  
?>
<p>
  <a href="index.php" title="Retour à l'accueil">Accueil</a> &nbsp;>>&nbsp; 
  <a href="#" title="Gestion">Gestion</a>
</p>
<h2 class='title'>Gestion du bâtiment n°<?php echo $_SESSION['b'] ?></h2>
<main>
  <?php

    // if the user has the management rights of this building (management-1 for building id 1, etc.)
    // display the records of the sensor data within the desired time frame
    if (check_status('management-' . $_SESSION['b']))
    {
      // Retrieve all the ID of the sensors to validate the form
      $sensor_query = mysqli_query($db, 'SELECT id_capt FROM Capteur WHERE id_bat = ' . $_SESSION['b']);
      $sensor_ids = array();
      
      while ($sensor_id = mysqli_fetch_assoc($sensor_query))
        $sensor_ids[] = $sensor_id['id_capt'];

      // Act if the form has been submitted
      if (isset($_POST['sensor']) && isset($_POST['start']) && isset($_POST['end']))
      {
        // If the provided sensor ID is not within the array of sensors retrieved above, it is set to '%' to match all the sensors
        // as we use the wildcard operator in the SQL query (LIKE)
        if (in_array($_POST['sensor'], $sensor_ids))
          $sensor_id = $_POST['sensor'];
        else
          $sensor_id = '%';
        
        // Both dates (start and end) must match the following regular expression (4 digits, a hyphen, 2 digits, a hyphen, 2 digits):
        $date_regexp = '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/';

        if (preg_match($date_regexp, $_POST['start']) and preg_match($date_regexp, $_POST['end']))
        {
          $start = $_POST['start'];
          $end = $_POST['end'];
        } else {
          // If one of the dates doesn't match, the end date is set to today and the start date is set to yesterday
          $end = date('Y-m-d');
          $start = date('Y-m-d', strtotime($end . '-1 day'));
        }
      } else {
        // Same default values if the form hasn't been submitted
        $sensor_id = '%';
        $end = date('Y-m-d');
        $start = date('Y-m-d', strtotime($end . '-1 day'));
      }
    ?>

      <form method='POST' action='management.php'>
        <label for='sensor'>Capteur :</label>
        <select id='sensor' name='sensor'>
          <?php

            // Retrieve the sensors IDs of the current building for the local administrator to select them and access their data
            $sensors_query = mysqli_query($db, 'SELECT id_capt, nom_capt FROM Capteur WHERE id_bat = ' . $_SESSION['b']);

            while ($sensor = mysqli_fetch_assoc($sensors_query))
              echo '<option value="' . $sensor['id_capt'] . '">'  . $sensor['nom_capt'] . '</option>';

          ?>
        </select>
        <label for='start'>Date de départ :</label>
        <?php
          // $start and $end times are displayed within the <input> of type 'date' so the user sees which dates are being used
        ?>
        <input type='date' id='start' name='start' value="<?php echo $start ?>" required>
        <label for='end'>Date de fin :</label>
        <input type='date' id='end' name='end' value="<?php echo $end ?>" required>
        <button type="submit" class="light" title="Filtrer les résultats">Filtrer</button>
      </form>
      <div class='margin'></div>

    <?php
      echo "<h3>Affichage des données depuis le <b>$start</b> jusqu'au <b>$end</b> :</h3>";
    ?>

      <div class='table-responsive'>
      <table>
        <tr>
          <th>Salle</th><th>Type</th><th>Date</th><th>Horaire</th><th>Valeur</th>
        </tr>

      <?php        

        // Final query used to display all the data from the selected sensor within the time frame
        $data = mysqli_query(
          $db,
          'SELECT Capteur.nom_capt, date_mes, horaire_mes, valeur_mes
          FROM Mesure
          INNER JOIN Capteur
          ON Mesure.id_capt = Capteur.id_capt
          WHERE Capteur.id_bat = ' . $_SESSION['b'] .  // data from sensors which belong to the building...
        ' AND Mesure.id_capt LIKE "' . $sensor_id . '"' . // ... and to the right sensor within the building...
        ' AND Mesure.date_mes BETWEEN "' . $start . '" AND "' . $end . '"' // ... and whose dates are between the specified start and end dates
        );

        // Display the data in the table like in the sensors.php page
        while ($ligne = mysqli_fetch_assoc($data))
        {
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
        echo '</table></div>';
      }


    // If the user is not logged in, the form is displayed
    else {

      // Besides, if the user had a mistake in his login details, output an error message and unset the associated variable
			// so as not to print the error message the next time
			if (isset($_SESSION['login-error']))
      {
        echo '<p class="error">Nom d\'utilisateur ou mot de passe incorrects</p>';
        unset($_SESSION['login-error']);
      }
  ?>
    <form action="management.php" method="POST">
      <label for="username">Nom d'utilisateur :</label>
      <input type="text" id="username" name="username" maxlength="25" minlength="1" placeholder="nom d'utilisateur" required autofocus>
  
      <label for="password">Mot de passe :</label>
      <input type="password" id="password" name="password" minlength="1" placeholder="mot de passe" required>
  
      <button type="submit" title="Soumettre le formulaire">Connexion</button>
    </form>
  <?php
    }
  ?>
</main>
<?php

// Generate the footer and close the <body> and <html> tags.
html_footer();

?>