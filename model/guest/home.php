<?php
	
	$View->Load("home");
	$View->Add("title", 'Strona główna');
	$View->Add("header", 'Witaj w sklepie!');
	$View->Add("info", '<a href="https://auth.bitbay.net/ref/sloenthran/6/pl-PL">
		<img src="https://ad.bitbay.net/en/bb-900x120.png" alt="Baner 900x120">
	</a>');
	$View->Out();

?>