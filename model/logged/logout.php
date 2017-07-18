<?php

	$_SESSION['LOGGED'] = false;
	$_SESSION['ID'] = false;
	$_SESSION['RANKS'] = false;
	$_SESSION['ID_TIME'] = false;
	
	header("Location: index.php?pages=home");

?>