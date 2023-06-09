<?php

// Connection to MySQL with username john, password john123 and the database is sae23
$db = mysqli_connect("localhost", "john", "john123", "sae23") or die("Connexion à la base de données impossible.");

// We set the charset to UTF-8
mysqli_query($db, "SET NAMES 'utf8'");

?>