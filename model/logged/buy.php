<?php

	$ID = $Core->ClearText($_GET['id']);
	
	if(!$ID || $ID == '')
	{
		
		$_SESSION['SERVERID'] = 0;

		$Query = $MySQL->query("SELECT `id`, `name` FROM `servers`");
		
		while($Fetch = $Query->fetch())
		{
		
			$Menu .= '<a href="index.php?pages=buy&id='.$Fetch['id'].'"><li><i class="fa fa-asterisk"></i> '.$Fetch['name'].'</li></a>';
		
		}
		
		$View->Load("other");
		$View->Add("header", "Wybierz serwer");
		$View->Add("title", "Zakupy");
		$View->Add("info", "Wybierz serwer z menu");
		$View->Add("menu", $Menu);
		$View->Add("back", "index.php?pages=home");
		$View->Out();
		
	}
	
	else
	{
		
		$_SESSION['SERVERID'] = $ID;
	
		$Query = $MySQL->prepare("SELECT `id`, `name` FROM `buy` WHERE `server`=:one OR `server`='0'");
		
		$Query->bindValue(":one", $ID, PDO::PARAM_INT);
		
		$Query->execute();
		
		while($Fetch = $Query->fetch())
		{
			
			$Menu .= '<a href="index.php?pages=buy_two&id='.$Fetch['id'].'"><li><i class="fa fa-asterisk"></i> '.$Fetch['name'].'</li></a>';
		
		}

		$View->Load("other");
		$View->Add("header", "Wybierz usługę");
		$View->Add("title", "Zakupy");
		$View->Add("info", "Wybierz usługę z menu");
		$View->Add("back", "index.php?pages=buy");
		$View->Add("menu", $Menu);
		$View->Out();
	
	}

?>