<?php

	$Query = $MySQL->query("SELECT `value` FROM `info` WHERE `name`='contact'");
	
	$Fetch = $Query->fetch();
	
	$View->Load("logged_home");
	$View->Add("title", 'Kontakt');
	$View->Add("header", 'Kontakt');
	$View->Add("info", $Fetch['value']);
	$View->Out();

?>