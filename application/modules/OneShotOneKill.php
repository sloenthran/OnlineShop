<?php

	class OneShotOneKill
	{
		
		function CheckSMS($CodeSMS, $NumberSMS)
		{
		
			global $MySQL;
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='1s1k_api'");
			
			$Fetch = $Query->fetch();
		
			$GET = file_get_contents("http://www.1shot1kill.pl/api?type=sms&key=".$Fetch['value']."&sms_code=".$CodeSMS."&comment=Sloenthran.pl [Online Shop]");
    
			if($GET)
			{
				
				$GET = json_decode($GET);
	
				if(is_object($GET))
				{
					
					if($GET->error)
					{
						
						return false;
					
					}
					
					else
					{
						
						$Status = $GET->status;
		
						if($Status == "ok")
						{
							
							$CheckMoney = $this->CheckMoney($GET->amount, $NumberSMS);
							
							if($CheckMoney)
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
					
					}
				
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
		
		}
		
		function CheckMoney($Money, $NumberSMS)
		{
		
			switch($NumberSMS)
			{
			
				case 7136:
				
					if($Money == 0.65)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 7255:
				
					if($Money == 1.30)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 7355:
				
					if($Money == 1.95)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 7455:
				
					if($Money == 2.60)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 7555:
				
					if($Money == 3.25)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 7636:
				
					if($Money == 3.90)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 7936:
				
					if($Money == 5.85)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 91455:
				
					if($Money == 9.10)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 91955:
				
					if($Money == 12.35)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 92555:
				
					if($Money == 16.25)
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

?>