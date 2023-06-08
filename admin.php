<?php

  session_start();

	require('inc/db.php');
	require('inc/functions.php');

  $buildings_query = mysqli_query($db, 'SELECT id_bat, nom_bat FROM Batiment');
	$buildings = mysqli_fetch_all($buildings_query);

  html_header(
    'SAE 23 - Administration',
    'Page d\'administration du site de la SAE 23'
  );

	// Handling the form before the nav because if the user logs in, the logout button must appear in the navigation bar
	if (isset($_POST['username']) && isset($_POST['password']))
	{
		$credentials_query = mysqli_query($db, 'SELECT login, password FROM Administration');
		$credentials = mysqli_fetch_assoc($credentials_query);

		if ($_POST['username'] === $credentials['login'] && hash('sha256', $_POST['password']) === $credentials['password'])
		{
			$_SESSION['status'] = 'admin';
			$_SESSION['connected'] = 1;
		} else {
			$_SESSION['login-error'] = 1;
		}
	}
	
  html_nav($buildings);

?>
<p><a href="index.php" title="Retour à l'accueil">Accueil</a> &nbsp;>>&nbsp; <a href="#" title="Administration">Administration</a></p>
<main>

		<?php

		if (check_status('admin')) {

			if (isset($_POST['action']))
			{
				switch($_POST['action']) {
					case 'delete-building':
						if (isset($_POST['building']) && !empty($_POST['building']) && preg_match('/^[0-9]{1,}$/', $_POST['building']))
						{
							$query = mysqli_query($db, 'DELETE FROM Batiment WHERE id_bat = ' . $_POST['building']);
							echo '<p class="success">Le bâtiment a bien été supprimé !</p>';
						}
						else
							echo '<p class="error">La suppression n\'a pas pu être effectuée</p>';
						break;
					case 'delete-sensor':
						if (isset($_POST['sensor']) && !empty($_POST['sensor']) && preg_match('/^[0-9]{1,}$/', $_POST['sensor']))
						{
							$query = mysqli_query($db, 'DELETE FROM Capteur WHERE id_capt = ' . $_POST['sensor']);
							echo '<p class="success">Le capteur a bien été supprimé !</p>';
						}
						else
							echo '<p class="error">La suppression n\'a pas pu être effectuée</p>';
						break;
					case 'add-building':
						if (isset($_POST['name']) && isset($_POST['username']) && isset($_POST['password']))
						{
							$name = mysqli_real_escape_string($db, $_POST['name']);
							$username = mysqli_real_escape_string($db, $_POST['username']);
							$password = hash('sha256', $_POST['password']);
							$query = mysqli_query($db, "INSERT INTO Batiment (nom_bat, login_gest, password_gest) VALUES ('$name', '$username', '$password')");
							echo '<p class="success">Le bâtiment a bien été ajouté !</p>';
						} else 
							echo '<p class="error">L\'opération n\'a pas pu être effectuée</p>';
						break;
					case 'add-sensor':
						if (isset($_POST['building']) && preg_match('/^[0-9]{1,}$/', $_POST['building']) && isset($_POST['name']) && isset($_POST['type']))
						{
							$id_bat = $_POST['building'];
							$name = mysqli_real_escape_string($db, $_POST['name']);
							$type = mysqli_real_escape_string($db, $_POST['type']);
							$query = mysqli_query($db, "INSERT INTO Capteur (id_bat, nom_capt, type_capt) VALUES ('$id_bat', '$name', '$type')");
							echo '<p class="success">Le capteur a bien été ajouté !</p>';
						} else 
							echo '<p class="error">L\'opération n\'a pas pu être effectuée</p>';
						break;
					default:
						echo "<p class='error'>L'action demandée n'est pas prise en compte.</p>";
				}
			}

			?>
			<div class='margin'></div>
			<h3 class='form-title'>Suppression d'un bâtiment :</h3>
			<form action='admin.php' method='POST' class='delete-forms'>
				<input type='hidden' name='action' value='delete-building'>
				<label for='building'>Bâtiment à supprimer :</label>
				<select id='building' name='building'>
			<?php
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
				$buildings_query = mysqli_query($db, 'SELECT id_bat, nom_bat FROM Batiment');
				
				while ($building = mysqli_fetch_assoc($buildings_query))
					echo '<option value="' . $building['id_bat'] . '">Bâtiment ' . $building['nom_bat'] . '</option>';
			?>
				</select>
				<label for='name'>Nom du capteur (format '[salle]-[nom]'):</label>
				<input type='text' name='name' id='name' required placeholder='Nom du capteur' maxlength='25'>
				<label for='type'>Type du capteur :</label>
				<input type='text' name='type' id='type' required placeholder="Type du capteur" maxlength='25'>
				<button type="submit" title="Ajouter le capteur">Ajouter</button>
			</form>
<?php




		} else {

		?>

		<h2 class='title'>Connexion</h2>
		<?php

			if (isset($_SESSION['login-error']))
			{
				echo '<p class="error">Nom d\'utilisateur ou mot de passe incorrects</p>';
				unset($_SESSION['login-error']);
			}

		?>
		<form action="admin.php" method="POST">
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

const forms = document.querySelectorAll('.delete-forms');
for (let i = 0, c = forms.length; i < c; i++)
{
	forms[i].addEventListener('submit', e => {
		e.preventDefault();
		if (confirm('Êtez-vous sûr ?')) {
			e.target.submit();
		}
	});
}

</script>
<?php

html_footer();

?>