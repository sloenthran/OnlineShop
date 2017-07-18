<?php

	$Query = $MySQL->query("SELECT `value` FROM `info` WHERE `name`='contact'");
	
	$Fetch = $Query->fetch();
	
	$View->Load("home");
	$View->Add("title", 'Kontakt');
	$View->Add("header", 'Kontakt');
	$View->Add("info", $Fetch['value']);
	$View->Out();

?>