<?php

	$ID = $Core->ClearText($_GET['id']);
	$PriceID = $Core->ClearText($_GET['priceid']);
	
	if($_POST['BUY'])
	{
		
		$SID = $Core->ClearText($_POST['USER']);
		$Code = $Core->ClearText($_POST['SMS']);
	
		$Query = $MySQL->prepare("SELECT `price_id` FROM `service` WHERE `id`=:one");
		$Query->bindParam(":one", $PriceID, PDO::PARAM_INT);
		$Query->execute();
	
		$Fetch = $Query->fetch();
		$Fetch = $Fetch['price_id'];
	
		$Query = $MySQL->prepare("SELECT `number` FROM `price` WHERE `id`=:one");
		$Query->bindParam(":one", $Fetch, PDO::PARAM_INT);
		$Query->execute();
	
		$Fetch = $Query->fetch();
		$Number = $Fetch['number'];
	
		$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='pay'");
		$Fetch = $Query->fetch();
	
		$Pay = new $Fetch['value']();
	
		if($Pay->CheckSMS($Code, $Number))
		{
		
			$Query = $MySQL->prepare("SELECT `days` FROM `service` WHERE `id`=:one");
			$Query->bindValue(":one", $PriceID, PDO::PARAM_INT);
			$Query->execute();
		
			$Fetch = $Query->fetch();
		
			$Days = $Fetch['days'];
		
			$Query = $MySQL->prepare("SELECT `id` FROM `premium_cache` WHERE `nick`=:one AND `premium_id`=:two AND `server`=:three");
			$Query->bindValue(":one", $SID, PDO::PARAM_STR);
			$Query->bindValue(":two", $ID, PDO::PARAM_INT);
			$Query->bindValue(":three", $_SESSION['SERVERID'], PDO::PARAM_INT);
			$Query->execute();
		
			if($Query->rowCount() > 0)
			{
			
				$Fetch = $Query->fetch();
			
				$Days = $Days * 86400;
			
				$Query = $MySQL->prepare("UPDATE `premium_cache` SET `time`=`time`+:one WHERE `id`=:two");
				$Query->bindValue(":one", $Days, PDO::PARAM_INT);
				$Query->bindValue(":two", $Fetch['id'], PDO::PARAM_INT);
				$Query->execute();
			
				$View->Load("info");
				$View->Add('title', 'Zakup przedłużony');
				$View->Add('header', 'Twój zakup został przedłużony!');
				$View->Add('info', 'Posiadałeś już ten zakup na tym serwerze więc został on przedłużony.');
				$View->Add('back', 'index.php?pages=home');
				$View->Out();
				
				$_SESSION['SERVERID'] = 0;

			}

			else
			{
			
				$Buy = new Buy();
			
				$Buy->AddBuy($SID, $ID, 0, $Days);
			
				$View->Load("info");
				$View->Add('title', 'Zakup dodany');
				$View->Add('header', 'Zakup dodany!');
				$View->Add('info', 'Twój zakup został poprawnie dodany i jest już aktywny!<br>Jeżeli jesteś już na serwerze to skorzystaj z komendy retry w konsoli...');
				$View->Add('back', 'index.php?pages=home');
				$View->Out();
		
			}
		
		}
	
		else
		{
		
			$View->Load("info");
			$View->Add('title', 'Błąd :: Błędny kod SMS');
			$View->Add('header', 'Błędny kod SMS!');
			$View->Add('info', 'Podany przez Ciebie kod SMS jest błędny!');
			$View->Add('back', 'index.php?pages=buy_three&id='.$ID.'&priceid='.$PriceID.'');
			$View->Out();
		
		}
		
	}

	else
	{
		
		$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='text_sms'");
		
		$Fetch = $Query->fetch();
		$SMS = $Fetch['value'];
		
		$Query = $MySQL->prepare("SELECT `name`, `server`, `description` FROM `buy` WHERE `id`=:one");
		$Query->bindValue(":one", $ID, PDO::PARAM_INT);
		$Query->execute();
	
		$Fetch = $Query->fetch();
		
		$QueryTwo = $MySQL->prepare("SELECT * FROM `service` WHERE `id`=:one");
		$QueryTwo->bindValue(":one", $PriceID, PDO::PARAM_INT);
		$QueryTwo->execute();
		
		$FetchTwo = $QueryTwo->fetch();
		
		$QueryThree = $MySQL->prepare("SELECT `vat`, `number` FROM `price` WHERE `id`=:one");
		$QueryThree->bindValue(":one", $FetchTwo['price_id'], PDO::PARAM_INT);
		$QueryThree->execute();
		
		$FetchThree = $QueryThree->fetch();
		
		$Info = 'Aby sfinalizować zakup wyślij SMS-a o treści <b>'.$SMS.'</b> na numer <b>'.$FetchThree['number'].'</b>.<br>
		Otrzymany kod SMS wpisz w pole poniżej.<br>
		Całkowity koszt SMS-a wynosi <b>'.$FetchThree['vat'].' PLN</b>!
		
		<form method="post" action="index.php?pages=buy_three&id='.$ID.'&priceid='.$PriceID.'">
		
			<input type="hidden" name="BUY" value="true">
			
			<br><input type="text" name="USER" placeholder="SteamID (STEAM_0:0:12345)" required><br>
			<br><input type="text" name="SMS" placeholder="Kod SMS" required><br>
			
			<br><button type="submit" class="przycisk">Kupuję! <i class="fa fa-chevron-circle-right"></i> </button>
		
		</form>
	
		<br><br>
		
		<img src="https://chart.apis.google.com/chart?cht=qr&chs=100x100&choe=UTF-8&chl=smsto:'.$FetchThree['number'].':'.$SMS.'">';
		
		$View->Load("info");
		$View->Add("title", "Zakup :: ".$Fetch['name']."");
		$View->Add("header", "".$Fetch['name']." (".$FetchTwo['days']." dni) [".$FetchThree['vat']." PLN]");
		$View->Add("info", $Info);
		$View->Add("back", "index.php?pages=buy_two&id=".$ID."&priceid=".$PriceID."");
		$View->Out();
	
	}

?>