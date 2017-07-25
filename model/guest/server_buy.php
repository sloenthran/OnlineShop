<?php

	$Code = $Core->ClearText($_GET['code']);
	$Code = str_replace('&amp;quot;', '', $Code);
	
	$_SESSION['SERVERID'] = $Core->ClearText($_GET['serverid']);
	
	$SID = $Core->ClearText($_GET['sid']);
	
	$ID = $Core->ClearText($_GET['premium']);
	
	$Query = $MySQL->prepare("SELECT `price_id`, `buy_id` FROM `service` WHERE `id`=:one");
	$Query->bindParam(":one", $ID, PDO::PARAM_INT);
	$Query->execute();
	
	$Fetch = $Query->fetch();
	$BuyID = $Fetch['buy_id'];
	$Fetch = $Fetch['price_id'];
	
	$Query = $MySQL->prepare("SELECT `number` FROM `price` WHERE `id`=:one");
	$Query->bindParam(":one", $Fetch, PDO::PARAM_INT);
	$Query->execute();
	
	$Fetch = $Query->fetch();
	$Number = $Fetch['number'];
	
	$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='pay'");
	$Fetch = $Query->fetch();
	
	$Pay = new $Fetch['value']();
	
	if($Pay->CheckSMS($Code, $Number) || $Core->CheckSMS($Code, $Number))
	{
		
		$Query = $MySQL->prepare("SELECT `days` FROM `service` WHERE `id`=:one");
		$Query->bindValue(":one", $ID, PDO::PARAM_INT);
		$Query->execute();
		
		$Fetch = $Query->fetch();
		
		$Days = $Fetch['days'];
		
		$Query = $MySQL->prepare("SELECT `id` FROM `premium_cache` WHERE `nick`=:one AND `premium_id`=:two AND `server`=:three");
		$Query->bindValue(":one", $SID, PDO::PARAM_STR);
		$Query->bindValue(":two", $BuyID, PDO::PARAM_INT);
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
			
			echo 'extension|' . $SID;

		}

		else
		{
			
			$Buy = new Buy();
			
			$Buy->AddBuy($SID, $BuyID, 0, $Days);
			
			echo 'ok|' . $SID;
		
		}
		
	}
	
	else
	{
		
		echo 'bad|' . $SID;
		
	}

?>