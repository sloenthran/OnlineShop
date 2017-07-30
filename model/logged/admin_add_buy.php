<?php

	if($Core->CheckAdmin())
	{
		
		$ID = $Core->ClearText($_GET['id']);
		
		if($ID == '')
		{
			
			$Query = $MySQL->query("SELECT `id`, `name` FROM `servers`");
			
			if($Query->rowCount() > 0)
			{
				
				$Info .= '<a href="index.php?pages=admin_add_buy&id=0"><button class="przycisk">Wszystkie serwery</button></a><br>';
			
				while($Fetch = $Query->fetch())
				{
		
					$Info .= '<a href="index.php?pages=admin_add_buy&id='.$Fetch['id'].'"><button class="przycisk">'.$Fetch['name'].'</button></a><br>';
				
				}
		
				$View->Load("admin_servers");
				$View->Add('title', 'Wybierz serwer');
				$View->Add('header', 'Wybierz serwer');
				$View->Add('info', $Info);
				$View->Out();
				
			}
			
			else
			{
			
				$View->Load("info");
				$View->Add("title", "Błąd :: Brak serwerów");
				$View->Add("header", "Błąd! Brak serwerów!");
				$View->Add("info", "Nie ma żadnych serwerów!");
				$View->Add("back", "index.php?pages=admin_servers");
				$View->Out();
				
			}
			
		}
		
		else
		{
	
			if($_POST['ADD'])
			{
			
				$Name = $Core->ClearText($_POST['NAME']);
				$Flags = $Core->ClearText($_POST['FLAGS']);
				$Text = nl2br($_POST['TEXT']);
			
				if($Name == '' || $Flags == '')
				{
		
					$View->Load("info");
					$View->Add("title", "Błąd :: Puste pola");
					$View->Add("header", "Błąd! Puste pola!");
					$View->Add("info", "Pola formularza nie mogą być puste!");
					$View->Add("back", "index.php?pages=admin_add_buy");
					$View->Out();
		
				}
	
				else
				{
		
					$Query = $MySQL->prepare("INSERT INTO `buy` VALUES(NULL, :one, :three, :four, :five)");
				
					$Query->bindValue(":one", $Name, PDO::PARAM_STR);
					$Query->bindValue(":three", $Flags, PDO::PARAM_STR);
					$Query->bindValue(":four", $Text, PDO::PARAM_STR);
					$Query->bindValue(":five", $ID, PDO::PARAM_INT);
				
					$Query->execute();
				
					$View->Load("info");
					$View->Add("title", "Usługa dodana");
					$View->Add("header", "Usługa dodana!");
					$View->Add("info", "Usługa została poprawnie dodana!");
					$View->Add("back", "index.php?pages=admin_add_buy");
					$View->Out();
					
					$Query = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
					$Query->bindValue(":one", $ID, PDO::PARAM_INT);
					$Query->execute();
					
					$Fetch = $Query->fetch();
			
					$Core->AddAdminLogs("Dodano usługę ".$Name." z flagami ".$Flags." na serwerze ".$Fetch['name']."");
			
				}
			
			}
		
			else
			{
			
				$Info = '<form method="post" action="index.php?pages=admin_add_buy&id='.$ID.'">
		
					<input type="hidden" name="ADD" value="true">
			
					<br><input type="text" name="NAME" placeholder="Nazwa"><br>
					<br><input type="text" name="FLAGS" placeholder="Flagi"><br>
					<br><textarea name="TEXT" placeholder="Opis"></textarea><br>
				
					<br><button type="submit" class="przycisk">Dodaj usługę <i class="fa fa-chevron-circle-right"></i> </button>
		
				</formm>';
			
				$View->Load("admin_servers");
				$View->Add('title', 'Dodaj usługę');
				$View->Add('header', 'Dodaj usługę');
				$View->Add('info', $Info);
				$View->Out();
				
			}
	
		}
	
	}
	
	else
	{
	
		$View->Load("info");
		$View->Add('title', 'Błąd :: Brak uprawnień');
		$View->Add('header', 'Błąd! Brak uprawnień!');
		$View->Add('info', 'Nie posiadasz uprawnień administracyjnych!');
		$View->Add('back', 'index.php?pages=home');
		$View->Out();
	
	}

?>