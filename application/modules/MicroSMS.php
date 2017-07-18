<?php

	class MicroSMS
	{
		
		function CheckSMS($CodeSMS, $NumberSMS)
		{
			
			global $MySQL;
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='microsms_user'");
			$Fetch = $Query->fetch();
			
			$User = $Fetch['value'];
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='microsms_service'");
			$Fetch = $Query->fetch();
			
			$Service = $Fetch['value'];
			
			$Query = @file_get_contents("http://microsms.pl/api/v2/multi.php?userid=".$User."&code=".$CodeSMS."&serviceid=".$Service);
			$Fetch = json_decode($Query);
			
			if ($Fetch->data->status == 1) { return true; }
			
			return false;
			
		}
		
	}

?>