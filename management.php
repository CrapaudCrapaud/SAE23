<?php

  session_start();

  require('inc/db.php');
  require('inc/functions.php');


  if (!isset($_SESSION['b']) or empty($_SESSION['b']))
    redirect('index.php');


  $buildings_query = mysqli_query($db, 'SELECT id_bat, nom_bat FROM Batiment');
	$buildings = mysqli_fetch_all($buildings_query);

  html_header(
    'SAE 23 - Gestion du bâtiment',
    'Affichage et gestion des données de capteurs d\'un bâtiment'
  );


  // Handling the form before the nav because if the user logs in, the logout button must appear in the navigation bar
  if (isset($_POST['username']) && isset($_POST['password']))
  {
    $credentials_query = mysqli_query($db, 'SELECT login_gest, password_gest FROM Batiment WHERE id_bat = ' . $_SESSION['b']);
    $credentials = mysqli_fetch_assoc($credentials_query);

    if ($_POST['username'] === $credentials['login_gest'] && hash('sha256', $_POST['password']) === $credentials['password_gest'])
    {
      $_SESSION['status'] = 'management-' . $_SESSION['b'];
      $_SESSION['connected'] = 1;
    } else {
      $_SESSION['login-error'] = 1;
    }
  }


  html_nav($buildings);
  
?>
<p>
  <a href="index.php" title="Retour à l'accueil">Accueil</a> &nbsp;>>&nbsp; 
  <a href="#" title="Gestion">Gestion</a>
<h2 class='title'>Gestion du bâtiment n°<?php echo $_SESSION['b'] ?></h2>
<main>
  <?php

    // if the user has the management rights of this building (management-1 for building id 1, etc.)
    // display the last 100 records of the sensor data within the desired days
    if (check_status('management-' . $_SESSION['b']))
    {

      $sensor_query = mysqli_query($db, 'SELECT id_capt FROM Capteur WHERE id_bat = ' . $_SESSION['b']);
      $sensor_ids = array();

      
      while ($sensor_id = mysqli_fetch_assoc($sensor_query))
        $sensor_ids[] = $sensor_id['id_capt'];
      
      if (isset($_POST['sensor']) && isset($_POST['start']) && isset($_POST['end']))
      {
        if (in_array($_POST['sensor'], $sensor_ids))
          $sensor_id = $_POST['sensor'];
        else
          $sensor_id = '%';
        
        $date_regexp = '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/';

        if (preg_match($date_regexp, $_POST['start']) && preg_match($date_regexp, $_POST['end']))
        {
          $start = $_POST['start'];
          $end = $_POST['end'];
        } else {
          $end = date('Y-m-d');
          $start = date('Y-m-d', strtotime($end . '-1 day'));
        }
      } else {
        $sensor_id = '%';
        $end = date('Y-m-d');
        $start = date('Y-m-d', strtotime($end . '-1 day'));
      }
    ?>

      <form method='POST' action='management.php'>
        <label for='sensor'>Capteur :</label>
        <select id='sensor' name='sensor'>
          <?php

            $sensors_query = mysqli_query($db, 'SELECT id_capt, nom_capt FROM Capteur WHERE id_bat = ' . $_SESSION['b']);

            while ($sensor = mysqli_fetch_assoc($sensors_query))
              echo '<option value="' . $sensor['id_capt'] . '">'  . $sensor['nom_capt'] . '</option>';

          ?>
        </select>
        <label for='start'>Date de départ :</label>
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

        $data = mysqli_query(
          $db,
          'SELECT Capteur.nom_capt, date_mes, horaire_mes, valeur_mes
          FROM Mesure
          INNER JOIN Capteur
          ON Mesure.id_capt = Capteur.id_capt
          WHERE Capteur.id_bat = ' . $_SESSION['b'] . 
        ' AND Mesure.id_capt LIKE "' . $sensor_id . '"' .
        ' AND Mesure.date_mes BETWEEN "' . $start . '" AND "' . $end . '"'
        );

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

  html_footer();

?>