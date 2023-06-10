<?php

	// admin.php
	// Contains a form for the administrator to log in
	// Once the admin is logged in, he is provided with 4 forms to add or delete a building or a sensor

	// start new or resume existing session
  session_start();

  // retrieve useful code (connection to database and various PHP functions)
	require('inc/db.php');
	require('inc/functions.php');

  // Generate the HTML header part. First argument is the title, second one is the description meta data.
  html_header(
    'SAE 23 - Administration',
    'Page d\'administration du site de la SAE 23'
  );

	// Handling the login form before the nav is generated because if the admin logs in, the logout button must appear in the navigation bar
	if (isset($_POST['admin_form']) && isset($_POST['username']) && isset($_POST['password']))
	{
		// Retrieve the correct login details from the Administration table
		$credentials_query = mysqli_query($db, 'SELECT login, password FROM Administration');
		$credentials = mysqli_fetch_assoc($credentials_query);

		// Check them agains the provided credentials. Password is hashed with the sha256 hashing function for security purposes
		if ($_POST['username'] === $credentials['login'] && hash('sha256', $_POST['password']) === $credentials['password'])
		{
			// If the credentials are correct, the 'admin' status is set to the current session and the 'connected' session variable is set to 1
			$_SESSION['status'] = 'admin';
			$_SESSION['connected'] = 1;
		} else {
			// Otherwise, a login-error variable is created to output an error later in the script
			$_SESSION['login-error'] = 1;
		}
	}

	// Also handle the 4 forms of admin.php where the admin can add or remove a building to modify the navigation bar accordingly
	// Checking if the user who submitted the form is indeed an administrator
	if (check_status('admin')) {

		// If one of the 4 forms of admin.php has been submitted
		if (isset($_POST['action']))
		{
			// We decline the behavior of the program according to the 4 possible values of the $_POST['action'] parameter
			// 'action' is a parameter hidden inside the HTML forms which indicates the purpose of the form
			switch($_POST['action']) {

				// If the admin wants to delete a building
				case 'delete-building':
					// The ID of the building to be deleted must be valid and match the regular expression (only digits, at least 1)
					if (isset($_POST['building']) && !empty($_POST['building']) && preg_match('/^[0-9]{1,}$/', $_POST['building']))
					{
						// If a building gets deleted, all the measurements from all the sensors of this building must be deleted
						// Delete all the measurements whose sensor ID is in the list of sensor IDs of the sensors belonging to the targeted building
						$del_measures_query = mysqli_query($db, 'DELETE FROM Mesure WHERE id_capt in (
							SELECT id_capt FROM Capteur WHERE id_bat = ' . $_POST['building'] . '
						)');

						// The sensors must be deleted too
						$del_sensors_query = mysqli_query($db, 'DELETE FROM Capteur WHERE id_bat = ' . $_POST['building']);

						// delete the building identified by the above-mentioned ID
						$del_building_query = mysqli_query($db, 'DELETE FROM Batiment WHERE id_bat = ' . $_POST['building']);
						echo '<p class="success">Le bâtiment a bien été supprimé !</p>';
					}
					else
						echo '<p class="error">La suppression n\'a pas pu être effectuée</p>';
					// break statements are used so PHP does not test the other case statements once it found the right one
					break;

				// If the admin wants to delete a sensor
				case 'delete-sensor':
					// Same as with the building ID. It must be valid
					if (isset($_POST['sensor']) && !empty($_POST['sensor']) && preg_match('/^[0-9]{1,}$/', $_POST['sensor']))
					{
						// Delete the measurements of the sensor
						$del_measures_query = mysqli_query($db, 'DELETE FROM Mesure WHERE id_capt = ' . $_POST['sensor']);

						// Delete the sensor associated to the sensor ID provided by the admin
						$del_sensor_query = mysqli_query($db, 'DELETE FROM Capteur WHERE id_capt = ' . $_POST['sensor']);
						echo '<p class="success">Le capteur a bien été supprimé !</p>';
					}
					else
						echo '<p class="error">La suppression n\'a pas pu être effectuée</p>';
					break;

				// If the admin wants to add a building
				case 'add-building':
					if (isset($_POST['name']) && isset($_POST['username']) && isset($_POST['password']))
					{
						// Escape the name of the building and the username of its administrator to have clean SQL queries and avoid injections and errors
						$name = mysqli_real_escape_string($db, $_POST['name']);
						$username = mysqli_real_escape_string($db, $_POST['username']);
						// Hash the password using sha256 before inserting it into the table
						$password = hash('sha256', $_POST['password']);
						// Create the new building with the previous information
						$query = mysqli_query($db, "INSERT INTO Batiment (nom_bat, login_gest, password_gest) VALUES ('$name', '$username', '$password')");
						echo '<p class="success">Le bâtiment a bien été ajouté !</p>';
					} else 
						echo '<p class="error">L\'opération n\'a pas pu être effectuée</p>';
					break;

				// If the admin wants to add a sensor
				case 'add-sensor':
					// The building ID whose sensors belongs to must match the only-digit regular expression
					if (isset($_POST['building']) && preg_match('/^[0-9]{1,}$/', $_POST['building']) && isset($_POST['name']) && isset($_POST['type']))
					{
						$id_bat = $_POST['building'];
						// Escape the strings again
						$name = mysqli_real_escape_string($db, $_POST['name']);
						$type = mysqli_real_escape_string($db, $_POST['type']);
						// Insert the newly created sensor into the table Capteur
						$query = mysqli_query($db, "INSERT INTO Capteur (id_bat, nom_capt, type_capt) VALUES ('$id_bat', '$name', '$type')");
						echo '<p class="success">Le capteur a bien été ajouté !</p>';
					} else 
						echo '<p class="error">L\'opération n\'a pas pu être effectuée</p>';
					break;

				// In case the 'action' parameter is invalid, display an error message
				default:
					echo "<p class='error'>L'action demandée n'est pas prise en compte.</p>";
			}
		}
	}

	// fetch all the buildings from the database into an array so as to give this array
  // to the html_nav() function used to build the dynamic navigation bar
	$buildings_query = mysqli_query($db, 'SELECT id_bat, nom_bat FROM Batiment');
	$buildings = mysqli_fetch_all($buildings_query);

  // Generate the navigation bar once the session has been potentially set
  html_nav($buildings);

