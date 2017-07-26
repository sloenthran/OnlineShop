<?php

	$ID = $Core->ClearText($_GET['id']);
	$PriceID = $Core->ClearText($_GET['priceid']);
	
	if($_POST['BUY'])
	{
		
		$SID = $Core->ClearText($_POST['USER']);
	
		$Query = $MySQL->prepare("SELECT `price_id` FROM `service` WHERE `id`=:one");
		$Query->bindParam(":one", $PriceID, PDO::PARAM_INT);
		$Query->execute();
	
		$Fetch = $Query->fetch();
		$Fetch = $Fetch['price_id'];
	
		$Query = $MySQL->prepare("SELECT `value` FROM `price` WHERE `id`=:one");
		$Query->bindParam(":one", $Fetch, PDO::PARAM_INT);
		$Query->execute();
	
		$Fetch = $Query->fetch();
		$Price = $Fetch['value'];
	
		$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='pay'");
		$Fetch = $Query->fetch();
	
		$Pay = new $Fetch['value']();
		
		$Money = $Core->GetMoney();
	
		if($Money >= $Price)
		{
			
			$Query = $MySQL->prepare("UPDATE `users` SET `money`=:one WHERE `id`=:two");
		
			$Query->bindValue(":one", $Money - $Price, PDO::PARAM_INT);
			$Query->bindValue(":two", $_SESSION['ID'], PDO::PARAM_INT);

			$Query->execute();
		
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
			
				$Buy->AddBuy($SID, $ID, $_SESSION['ID'], $Days);
			
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
			$View->Add('title', 'Błąd :: Za mało wPLN');
			$View->Add('header', 'Za mało wPLN!');
			$View->Add('info', 'Posiadasz za mało wPLN w portfelu!<br>Przed zakupem doładuj swój portfel!');
			$View->Add('back', 'index.php?pages=add_cash');
			$View->Out();
		
		}
		
	}

	else
	{
		
		$Query = $MySQL->prepare("SELECT `name`, `server`, `description` FROM `buy` WHERE `id`=:one");
		$Query->bindValue(":one", $ID, PDO::PARAM_INT);
		$Query->execute();
	
		$Fetch = $Query->fetch();
		
		$QueryTwo = $MySQL->prepare("SELECT * FROM `service` WHERE `id`=:one");
		$QueryTwo->bindValue(":one", $PriceID, PDO::PARAM_INT);
		$QueryTwo->execute();
		
		$FetchTwo = $QueryTwo->fetch();
		
		$QueryThree = $MySQL->prepare("SELECT `value` FROM `price` WHERE `id`=:one");
		$QueryThree->bindValue(":one", $FetchTwo['price_id'], PDO::PARAM_INT);
		$QueryThree->execute();
		
		$FetchThree = $QueryThree->fetch();
		
		$Info = 'Z twojego portfela zostanie pobrane <b>'.$FetchThree['value'].' wPLN</b>!
		
		<form method="post" action="index.php?pages=buy_three&id='.$ID.'&priceid='.$PriceID.'">
		
			<input type="hidden" name="BUY" value="true">
			
			<br><input type="text" name="USER" placeholder="SteamID (STEAM_0:0:12345)" required><br>
			
			<br><button type="submit" class="przycisk">Kupuję! <i class="fa fa-chevron-circle-right"></i> </button>
		
		</form>';
		
		$View->Load("info");
		$View->Add("title", "Zakup :: ".$Fetch['name']."");
		$View->Add("header", "".$Fetch['name']." (".$FetchTwo['days']." dni) [".$FetchThree['value']." wPLN]");
		$View->Add("info", $Info);
		$View->Add("back", "index.php?pages=buy_two&id=".$ID."&priceid=".$PriceID."");
		$View->Out();
	
	}

?>