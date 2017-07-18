<?php

	class HomePay
	{
		
		function CheckSMS($CodeSMS, $NumberSMS)
		{
			
			global $MySQL;
			
			$Query = $MySQL->prepare("SELECT `service` FROM `homepay` WHERE `number`=:one");
			$Query->bindValue(":one", $NumberSMS, PDO::PARAM_INT);
			$Query->execute();
			
			$Fetch = $Query->fetch();
			$Service = $Fetch['service'];
			
			$Query = $MySQL->prepare("SELECT `value` FROM `settings` WHERE `name`='homepay_api'");
			
			$Fetch = $Query->fetch();

			$Query = fopen("http://homepay.pl/API/check_code.php?usr_id=".$Fetch['value']."&acc_id=".$Service."&code=".$CodeSMS,'r');
			$Return = fgets($Query, 8);
			fclose($Query);
			
			if($Return == '1')
			{
				
				return true;
				
			}
			
			else
			{
				
				return false;
				
			}
			
			return false;
			
		}
		
	}

?>