<?php

require('inc/db.php');

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Page d'administration du site de la SAE 23">
	<meta name="author" content="RAMOUNET Elie, JULOU Loïc, FROMENT Téo">
	<link type="text/css" rel="stylesheet" href="css/style.css">
	<title>SAE 23 - Administration</title>
</head>
<body>
	<nav class="flex">
		<img alt="Logo d'IoT" src="./img/logo.png" class="logo">
		<a href="./building.php?b=1" title="Données du bâtiment 1">Bâtiment 1</a>
		<a href="./building.php?b=2" title="Données du bâtiment 2">Bâtiment 2</a>
		<a href="./admin.php" title="Administration du site">Administration</a>
	</nav>
  <p><a href="./index.html" title="Retour à l'accueil">Accueil</a> &nbsp;>>&nbsp; <a href="#" title="Administration">Administration</a></p>
	<main>
    <h2>Connexion</h2>
    <form action="admin.php" method="POST">
      <label for="username">Nom d'utilisateur :</label>
      <input type="text" id="username" name="username" maxlength="255" minlength="1" placeholder="nom d'utilisateur" required autofocus>

      <label for="password">Mot de passe :</label>
      <input type="password" id="password" name="password" minlength="1" placeholder="mot de passe" required>

      <button type="submit" title="Soumettre le formulaire">Connexion</button>
    </form>
	</main>
</body>
</html>