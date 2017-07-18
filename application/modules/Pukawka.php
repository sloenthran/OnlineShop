<?php

	class Pukawka
	{
		
		function CheckSMS($CodeSMS, $NumberSMS)
		{
		
			global $MySQL;
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='pukawka_api'");
			
			$Fetch = $Query->fetch();
		
			$GET = json_decode(file_get_contents("https://admin.pukawka.pl/api/?keyapi=".$Fetch['value']."&type=sms&code=".$CodeSMS.""));
    
			$Status = $GET->status;
		
			if($Status == "ok")
			{
				
				if($this->CheckMoney($GET->kwota, $NumberSMS)
				{
					
					return true;
					
				}
				
				else
				{
					
					return false;
					
				}
				
			}
			
			else
			{
				
				return false;
				
			}
			
			return false;
		
		}
		
		function CheckMoney($Money, $NumberSMS)
		{
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='pukawka_api'");
			
			$Fetch = $Query->fetch();
		
			$Query = json_decode(file_get_contents("https://admin.pukawka.pl/api/?keyapi=".$Fetch['value']."&type=sms_table"));
			
			foreach($Query as $Value)
			{
				
				if($NumberSMS == $Value->numer)
				{
					
					if($Money == $Value->wartosc) 
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