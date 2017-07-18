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
	
	}
	
?>