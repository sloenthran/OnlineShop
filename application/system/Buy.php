<?php

	class Buy
	{
	
		function AddBuy($Nick, $PremiumID, $ShopID, $Days)
		{
		
			global $MySQL, $_SESSION;
			
			$Query = $MySQL->prepare("SELECT * FROM `buy` WHERE `id`=:one");
			
			$Query->bindValue(":one", $PremiumID, PDO::PARAM_INT);
			
			$Query->execute();
			
			$Fetch = $Query->fetch();
			
			$BuyData = $Fetch;
			
			if($BuyData['server'] == 0) { $Server = $_SESSION['SERVERID']; } else { $Server = $BuyData['server']; }
			
			$Query = $MySQL->prepare("SELECT * FROM `premium` WHERE `nick`=:one AND `server`=:two");
			
			$Query->bindValue(":one", $Nick, PDO::PARAM_STR);
			$Query->bindValue(":two", $Server, PDO::PARAM_INT);
			
			$Query->execute();
			
			$Days = $Days * 86400;
			
			$Time = time() + $Days;
			
			if($Query->rowCount() > 0)
			{
			
				$Fetch = $Query->fetch();
			
				$Query = $MySQL->prepare("INSERT INTO `premium_cache` VALUES(NULL, :one, :three, :four, :five, :six, :seven)");
				
				$Query->bindValue(":one", $Nick, PDO::PARAM_STR);
				$Query->bindValue(":three", $BuyData['flags'], PDO::PARAM_STR);
				$Query->bindValue(":four", $Server, PDO::PARAM_INT);
				$Query->bindValue(":five", $Time, PDO::PARAM_INT);
				$Query->bindValue(":six", $ShopID, PDO::PARAM_INT);
				$Query->bindValue(":seven", $PremiumID, PDO::PARAM_INT);
				
				$Query->execute();
			
				$NewFlags = $this->SumFlags($Fetch['flags'], $BuyData['flags']);
				
				$Query = $MySQL->prepare("UPDATE `premium` SET `flags`=:one WHERE `nick`=:two AND `server`=:three");
				
				$Query->bindValue(":one", $NewFlags, PDO::PARAM_STR);
				$Query->bindValue(":two", $Nick, PDO::PARAM_STR);
				$Query->bindValue(":three", $Server, PDO::PARAM_INT);
				
				$Query->execute();		
			
			}
			
			else
			{
				
				$Query = $MySQL->prepare("INSERT INTO `premium` VALUES(NULL, :one, :three, :four)");
				
				$Query->bindValue(":one", $Nick, PDO::PARAM_STR);
				$Query->bindValue(":three", $BuyData['flags'], PDO::PARAM_STR);
				$Query->bindValue(":four", $Server, PDO::PARAM_INT);
				
				$Query->execute();
				
				$Query = $MySQL->prepare("INSERT INTO `premium_cache` VALUES(NULL, :one, :three, :four, :five, :six, :seven)");
				
				$Query->bindValue(":one", $Nick, PDO::PARAM_STR);
				$Query->bindValue(":three", $BuyData['flags'], PDO::PARAM_STR);
				$Query->bindValue(":four", $Server, PDO::PARAM_INT);
				$Query->bindValue(":five", $Time, PDO::PARAM_INT);
				$Query->bindValue(":six", $ShopID, PDO::PARAM_INT);
				$Query->bindValue(":seven", $PremiumID, PDO::PARAM_INT);
				
				$Query->execute();
			
			}
			
			$_SESSION['SERVERID'] = 0;
		
		}
	
		function SumFlags($FlagsA, $FlagsB)
		{
		
			for($Number = 0; $Number < strlen($FlagsB); $Number++) 
			{
				if(!(strlen(strstr($FlagsA, $FlagsB[$Number]))))
				{
				
					$Out .= $FlagsB[$Number];
					
				}
				
			}
			
			$Text = str_split($FlagsA . $Out);
			
			sort($Text);
			
			return implode('', $Text);
		
		}
	
	}

?>