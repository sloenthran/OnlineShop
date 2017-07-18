<?php

	class HostPlay
	{
		
		function CheckSMS($CodeSMS, $NumberSMS)
		{
		
			global $MySQL;
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='hostplay_api'");
			
			$Fetch = $Query->fetch();
		
			$GET = file_get_contents("http://hostplay.pl/api/payment/api_code_verify.php?payment=homepay_sms&userid=".$Fetch['value']."&comment=Sloenthran.pl [Online Shop]&code=".$CodeSMS."");
    
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
							
							$CheckMoney = $this->CheckMoney($GET->kwota, $NumberSMS);
							
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
			
				case 7055:
				
					if($Money == 0.34)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 7155:
				
					if($Money == 0.67)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 7255:
				
					if($Money == 1.35)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 7355:
				
					if($Money == 2.02)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 7455:
				
					if($Money == 2.70)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 7555:
				
					if($Money == 3.38)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 76660:
				
					if($Money == 4.05)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 7955:
				
					if($Money == 6.08)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 91055:
				
					if($Money == 6.76)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 91155:
				
					if($Money == 7.43)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 91455:
				
					if($Money == 9.47)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 91955:
				
					if($Money == 12.85)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
			
				case 92055:
				
					if($Money == 13.53)
					{
					
						return true;
					
					}
					
					else
					{
					
						return false;
					
					}
					
				break;
				
				case 92520:
				
					if($Money == 16.91)
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