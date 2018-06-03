<?php

	$Code = $Core->ClearText($_GET['code']);
	$Code = str_replace('&amp;quot;', '', $Code);
	
	$_SESSION['SERVERID'] = $Core->ClearText($_GET['serverid']);
	
	$SID = $Core->ClearText($_GET['sid']);
	
	$ID = $Core->ClearText($_GET['premium']);
	
	$Query = $MySQL->prepare("SELECT `price_id`, `amount`, `buy_id` FROM `service_credits` WHERE `id`=:one");
	$Query->bindParam(":one", $ID, PDO::PARAM_INT);
	$Query->execute();
	
	$Fetch = $Query->fetch();
	
	$Data[0] = $Fetch['amount'];
	$BuyID = $Fetch['buy_id'];
	$Fetch = $Fetch['price_id'];
	
	$Query = $MySQL->prepare("SELECT `number`, `value` FROM `price` WHERE `id`=:one");
	$Query->bindParam(":one", $Fetch, PDO::PARAM_INT);
	$Query->execute();
	
	$Fetch = $Query->fetch();
	$Number = $Fetch['number'];
	$Data[2] = $Fetch['value'];
	
	$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='pay'");
	$Fetch = $Query->fetch();
	
	$Pay = new $Fetch['value']();
	
	if($Pay->CheckSMS($Code, $Number) || $Core->CheckSMS($Code, $Number))
	{
		
		$Query = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
		$Query->bindValue(":one", $_SESSION['SERVERID'], PDO::PARAM_INT);
		$Query->execute();
		
		$Fetch = $Query->fetch();
		$Data[1] = $Fetch['name'];

		$Core->AddGuestBuyLogs('[Plugin] Zakupiono <b>'.$Data[0].'</b> kredytów na serwerze <b>'.$Data[1].'</b> kodem SMS <b>'.$Code.'</b> ('.$SID.')');
		
		$Core->AddServerCash($_GET['serverid'], $Data[2]);
		
		$Query = $MySQL->prepare("SELECT `command` FROM `buy_credits` WHERE `id`=:one");
		$Query->bindValue(":one", $BuyID, PDO::PARAM_INT);
		$Query->execute();
		
		$Fetch = $Query->fetch();
		
		$Command = str_replace('[player]', $SID, $Fetch['command']);
		$Command = str_replace('[credits]', $Data[0], $Command);
		
		echo 'ok|'.$SID.'|'.$Command.'';
		
	}
	
	else
	{
		
		echo 'bad|' . $SID;
		
	}

?>