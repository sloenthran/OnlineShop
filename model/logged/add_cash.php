<?php

	$ID = $Core->ClearText($_GET['id']);

	if($ID == '')
	{
		
		$Query = $MySQL->query("SELECT * FROM `price`");
		
		while($Fetch = $Query->fetch())
		{
			
			$Info .= '<a href="index.php?pages=add_cash&id='.$Fetch['id'].'"><button class="przycisk">'.$Fetch['value'].' wPLN</button></a><br>';
			
		}
		
		$View->Load("logged_home");
		$View->Add('title', 'Doładuj portfel');
		$View->Add('header', 'Wybierz kwotę');
		$View->Add('info', $Info);
		$View->Out();
		
	}
	
	else
	{
		
		if($_POST['ADD'])
		{
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='pay'");
		
			$Fetch = $Query->fetch();
			
			$Pay = new $Fetch['value']();

			$SMS = $Core->ClearText($_POST['SMS']);
			
			$Query = $MySQL->prepare("SELECT * FROM `price` WHERE `id`=:one");
			$Query->bindParam(":one", $ID, PDO::PARAM_INT);
			$Query->execute();
			
			$Fetch = $Query->fetch();
			
			if($Pay->CheckSMS($SMS, $Fetch['number']))
			{
				
				$Query = $MySQL->prepare("UPDATE `users` SET `money`=`money`+:one WHERE `id`=:two");
				
				$Query->bindValue(":one", $Fetch['value'], PDO::PARAM_INT);
				$Query->bindValue(":two", $_SESSION['ID'], PDO::PARAM_INT);
				
				$Query->execute();
				
				$View->Load("info");
				$View->Add('title', 'Doładowano');
				$View->Add('header', 'Doładowano!');
				$View->Add('info', 'Konto zostało doładowane kwotą '.$Fetch['value'].' wPLN');
				$View->Add('back', 'index.php?pages=add_cash&id='.$ID.'');
				$View->Out();
				
			}
			
			else
			{
				
				$View->Load("info");
				$View->Add('title', 'Błąd :: Błędny kod SMS');
				$View->Add('header', 'Błędny kod SMS!');
				$View->Add('info', 'Podany przez Ciebie kod SMS jest błędny!');
				$View->Add('back', 'index.php?pages=add_cash&id='.$ID.'');
				$View->Out();
				
			}
			
		}
		
		else
		{
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='text_sms'");
		
			$Fetch = $Query->fetch();
			$SMS = $Fetch['value'];
			
			$Query = $MySQL->prepare("SELECT * FROM `price` WHERE `id`=:one");
			$Query->bindParam(":one", $ID, PDO::PARAM_INT);
			$Query->execute();
			
			$Fetch = $Query->fetch();
			
			$Info = 'Aby sfinalizować zakup wyślij SMS-a o treści <b>'.$SMS.'</b> na numer <b>'.$Fetch['number'].'</b>.<br>
			Otrzymany kod SMS wpisz w pole poniżej.<br>
			Całkowity koszt SMS-a wynosi <b>'.$Fetch['vat'].' PLN</b>!
			
			<form method="post" action="index.php?pages=add_cash&id='.$ID.'">
		
				<input type="hidden" name="ADD" value="true">
			
				<br><input type="text" name="SMS" placeholder="Kod SMS" required><br>
			
				<br><button type="submit" class="przycisk">Doładuj! <i class="fa fa-chevron-circle-right"></i> </button>
		
			</form>
	
			<br><br>
		
			<img src="https://chart.apis.google.com/chart?cht=qr&chs=100x100&choe=UTF-8&chl=smsto:'.$Fetch['number'].':'.$SMS.'">';
			
			$View->Load("logged_home");
			$View->Add('title', 'Doładuj portfel');
			$View->Add('header', 'Doładowanie portfela o '.$Fetch['value'].' wPLN');
			$View->Add('info', $Info);
			$View->Out();
			
		}
		
	}

?>