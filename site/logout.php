<?php
  
	// logout.php
  // Logs out the user

	// start new or resume existing session
	session_start();

	// imports the redirect function
	require('inc/functions.php');

	// destroy the session and redirect to index.php
	session_destroy();

	redirect('index.php');

?>