?>
<p><a href="index.php" title="Retour à l'accueil">Accueil</a> &nbsp;>>&nbsp; <a href="#" title="Administration">Administration</a></p>
<main>

		<?php

		// If the user is indeed an administrator
		if (check_status('admin')) {

			// Display the 4 forms responsible for the above-mentioned actions:

			?>
			<div class='margin'></div>
			<h3 class='form-title'>Suppression d'un bâtiment :</h3>
			<form action='admin.php' method='POST' class='delete-forms'>
				<input type='hidden' name='action' value='delete-building'>
				<label for='building'>Bâtiment à supprimer :</label>
				<select id='building' name='building'>
			<?php

				// Retrieve all the buildings stored in MySQL and output them in an <option> list within the <select> tag
				// The value sent to PHP will be the building id, but the user only sees its name
				$buildings_query = mysqli_query($db, 'SELECT id_bat, nom_bat FROM Batiment');
				
				while ($building = mysqli_fetch_assoc($buildings_query))
					echo '<option value="' . $building['id_bat'] . '">Bâtiment ' . $building['nom_bat'] . '</option>';
			?>
				</select>
      	<button type="submit" class='delete-button' title="Supprimer le bâtiment">Suppression</button>
			</form>
		
		
			<div class='margin'></div>
			<h3 class='form-title'>Suppression d'un capteur :</h3>
			<form action='admin.php' method='POST' class='delete-forms'>
				<input type='hidden' name='action' value='delete-sensor'>
				<label for='sensor'>Capteur à supprimer :</label>
				<select id='sensor' name='sensor'>
			<?php

				// Retrieve all the sensors stored in MySQL and output them in an <option> list within the <select> tag
				// The value sent to PHP will be the sensor id, but the user only sees its name

				$sensors_query = mysqli_query($db, 'SELECT id_capt, nom_capt FROM Capteur');
				
				while ($sensor = mysqli_fetch_assoc($sensors_query))
					echo '<option value="' . $sensor['id_capt'] . '">Capteur ' . $sensor['nom_capt'] . '</option>';
			?>
				</select>
				<button type="submit" class='delete-button' title="Supprimer le capteur">Suppression</button>
			</form>


			<div class='margin'></div>
			<h3 class='form-title'>Ajout d'un bâtiment :</h3>
			<form action='admin.php' method='POST'>
				<input type='hidden' name='action' value='add-building'>
				<label for='name'>Nom du bâtiment (1 lettre) :</label>
				<input type='text' name='name' id='name' required placeholder='Nom du bâtiment' maxlength='1'>
				<label for='username'>Nom d'utilisateur du gestionnaire :</label>
				<input type='text' name='username' id='username' required placeholder="Nom d'utilisateur" maxlength='25'>
				<label for='password'>Mot de passe du gestionnaire :</label>
				<input type='text' name='password' id='password' required placeholder="Mot de passe" >
				<button type="submit" title="Ajouter le bâtiment">Ajouter</button>
			</form>

			<div class='margin'></div>
			<h3 class='form-title'>Ajout d'un capteur :</h3>
			<form action='admin.php' method='POST'>
				<input type='hidden' name='action' value='add-sensor'>
				<label for='building'>Bâtiment du capteur :</label>
				<select id='building' name='building'>
			<?php

				// Same as before. Retrieve all the buildings stored in MySQL and output them in an <option> list within the <select> tag
				// The value sent to PHP will be the building id, but the user only sees its name
				$buildings_query = mysqli_query($db, 'SELECT id_bat, nom_bat FROM Batiment');
				
				while ($building = mysqli_fetch_assoc($buildings_query))
					echo '<option value="' . $building['id_bat'] . '">Bâtiment ' . $building['nom_bat'] . '</option>';
			?>
				</select>
				<label for='name'>Nom du capteur (format '[salle]-[type]'):</label>
				<input type='text' name='name' id='name' required placeholder='Nom du capteur' maxlength='25'>
				<label for='type'>Type du capteur :</label>
				<input type='text' name='type' id='type' required placeholder="Type du capteur" maxlength='25'>
				<button type="submit" title="Ajouter le capteur">Ajouter</button>
			</form>
<?php

		// If the user is not logged in, display the form
		} else {

		?>

		<h2 class='title'>Connexion</h2>
		<?php

			// Besides, if the user had a mistake in his login details, output an error message and unset the associated variable
			// so as not to print the error message the next time
			if (isset($_SESSION['login-error']))
			{
				echo '<p class="error">Nom d\'utilisateur ou mot de passe incorrects</p>';
				unset($_SESSION['login-error']);
			}

		?>
		<form action="admin.php" method="POST">
			<input type="hidden" name="admin_form" value="1">
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
<script>

// retrieve all deletion forms and loop through each of them
const forms = document.querySelectorAll('.delete-forms');
for (let i = 0, c = forms.length; i < c; i++)
{
	forms[i].addEventListener('submit', e => {
		// Prevent the form from being submitted and instead require the confirmation of the user before proceeding
		e.preventDefault();
		if (confirm('Êtez-vous sûr ?')) {
			// Finally submit the form if the user confirms
			e.target.submit();
		}
	});
}

</script>
<?php

// Generate the footer and close the <body> and <html> tags.
html_footer();

?>