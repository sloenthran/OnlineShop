<?php

	if($Core->CheckAdmin())
	{
		
		$ID = $Core->ClearText($_GET['id']);
		
		if($ID == '')
		{
			
			$Query = $MySQL->query("SELECT `id`, `name` FROM `servers`");
			
			if($Query->rowCount() > 0)
			{
			
				while($Fetch = $Query->fetch())
				{
		
					$Info .= '<a href="index.php?pages=admin_add_buy_credits&id='.$Fetch['id'].'"><button class="przycisk">'.$Fetch['name'].'</button></a><br>';
				
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
				$Command = $_POST['COMMAND'];
			
				if($Command == '')
				{
		
					$View->Load("info");
					$View->Add("title", "Błąd :: Puste pola");
					$View->Add("header", "Błąd! Puste pola!");
					$View->Add("info", "Pola formularza nie mogą być puste!");
					$View->Add("back", "index.php?pages=admin_add_buy_credits");
					$View->Out();
		
				}
	
				else
				{
		
					$Query = $MySQL->prepare("INSERT INTO `buy_credits` VALUES(NULL, :one, :two)");
				
					$Query->bindValue(":one", $Command, PDO::PARAM_STR);
					$Query->bindValue(":two", $ID, PDO::PARAM_INT);
				
					$Query->execute();
				
					$View->Load("info");
					$View->Add("title", "Usługa dodana");
					$View->Add("header", "Usługa dodana!");
					$View->Add("info", "Usługa została poprawnie dodana!");
					$View->Add("back", "index.php?pages=admin_add_buy_credits");
					$View->Out();
					
					$Query = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
					$Query->bindValue(":one", $ID, PDO::PARAM_INT);
					$Query->execute();
					
					$Fetch = $Query->fetch();
			
					$Core->AddAdminLogs("Dodano obsługę kredytów na serwerze ".$Fetch['name']."");
			
				}
			
			}
		
			else
			{
			
				$Info = '<form method="post" action="index.php?pages=admin_add_buy_credits&id='.$ID.'">
		
					<input type="hidden" name="ADD" value="true">
			
					<br><input type="text" name="COMMAND" placeholder="Komenda" value=\'sm_givecredits "[player]" "[credits]"\'><br>
				
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