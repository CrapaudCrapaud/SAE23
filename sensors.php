<?php

  session_start();

	require('inc/db.php');
	require('inc/functions.php');

  $buildings_query = mysqli_query($db, 'SELECT id_bat, nom_bat FROM Batiment');
	$buildings = mysqli_fetch_all($buildings_query);
  $building_ids = array();

  for ($i = 0; $i < count($buildings); $i ++) {
    $building_ids[] = $buildings[$i][0];
  }

  html_header(
    'SAE 23 - Affichage des données',
    'Affichage des données de capteurs d\'un bâtiment'
  );

  html_nav($buildings);

  if (isset($_GET['b']) && !empty($_GET['b']) && in_array($_GET['b'], $building_ids))
    $_SESSION['b'] = $_GET['b'];
  else
    $_SESSION['b'] = 1;

?>
<p>
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

      $data = mysqli_query(
        $db,
        'SELECT Mesure.id_capt, Capteur.nom_capt, date_mes, horaire_mes, valeur_mes
        FROM Mesure
        INNER JOIN Capteur
        ON Mesure.id_capt = Capteur.id_capt
        WHERE Capteur.id_bat = ' . $_SESSION['b'] . ' GROUP BY Mesure.id_capt'
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
    ?>
    </table>
  </div>
</main>
<?php

  html_footer();

?>