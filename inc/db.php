<?php

// $db = mysqli_connect("localhost", "john", "john123", "sae23") or die("Connexion à la base de données impossible.");
$db = mysqli_connect("localhost", "root", "", "sae23") or die("Connexion à la base de données impossible.");

mysqli_query($db, "SET NAMES 'utf8'");

?>