<?php

	if($Core->CheckAdmin())
	{
	
		$View->Load("admin");
		$View->Add('title', 'Strona główna');
		$View->Add('header', 'Panel administratora');
		$View->Add("info", '<p align="left">Uwaga!<br><br>
		Nie zapomnij dodać cyklicznego uruchamiania adresu (tak zwany crontab - polecam lynx-a oraz uruchamianie co 1 minutę):<br>
		* http://TwojaDomena.pl/index.php?pages=crontab - w przypadku posiadania sklepu w głównej domenie<br>
		* http://TwojaDomena.pl/TwojKatalog/index.php?pages=crontab - w przypadku posiadania sklepu w osobnym katalogu<br>
		* http://TwojaSubDomena.TwojaDomena.pl/index.php?pages=crontab - w przypadku posiadania sklepu na subdomenie<br><br>
		
		Oczywiście odpowiednio podmieniasz słowa TwojaDomena.pl i TwojKatalog na swoje...</p>');
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