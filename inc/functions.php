<?php

	function check_status($status) {
		if ($_SESSION['status'] === $status) {
			return true;
		}
		return false;
	}

	function redirect($location) {
		header("Location: $location");
		die();
	}

	function html_header($title, $description) {
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
		$nav = '<nav class="flex">
		<img alt="Logo d\'IoT" src="./img/logo.png" class="logo">';

		foreach($buildings as $building)
			$nav = $nav . '<a href="sensors.php?b=' . $building[0] . '">Bâtiment ' . $building[1] . '</a>';

		$nav = $nav . '<a href="admin.php">Administration</a>';

		if ($_SESSION['connected'] === 1) {
			$nav = $nav . '<a href="logout.php">Déconnexion</a>';
		}
		echo $nav . '</nav>';
	}

	function html_footer() {
		echo "<footer>
		<p><a href='projet.php'>Gestion de projet</a>&nbsp; | &nbsp;<a href='legal.php'>Mentions légales</a>
			<p>&copy; 2023 RAMOUENT Elie, FROMENT Téo, JULOU Loïc</p>
			</footer>
		</body>
		</html>";
	}


?>