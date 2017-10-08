<?php

	class LiveServer
	{
		
		function CheckSMS($CodeSMS, $NumberSMS)
		{
            global $MySQL;
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name` = 'liveserver_cid'");
            $lvsCID = $Query->fetch();
            
            $Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name` = 'liveserver_pin'");
			$lvsPIN = $Query->fetch();
			
			$ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://rec.liveserver.pl/api?channel=sms&return_method=seperator');
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'client_id='. (INT)$lvsCID['value'] .'&pin='. urlencode($lvsPIN['value']) .'&code='. urlencode($CodeSMS) .'');
            $data = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
			
			if($httpcode >= 200 && $httpcode < 300)
            {
                $recData = explode(' ', $data, 8);
    
                if(count($recData) < 8)
                {
                    return false;
                }
                else
                {
                    if($recData[6] == 0 && $recData[4] == $NumberSMS)
                    {
                        return true;
                    }
                }
            }
            else
            {
                return false;
            }
			
			return false;
			
		}
		
	}

?>