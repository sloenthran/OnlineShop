<?php

	$Buy = new Buy();
	
	$Query = $MySQL->query("SELECT * FROM `premium_cache`");
	
	while($Fetch = $Query->fetch())
	{
	
		if(time() >= $Fetch['time'])
		{
		
			$QueryTwo = $MySQL->prepare("DELETE FROM `premium_cache` WHERE `id`=:one");
			$QueryTwo->bindValue(":one", $Fetch['id'], PDO::PARAM_INT);
			$QueryTwo->execute();
		
			$QueryTwo = $MySQL->prepare("SELECT * FROM `premium_cache` WHERE `nick`=:one AND `server`=:two");
			$QueryTwo->bindValue(":one", $Fetch['nick'], PDO::PARAM_STR);
			$QueryTwo->bindValue(":two", $Fetch['server'], PDO::PARAM_INT);
			$QueryTwo->execute();
			
			if($QueryTwo->rowCount() > 0)
			{
			
				while($FetchTwo = $QueryTwo->fetch())
				{
				
					$Flags = $Buy->SumFlags($Flags, $FetchTwo['flags']);
				
				}
			
				$QueryThree = $MySQL->prepare("UPDATE `premium` SET `flags`=:one WHERE `nick`=:two AND `server`=:three");
				
				$QueryThree->bindValue(":one", $Flags, PDO::PARAM_STR);
				$QueryThree->bindValue(":two", $Fetch['nick'], PDO::PARAM_STR);
				$QueryThree->bindValue(":three", $Fetch['server'], PDO::PARAM_INT);
				
				$QueryThree->execute();		
			
			}
			
			else
			{
			
				$QueryThree = $MySQL->prepare("DELETE FROM `premium` WHERE `nick`=:one AND `server`=:two");
				
				$QueryThree->bindValue(":one", $Fetch['nick'], PDO::PARAM_STR);
				$QueryThree->bindValue(":two", $Fetch['server'], PDO::PARAM_INT);
				
				$QueryThree->execute();
			
			}
		
		}
	
	}

?>