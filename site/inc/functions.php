<?php

	function check_status($status) {
		// $status is a string
		// return true if the value associated to the 'status' key of the $_SESSION array equals the argument $status
		// e.g. check_status('admin') will check if $_SESSION['status'] is equal to 'admin' and return a boolean
		if ($_SESSION['status'] === $status) {
			return true;
		}
		return false;
	}

	function redirect($location) {
		// $location is the new location (e.g. index.php)
		// this function is used to redirect the user to a new page
		// die() is used to prevent users from not following the redirection
		header("Location: $location");
		die();
	}

	function html_header($title, $description) {
		// $title and $description are strings representing the title of the page and the description metadata
		// Generate an HTML standard header with the indicated title and description
		echo "<!DOCTYPE html>
		<html>
		<head>
			<meta charset='utf-8'>
			<meta name='viewport' content='width=device-width, initial-scale=1'>
			<meta name='description' content=\"$description\">
			<meta name='author' content='RAMOUNET Elie, JULOU Loïc, FROMENT Téo'>
			<link type='text/css' rel='stylesheet' href='css/style.css'>
			<title>$title</title>
		</head>
		<body>";
	}

	function html_nav($buildings) {
		// $buildings is an array containing arrays in the form [building_id, building_name]
		// This funcion generates a dynamic navigation bar
		$nav = '<nav>
		<img alt="Logo d\'IoT" src="./img/logo.png" class="logo">';

		// loops through the buildings and create the necessary links to reach them
		foreach($buildings as $building)
			$nav = $nav . '<a href="sensors.php?b=' . $building[0] . '">Bâtiment ' . $building[1] . '</a>';

		$nav = $nav . '<a href="admin.php">Administration</a>';

		// if the user is logged in, the session variable 'connected' must be equal to 1
		// therefore we add a logout link inside the nav bar to allow the user to log out
		if ($_SESSION['connected'] === 1) {
			$nav = $nav . '<a href="logout.php">Déconnexion</a>';
		}
		echo $nav . '</nav>';
	}

	function html_footer() {
		// This function simply generates a static footer containing a link towards the website legal notice,
		// the authors of the website, a project management page and the copyright
		echo "<footer>
		<p><a href='project.php'>Gestion de projet</a>&nbsp; | &nbsp;<a href='legal.php'>Mentions légales</a>
			<p>&copy; 2023 RAMOUNET Elie, JULOU Loïc, FROMENT Téo</p>
			</footer>
		</body>
		</html>";
	}


?>