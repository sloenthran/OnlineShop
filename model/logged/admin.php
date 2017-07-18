<?php

	if($Core->CheckAdmin())
	{
	
		$View->Load("admin");
		$View->Add('title', 'Strona główna');
		$View->Add('header', 'Panel administratora');
		$View->Add("info", '');
		$View->Out();
	
	}
	
	else
	{
		
		$View->Load("info");
		$View->Add('title', 'Błąd :: Brak uprawnień');
		$View->Add('header', 'Błąd! Brak uprawnień!');
		$View->Add('info', 'Nie posiadasz uprawnień administracyjnych!');
		$View->Add('back', 'index.php?pages=home');
		$View->Out();
	
	}

?>