<?php

	class Core
	{
	
		function ClearText($Text)
		{
		
			if(get_magic_quotes_gpc())
			{
		
				$Text = stripslashes($Text);
			
			}
		
			$Text = trim($Text);
			$Text = htmlspecialchars($Text);
			$Text = htmlentities($Text);
			$Text = strip_tags($Text);
		
			return $Text;
		
		}
		
		function GetMoney()
		{
		
			global $_SESSION, $MySQL;
		
			$Query = $MySQL->prepare("SELECT `money` FROM `users` WHERE `id`=:one");
			
			$Query->bindValue(':one', $_SESSION['ID'], PDO::PARAM_INT);
			
			$Query->execute();
			
			$Fetch = $Query->fetch();
			
			return $Fetch['money'];
		
		}
		
		function GetName()
		{
		
			global $_SESSION, $MySQL;
		
			$Query = $MySQL->prepare("SELECT `login` FROM `users` WHERE `id`=:one");
			
			$Query->bindValue(':one', $_SESSION['ID'], PDO::PARAM_INT);
			
			$Query->execute();
			
			$Fetch = $Query->fetch();
			
			return $Fetch['login'];
		
		}
		
		function CheckAdmin()
		{
			
			global $_SESSION, $MySQL;
			
			if($_SESSION['ID_TIME'] + 300 < time())
			{
				
				$Query = $MySQL->prepare("SELECT `ranks` FROM `users` WHERE `id`=:one");
				$Query->bindValue(":one", $_SESSION['ID'], PDO::PARAM_INT);
				$Query->execute();
				
				$Fetch = $Query->fetch();
				
				$_SESSION['RANKS'] = $Fetch['ranks'];
			
			}
			
			if($_SESSION['RANKS'] == 1)
			{
				
				return true;
				
			}
			
			else
			{
				
				return false;
				
			}
			
		}
		
		function CheckSMS($Code, $Number)
		{
			
			global $MySQL;
			
			$Query = $MySQL->prepare("SELECT `id` FROM `sms_code` WHERE `code`=:one AND `number`=:two AND `status`='1'");
			$Query->bindValue(":one", $Code, PDO::PARAM_STR);
			$Query->bindValue(":two", $Number, PDO::PARAM_INT);
			$Query->execute();
			
			if($Query->rowCount() > 0)
			{
				
				$Fetch = $Query->fetch();
				
				$Query = $MySQL->prepare("UPDATE `sms_code` SET `status`='0' WHERE `id`=:one");
				$Query->bindValue(":one", $Fetch['id'], PDO::PARAM_INT);
				$Query->execute();
				
				return true;
				
			}
			
			return false;
			
		}
		
		function GetPay()
		{
		
			$DIR = opendir('./application/modules/');
		
			while($File = readdir($DIR))
			{
				
				if($File == '.' or $File == '..' or $File == 'SystemLoader.php')
				{
					
					continue;
				
				}
		
				$Info = pathinfo($File);
			
				$Pay[] = $Info['filename'];
				
			}
		
			return $Pay;
			
		}
		
		function AddAdminLogs($Text)
		{
			
			global $_SESSION, $MySQL;
			
			$Query = $MySQL->prepare("INSERT INTO `admin_logs` VALUES(NULL, :one, :two, :three, UNIX_TIMESTAMP(NOW()))");
			$Query->bindValue(":one", $Text, PDO::PARAM_STR);
			$Query->bindValue(":two", $_SESSION['ID'], PDO::PARAM_INT);
			$Query->bindValue(":three", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
			$Query->execute();
			
		}
		
		function AddLoginLogs($Text)
		{
			
			global $_SESSION, $MySQL;
			
			$Query = $MySQL->prepare("INSERT INTO `login_logs` VALUES(NULL, :one, :two, :three, UNIX_TIMESTAMP(NOW()))");
			$Query->bindValue(":one", $Text, PDO::PARAM_STR);
			$Query->bindValue(":two", $_SESSION['ID'], PDO::PARAM_INT);
			$Query->bindValue(":three", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
			$Query->execute();
			
		}
		
		function AddBuyLogs($Text)
		{
			
			global $_SESSION, $MySQL;
			
			$Query = $MySQL->prepare("INSERT INTO `buy_logs` VALUES(NULL, :one, :two, :three, UNIX_TIMESTAMP(NOW()))");
			$Query->bindValue(":one", $Text, PDO::PARAM_STR);
			$Query->bindValue(":two", $_SESSION['ID'], PDO::PARAM_INT);
			$Query->bindValue(":three", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
			$Query->execute();
			
		}
		
		function AddGuestBuyLogs($Text)
		{
			
			global $MySQL;
			
			$Query = $MySQL->prepare("INSERT INTO `buy_logs` VALUES(NULL, :one, :two, :three, UNIX_TIMESTAMP(NOW()))");
			$Query->bindValue(":one", $Text, PDO::PARAM_STR);
			$Query->bindValue(":two", 0, PDO::PARAM_INT);
			$Query->bindValue(":three", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
			$Query->execute();
			
		}
		
		function AddCashLogs($Text)
		{
			
			global $_SESSION, $MySQL;
			
			$Query = $MySQL->prepare("INSERT INTO `cash_logs` VALUES(NULL, :one, :two, :three, UNIX_TIMESTAMP(NOW()))");
			$Query->bindValue(":one", $Text, PDO::PARAM_STR);
			$Query->bindValue(":two", $_SESSION['ID'], PDO::PARAM_INT);
			$Query->bindValue(":three", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
			$Query->execute();
			
		}
		
		function AddOtherLogs($Text)
		{
			
			global $_SESSION, $MySQL;
			
			$Query = $MySQL->prepare("INSERT INTO `other_logs` VALUES(NULL, :one, :two, :three, UNIX_TIMESTAMP(NOW()))");
			$Query->bindValue(":one", $Text, PDO::PARAM_STR);
			$Query->bindValue(":two", $_SESSION['ID'], PDO::PARAM_INT);
			$Query->bindValue(":three", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
			$Query->execute();
			
		}
		
		function AddServerCash($ID, $Cash)
		{
			
			global $MySQL;
			
			$Query = $MySQL->prepare("INSERT INTO `server_cash` VALUES(NULL, :one, :two, CURRENT_TIMESTAMP)");
			
			$Query->bindValue(":one", $ID, PDO::PARAM_INT);
			$Query->bindValue(":two", $Cash, PDO::PARAM_INT);
			
			$Query->execute();
			
		}
	
	}
	
?>