<?php

	session_start();

	require('inc/functions.php');

	session_destroy();

	redirect('index.php');

?>