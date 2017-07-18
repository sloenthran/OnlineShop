<?php

	$View->Load("logged_home");
	$View->Add('title', 'Strona Główna');
	$View->Add('header', 'Witaj ponownie '.$Core->GetName().'!');
	$View->Add('info', 'Aktualnie w portfelu posiadasz '.$Core->GetMoney().' wPLN!');
	$View->Out();

?>