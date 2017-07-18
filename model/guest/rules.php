<?php

	$Query = $MySQL->query("SELECT `value` FROM `info` WHERE `name`='rules'");
	
	$Fetch = $Query->fetch();
	
	$View->Load("home");
	$View->Add("title", 'Regulamin');
	$View->Add("header", 'Regulamin');
	$View->Add("info", $Fetch['value']);
	$View->Out();

?>