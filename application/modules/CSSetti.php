<?php

	class CSSetti
	{
		
		function CheckSMS($CodeSMS, $NumberSMS)
		{
		
			global $MySQL;
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='cssetti_api'");
			
			$Fetch = $Query->fetch();
		
			$Query = file_get_contents("https://cssetti.pl/Api/SmsApiV2CheckCode.php?UserId=".$Fetch['value']."&Code=".$CodeSMS."");
			
			if($Query > 0)
			{
				
				if($this->CheckMoney($Query, $NumberSMS))
				{
					
					return true;
					
				}
				
				else
				{
					
					return false;
					
				}
				
			}
			
			return false;
		
		}
		
		function CheckMoney($Money, $NumberSMS)
		{
			
			$Query = json_decode(file_get_contents('http://cssetti.pl/Api/SmsApiV2GetData.php'), true);
			
			foreach($Query['Numbers'] as $Value)
			{
				
				if($Value['Number'] == $NumberSMS)
				{
					
					if($Money == $Value['TopUpAmount']) 
					{ 
						
						return true; 
						
					}
					
					else
					{ 
				
						return false; 
						
					}
					
					break;
					
				}
			
			}
			
		}
	
	}

?>
