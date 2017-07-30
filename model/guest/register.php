<?php

	if($_POST['REGISTER'])
	{
	
		$Name = $Core->ClearText($_POST['NAME']);
		$Mail = $Core->ClearText($_POST['MAIL']);
		$Pass = $Core->ClearText($_POST['PASS']);
	
		if($Name == '' || $Mail == '' || $Pass == '')
		{
		
			$View->Load("info");
			$View->Add("title", "Błąd :: Puste pola");
			$View->Add("header", "Błąd! Puste pola!");
			$View->Add("info", "Pola formularza nie mogą być puste!");
			$View->Add("back", "index.php?pages=register");
			$View->Out();
		
		}
		
		else
		{
		
			if(!filter_var($Mail, FILTER_VALIDATE_EMAIL))
			{
			
				$View->Load("info");
				$View->Add("title", "Błąd :: Błędny e-mail");
				$View->Add("header", "Błąd! Błędny e-mail!");
				$View->Add("info", "Podałeś błędny adres e-mail!");
				$View->Add("back", "index.php?pages=register");
				$View->Out();
		
			}
			
			else
			{
			
				$Query = $MySQL->prepare("SELECT * FROM `users` WHERE `mail`=:one");
				
				$Query->bindValue(":one", $Mail, PDO::PARAM_STR);
				
				$Query->execute();
				
				if($Query->rowCount() > 0)
				{
				
					$View->Load("info");
					$View->Add("title", "Błąd :: Zajęty adres e-mail");
					$View->Add("header", "Błąd! Zajęty adres e-mail!");
					$View->Add("info", "Taki adres e-mail już istnieje w naszej bazie danych!");
					$View->Add("back", "index.php?pages=register");
					$View->Out();
				
				}
				
				else
				{
				
					$Query = $MySQL->prepare("SELECT * FROM `users` WHERE `login`=:one");
				
					$Query->bindValue(":one", $Name, PDO::PARAM_STR);
				
					$Query->execute();
				
					if($Query->rowCount() > 0)
					{
					
						$View->Load("info");
						$View->Add("title", "Błąd :: Zajęty login");
						$View->Add("header", "Błąd! Zajęty login!");
						$View->Add("info", "Taki login już istnieje w naszej bazie danych!");
						$View->Add("back", "index.php?pages=register");
						$View->Out();
				
					}
				
					else
					{
					
						$Query = $MySQL->prepare("INSERT INTO `users` VALUES(NULL, :one, :two, '0', '0', :three, '000000000', '0')");
						
						$Query->bindValue(":one", $Name, PDO::PARAM_STR);
						$Query->bindValue(":two", sha1(md5($Pass)), PDO::PARAM_STR);
						$Query->bindValue(":three", $Mail, PDO::PARAM_STR);
						
						$Query->execute();
						
						$View->Load("info");
						$View->Add("title", "Zarejestrowano");
						$View->Add("header", "Zostałeś poprawnie zarejestrowany!");
						$View->Add("info", "Rejestracja przebiegła pomyślnie!<br>Życzymy miłych zakupów!");
						$View->Add("back", "index.php?pages=login");
						$View->Out();
				
					}
				
				}
			
			}
			
		}
		
	}
	
	else
	{
		
		$Info .= '<form method="post" action="index.php?pages=register">
		
			<input type="hidden" name="REGISTER" value="true">
			
			<br><input type="text" name="NAME" placeholder="Login"><br>
			<br><input type="password" name="PASS" placeholder="Hasło"><br>
			<br><input type="text" name="MAIL" placeholder="E-mail"><br>
			
			<br><button type="submit" class="przycisk">Zarejestruj <i class="fa fa-chevron-circle-right"></i> </button>
		
		</form>';
	
		$View->Load("home");
		$View->Add("title", 'Rejestracja');
		$View->Add("header", 'Rejestracja');
		$View->Add("info", $Info);
		$View->Out();
	
	}
	
?>