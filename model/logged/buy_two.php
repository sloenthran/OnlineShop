<?php

	$ID = $Core->ClearText($_GET['id']);
	$PriceID = $Core->ClearText($_GET['priceid']);
	
	if($PriceID == '')
	{
		
		$Query = $MySQL->prepare("SELECT * FROM `service` WHERE `buy_id`=:one");
		$Query->bindValue(":one", $ID, PDO::PARAM_INT);
		$Query->execute();
		
		while($Fetch = $Query->fetch())
		{
			
			$Menu .= '<a href="index.php?pages=buy_two&id='.$ID.'&priceid='.$Fetch['id'].'"><li><i class="fa fa-asterisk"></i> '.$Fetch['days'].' dni</li></a>';
		
		}

		$View->Load("other");
		$View->Add("header", "Wybierz ilość dni");
		$View->Add("title", "Zakupy");
		$View->Add("info", "Wybierz ilość dni z menu");
		$View->Add("back", "index.php?pages=buy&id=".$_SESSION['SERVERID']."");
		$View->Add("menu", $Menu);
		$View->Out();
		
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
		
		$QueryThree = $MySQL->prepare("SELECT `vat` FROM `price` WHERE `id`=:one");
		$QueryThree->bindValue(":one", $FetchTwo['price_id'], PDO::PARAM_INT);
		$QueryThree->execute();
		
		$FetchThree = $QueryThree->fetch();
		$Cash = $FetchThree['vat'];
	
		$Info .= ''.$Fetch['description'].'<br><br><br><br><a href="index.php?pages=buy_three&id='.$ID.'&priceid='.$PriceID.'"><button class="przycisk">Kupuję!</button></a>';

		$View->Load("info");
		$View->Add("title", "Zakup :: ".$Fetch['name']."");
		$View->Add("header", "".$Fetch['name']." na ".$FetchTwo['days']." dni [".$Cash." PLN]");
		$View->Add("info", $Info);
		$View->Add("back", "index.php?pages=buy_two&id=".$ID."");
		$View->Out();
		
	}

?>