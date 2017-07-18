<?php

	if($_POST['CHANGE'])
	{
	
		$OldPass = $Core->ClearText($_POST['OLDPASS']);
		$NewPass = $Core->ClearText($_POST['NEWPASS']);
		
		if($OldPass == '' || $NewPass == '')
		{
		
			$View->Load("info");
			$View->Add("title", "Błąd :: Puste pola");
			$View->Add("header", "Błąd! Puste pola!");
			$View->Add("info", "Pola formularza nie mogą być puste!");
			$View->Add("back", "index.php?pages=change_pass");
			$View->Out();
		
		}
		
		else
		{
		
			$Query = $MySQL->prepare("SELECT `pass` FROM `users` WHERE `id`=:one");
		
			$Query->bindValue(":one", $_SESSION['ID'], PDO::PARAM_INT);
		
			$Query->execute();
		
			$Fetch = $Query->fetch();
		
			if($Fetch['pass'] != sha1(md5($OldPass)))
			{
		
				$View->Load("info");
				$View->Add("title", "Błąd :: Błędne hasło");
				$View->Add("header", "Błędne hasło!");
				$View->Add("info", "Stare hasło nie zgadza się z tym zapisanym w bazie danych!");
				$View->Add("back", "index.php?pages=change_pass");
				$View->Out();
		
			}
		
			else
			{
		
				$Query = $MySQL->prepare("UPDATE `users` SET `pass`=:one WHERE `id`=:two");
			
				$Query->bindValue(":one", sha1(md5($NewPass)), PDO::PARAM_STR);
				$Query->bindValue(":two", $_SESSION['ID'], PDO::PARAM_INT);
			
				$Query->execute();
				
				$Core->AddOtherLogs('Zmieniono hasło');
			
				$View->Load("info");
				$View->Add("title", "Hasło zmienione");
				$View->Add("header", "Hasło zmienione!");
				$View->Add("info", "Hasło zostało pomyślnie zmienione!");
				$View->Add("back", "index.php?pages=change_pass");
				$View->Out();
		
			}
			
		}
	
	}
	
	else
	{
	
		$Info = '<form method="post" action="index.php?pages=change_pass">
		
			<input type="hidden" name="CHANGE" value="true">
			
			<br>Stare hasło<br><br><input type="password" name="OLDPASS"><br>
			<br>Nowe hasło<br><br><input type="password" name="NEWPASS"><br>
			
			<br><button type="submit" class="przycisk">Zmień <i class="fa fa-chevron-circle-right"></i> </button>
		
		</form>';
		
		$View->Load("logged_home");
		$View->Add('title', 'Zmiana hasła');
		$View->Add('header', 'Zmiana hasła');
		$View->Add('info', $Info);
		$View->Out();
	
	}

?>