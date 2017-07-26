<?php

	if($_SESSION['BAD_TIME'] > time())
	{
	
		$Data = date("d.m.Y H:i:s", $_SESSION['BAD_TIME']);
		
		$View->Load("info");
		$View->Add('title', 'Błąd :: Ban');
		$View->Add('header', 'Błąd! Masz bana!');
		$View->Add('info', 'Z powodu błędnego logowania możliwość Twojego logowania została zablokowana!<br><br>Twój ban wygaśnie: '.$Data.'');
		$View->Add('back', 'index.php?pages=home');
		$View->Out();
		
	}
	
	else
	{

		if($_POST['LOGIN'])
		{
	
			$User = $Core->ClearText($_POST['USER']);
			$Pass = $Core->ClearText($_POST['PASS']);
		
			$Query = $MySQL->prepare('SELECT * FROM `users` WHERE `login`=:one AND `pass`=:two LIMIT 1');
			
			$Query->bindValue(':one', $User, PDO::PARAM_STR);
			$Query->bindValue(':two', sha1(md5($Pass)), PDO::PARAM_STR);
		
			$Query->execute();
		
			if($Query->rowCount() > 0)
			{
			
				$Fetch = $Query->fetch();
		
				$_SESSION['LOGGED'] = true;
				$_SESSION['RANKS'] = $Fetch['ranks'];
				$_SESSION['ID'] = $Fetch['id'];
				$_SESSION['ID_TIME'] = time();
				
				$Core->AddLoginLogs("Zalogowano");
				
				header("Location: index.php?pages=home");
			
			}
		
			else
			{
				
				$View->Load('info');
				$View->Add('title', 'Błąd :: Błędny login lub hasło');
				$View->Add('header', 'Błędny login lub hasło!');
				$View->Add('info', 'Nie znaleziono użytkownika z takim loginem i hasłem w bazie danych!<br>Możliwość Twojego logowania została zablokowana na 15 sekund!');
				$View->Add('back', 'index.php?pages=home');
				$View->Out();
				
				$_SESSION['BAD_TIME'] = time() + 15;
				
			}
			
		}
		
		else
		{
			
			$Info .= '<p><form method="post" action="index.php?pages=login">
		
				<input type="hidden" name="LOGIN" value="true">
			
				<br><input type="text" name="USER" placeholder="Login"><br>
				<br><input type="password" name="PASS" placeholder="Hasło"><br>
			
				<br><button type="submit" class="przycisk">Zaloguj <i class="fa fa-chevron-circle-right"></i> </button>
		
			</form></p>';
			
			$View->Load("home");
			$View->Add("title", 'Logowanie');
			$View->Add("header", 'Logowanie');
			$View->Add("info", $Info);
			$View->Out();
			
		}
		
	}
	
?>