<?php

	if($Core->CheckAdmin())
	{
	
		if($_POST['ADD'])
		{
			
			$Name = $Core->ClearText($_POST['NAME']);
			$IP = $Core->ClearText($_POST['IP']);
			$Port = $Core->ClearText($_POST['PORT']);
			
			if($Name == '' || $IP == '' || $Port == '')
			{
		
				$View->Load("info");
				$View->Add("title", "Błąd :: Puste pola");
				$View->Add("header", "Błąd! Puste pola!");
				$View->Add("info", "Pola formularza nie mogą być puste!");
				$View->Add("back", "index.php?pages=admin_servers");
				$View->Out();
		
			}
	
			else
			{
		
				$Query = $MySQL->prepare("INSERT INTO `servers` VALUES('', :one, :two, :three)");
				
				$Query->bindValue(":one", $Name, PDO::PARAM_STR);
				$Query->bindValue(":two", $IP, PDO::PARAM_STR);
				$Query->bindValue(":three", $Port, PDO::PARAM_INT);
				
				$Query->execute();
				
				$View->Load("info");
				$View->Add("title", "Serwer dodany");
				$View->Add("header", "Serwer dodany!");
				$View->Add("info", "Serwer został poprawnie dodany!");
				$View->Add("back", "index.php?pages=admin_servers");
				$View->Out();
			
			}
			
		}
		
		else
		{
			
			$Info = '<form method="post" action="index.php?pages=admin_servers">
		
				<input type="hidden" name="ADD" value="true">
			
				<br><input type="text" name="NAME" placeholder="Nazwa serwera"><br>
				<br><input type="text" name="IP" placeholder="IP Serwera"><br>
				<br><input type="text" name="PORT" placeholder="Port serwera"><br>
			
				<br><button type="submit" class="przycisk">Dodaj serwer <i class="fa fa-chevron-circle-right"></i> </button>
		
			</form>';
			
			$View->Load("admin_servers");
			$View->Add('title', 'Dodaj serwer');
			$View->Add('header', 'Dodaj serwer');
			$View->Add('info', $Info);
			$View->Out();
	
		}
	
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