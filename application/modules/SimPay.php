<?php

	class SimPay
	{
		
		function CheckSMS($CodeSMS, $NumberSMS)
		{
			
			global $MySQL;
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='simpay_api'");
			
			$Fetch = $Query->fetch();
			
			$Key = $Fetch['value'];
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='simpay_api_key'");
			
			$Fetch = $Query->fetch();
			
			$Pass = $Fetch['value'];
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='simpay_service'");
			
			$Fetch = $Query->fetch();
			
			$Service = $Fetch['value'];
			
			$Options = array(
				CURLOPT_URL => 'https://simpay.pl/api/1/status',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_TIMEOUT => 10,
				CURLOPT_HEADER  => false
			);
			
			$Data = array(
				'auth' => array(
					'key' => $Key,
					'secret' => $Pass
				),
				'service_id' => $Service,
				'number' => $NumberSMS,
				'code' => $CodeSMS
			);
			
			$cURL = curl_init();
			
			curl_setopt_array($cURL, $Options);
			
			curl_setopt($cURL, CURLOPT_POST, true);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, json_encode(array('params' => $Data)));
			
			$Query = curl_exec($cURL);
			
			curl_close($cURL);
			
			$Response = json_decode($Query, true);
			
			if(isset($Response['respond']['status']) && $Response['respond']['status'] == 'OK')
			{
				
				return true;
				
			}
			
			return false;
			
		}
		
	}

?>